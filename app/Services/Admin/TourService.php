<?php

namespace App\Services\Admin;

use App\Events\AfterTourInit;
use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourInfoResource;
use App\Http\Resources\TourResource;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\BaseServices\XLDirectionService;
use App\Services\GoogleApiService;
use App\Services\OrderNoRuleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\OrderTrailService;
use App\Services\Traits\TourRedisLockTrait;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TourService extends BaseService
{
    use TourRedisLockTrait;

    /**
     * @var GoogleApiService
     */
    public $apiClient;

    /**
     * @var XLDirectionService
     */
    public $directionClient;

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_id' => ['=', 'driver_id'],
        'line_name' => ['like', 'line_name'],
        'tour_no' => ['like', 'tour_no']
    ];

    public $orderBy = ['created_at' => 'desc'];

    public function __construct(Tour $tour, GoogleApiService $client, XLDirectionService $directionClient)
    {
        $this->model = $tour;
        $this->query = $this->model::query();
        $this->resource = TourResource::class;
        $this->infoResource = TourInfoResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
        $this->apiClient = $client;
        $this->directionClient = $directionClient;
    }

    /**
     * 站点 服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 订单服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    /**
     * 司机 服务
     * @return DriverService
     */
    private function getDriverService()
    {
        return self::getInstance(DriverService::class);
    }

    /**
     * 车辆 服务
     * @return CarService
     */
    private function getCarService()
    {
        return self::getInstance(CarService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    private function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    private function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    public function getPageList()
    {
        if (isset($this->filters['status'][1]) && (intval($this->filters['status'][1]) == 0)) {
            unset($this->filters['status']);
        }
        return parent::getPageList();
    }

    //新增
    public function store($params)
    {
    }

    /**
     * 分配司机
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignDriver($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => BaseConstService::TOUR_STATUS_1], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不是待分配状态');
        }
        $tour = $tour->toArray();
        //查看当前司机是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['driver_id' => $params['driver_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前司机已被分配,请选择其他司机');
        }
        //获取司机
        $driver = $this->getDriverService()->getInfo(['id' => $params['driver_id'], 'is_locked' => BaseConstService::DRIVER_TO_NORMAL], ['*'], false);
        if (empty($driver)) {
            throw new BusinessLogicException('司机不存在或已被锁定');
        }
        $driver = $driver->toArray();
        //取件线路分配 由于取件线路,站点,订单的已分配状态都为2,所以只需取一个状态即可(ORDER_STATUS_2,BATCH_ASSIGNED,TOUR_STATUS_2)
        $rowCount = $this->assignOrCancelAssignAll($tour, ['driver_id' => $driver['id'], 'driver_name' => $driver['first_name'] . $driver['last_name'], 'driver_phone' => $driver['phone'], 'status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机分配失败,请重新操作');
        }
    }

    /**
     * 取消司机分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancelAssignDriver($id)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => BaseConstService::TOUR_STATUS_2], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不是已分配状态');
        }
        $rowCount = $this->assignOrCancelAssignAll($tour->toArray(), ['driver_id' => null, 'driver_name' => null, 'driver_phone' => null, 'status' => BaseConstService::ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机取消分配失败,请重新操作');
        }
    }


    /**
     * 分配车辆
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignCar($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不允许分配车辆');
        }
        $tour = $tour->toArray();
        //查看当前车辆是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['car_id' => $params['car_id'], 'execution_date' => $tour['execution_date']], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前车辆已被分配,请选择其他车辆');
        }
        //获取车辆
        $car = $this->getCarService()->getInfo(['id' => $params['car_id'], 'is_locked' => BaseConstService::CAR_TO_NORMAL], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('车辆不存在或已被锁定');
        }
        //分配
        $car = $car->toArray();
        $rowCount = $this->assignOrCancelAssignAll($tour, ['car_id' => $car['id'], 'car_no' => $car['car_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆分配失败,请重新操作');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('tour_no', $tour['tour_no'])->get(), BaseConstService::ORDER_TRAIL_ASSIGN_DRIVER);
    }

    /**
     * 取消车辆分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancelAssignCar($id)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不能分配车辆');
        }
        $tour = $tour->toArray();
        $rowCount = $this->assignOrCancelAssignAll($tour, ['car_id' => null, 'car_no' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆取消分配失败,请重新操作');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('tour_no', $tour['tour_no'])->get(), BaseConstService::ORDER_TRAIL_CANCEL_ASSIGN_DRIVER);
    }


    /**
     * 分配或取消分配司机或车辆到取件线路-站点-订单
     * @param $tour
     * @param $data
     * @return bool
     */
    private function assignOrCancelAssignAll($tour, $data)
    {
        //取件线路
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) return false;
        //站点
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;
        //订单
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;


        return true;
    }

    /**
     * 取消锁定-将状态改为已分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function unlock($id)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_3) {
            throw new BusinessLogicException('取件线路当前状态不允许取消锁定');
        }
        //取件线路 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取件线路取消锁定失败,请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败,请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_3], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单取消锁定失败,请重新操作');
        }
    }


    /**
     * 站点加入取件线路
     * @param $batch
     * @param $line
     * @param $type
     * @return BaseService|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function join($batch, $line, $type)
    {
        //若不存在取件线路或者超过最大订单量,则新建取件线路
        $this->query->where(DB::raw('expect_pickup_quantity+expect_pie_quantity'), '<', $line['order_max_count']);
        $tour = parent::getInfoLock(['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]], ['*'], false);
        //加入取件线路
        $tour = !empty($tour) ? $this->joinExistTour($tour->toArray(), $type) : $this->joinNewTour($batch, $line, $type);
        return $tour;
    }

    /**
     * 加入新的取件线路
     * @param $line
     * @param $batch
     * @param $orderType
     * @return BaseService|array|\Illuminate\Database\Eloquent\Model|mixed
     * @throws BusinessLogicException
     */
    private function joinNewTour($batch, $line, $orderType)
    {
        //获取仓库信息
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在!');
        }
        $warehouse = $warehouse->toArray();
        $quantity = (intval($orderType) === 1) ? ['expect_pickup_quantity' => 1] : ['expect_pie_quantity' => 1];
        $tour = parent::create(
            array_merge([
                'tour_no' => $this->getOrderNoRuleService()->createTourNo(),
                'line_id' => $line['id'],
                'line_name' => $line['name'],
                'execution_date' => $batch['execution_date'],
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['name'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_post_code' => $warehouse['post_code'],
                'warehouse_city' => $warehouse['city'],
                'warehouse_street' => $warehouse['street'],
                'warehouse_house_number' => $warehouse['house_number'],
                'warehouse_address' => $warehouse['address'],
                'warehouse_lon' => $warehouse['lon'],
                'warehouse_lat' => $warehouse['lat']
            ], $quantity)
        );
        if ($tour === false) {
            throw new BusinessLogicException('站点加入取件线路失败,请重新操作!');
        }
        return $tour->getOriginal();
    }


    /**
     * 加入已存在取件线路
     * @param $tour
     * @param $orderType
     * @return mixed
     * @throws BusinessLogicException
     */
    public function joinExistTour($tour, $orderType)
    {
        $data = (intval($orderType) === 1) ? ['expect_pickup_quantity' => intval($tour['expect_pickup_quantity']) + 1] : ['expect_pie_quantity' => intval($tour['expect_pie_quantity']) + 1];
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败,请重新操作!');
        }
        return $tour;
    }

    /**
     * 通过订单,修改订单相关数据
     * @param $dbOrder
     * @param $order
     * @param $data
     * @throws BusinessLogicException
     */
    public function updateAboutOrderByOrder($dbOrder, $order)
    {
        $info = parent::getInfoLock(['tour_no' => $dbOrder['tour_no']], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        if (intval($info['status']) !== BaseConstService::TOUR_STATUS_1) {
            throw new BusinessLogicException('当前取件线路状态不能更新订单相关信息');
        }
        //若订单类型改变,则站点统计数量改变
        $data = [];
        if (intval($dbOrder['type']) !== intval($order['type'])) {
            if (intval($order['type']) === BaseConstService::ORDER_TYPE_1) {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] + 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] - 1;
            } else {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] - 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] + 1;
            }
        }
        //代收款费用
        $diffReplaceAmount = $order['replace_amount'] - $dbOrder['replace_amount'];
        $diffSettlementAmount = $order['settlement_amount'] - $dbOrder['settlement_amount'];
        $rowCount = parent::updateById($info['id'], array_merge($data, [
            'replace_amount' => $info['replace_amount'] + $diffReplaceAmount,
            'settlement_amount' => $info['settlement_amount'] + $diffSettlementAmount
        ]));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
    }


    /**
     * 移除站点订单
     * @param $order
     * @param $batch
     * @throws BusinessLogicException
     */
    public function removeBatchOrder($order, $batch)
    {
        $info = parent::getInfoLock(['tour_no' => $order['tour_no']], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        if (intval($info['status']) !== BaseConstService::TOUR_STATUS_1) {
            throw new BusinessLogicException('当前取件线路状态不能移除订单');
        }
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他订单时,删除站点;若还存在其他订单,则只移除订单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $data['settlement_amount'] = $info['settlement_amount'] - $order['settlement_amount'];
            $data['replace_amount'] = $info['replace_amount'] - $order['replace_amount'];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('取件移除订单失败,请重新操作');
        }
    }


    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
     */
    public function getNextBatchAndUpdateIndex($batchIds): Batch
    {
        $first = false;
        foreach ($batchIds as $key => $batchId) {
            $tempbatch = Batch::where('id', $batchId)->first();
            if (!$first && in_array($tempbatch->status, [
                BaseConstService::BATCH_WAIT_ASSIGN,
                BaseConstService::BATCH_WAIT_OUT,
                BaseConstService::BATCH_DELIVERING,
                BaseConstService::BATCH_ASSIGNED
            ])) {
                if ($tempbatch) {
                    $batch = $tempbatch;
                    $first = true; // 找到了下一个目的地
                }
            }
            $tempbatch->update(['sort_id' => $key + 1]);
        }
        if ($batch ?? null) {
            return $batch;
        }

        throw new BusinessLogicException('未查找到下一个目的地');
    }

    /**
     * 更新批次配送顺序
     */
    public function updateBatchIndex()
    {
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        // set_time_limit(240);
        self::setTourLock($this->formData['tour_no'], 1); // 加锁

        app('log')->info('更新线路传入的参数为:', $this->formData);

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();

        throw_if(
            $tour->batchs->count() != count($this->formData['batch_ids']),
            new BusinessLogicException('线路')
        );

        //此处的所有 batchids 应该经过验证!
        $nextBatch = $this->getNextBatchAndUpdateIndex($this->formData['batch_ids']);

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        //0.5s执行一次
        //执行 120s
        $index = 0;
        while ($index++ != 240) {
            time_nanosleep(0, 500000000);
            app('log')->info('每 0.5 秒查询一次修改是否完成');
            //锁不存在代表更新完成
            if (!self::getTourLock($tour->tour_no)) {
                return '修改线路成功';
            }
        }
        app('log')->error('进入此处代表修改线路失败');
        // self::setTourLock($this->formData['tour_no'], 0); // 取消锁 -- 放在中间件中
        throw new BusinessLogicException('修改线路失败');
    }

    /**
     * 自动优化线路任务
     */
    public function autoOpTour()
    {
        //需要先判断当前 tour 是否被锁定状态!!! 中间件或者 validate 验证规则???
        // set_time_limit(240);
        self::setTourLock($this->formData['tour_no'], 1); // 加锁

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();
        $nextBatch = $this->autoOpIndex($tour); // 自动优化排序值并获取下一个目的地

        if (!$nextBatch) {
            // self::setTourLock($this->formData['tour_no'], 0);
            throw new BusinessLogicException('没有找到下一个目的地');
        }

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        //0.5s执行一次
        $index = 0;
        while ($index++ != 240) {
            time_nanosleep(0, 500000000);
            app('log')->info('每 0.5 秒查询一次修改是否完成');
            //锁不存在代表更新完成
            if (!$this->getTourLock($tour->tour_no)) {
                return '修改线路成功';
            }
        }
        app('log')->error('进入此处代表修改线路失败');
        // self::setTourLock($this->formData['tour_no'], 0); // 取消锁 -- 放在中间件中
        throw new BusinessLogicException('修改线路失败');
    }

    public function autoOpIndex(Tour $tour)
    {
        $key = 1;
        if ($tour->status != BaseConstService::TOUR_STATUS_4) { // 未取派的情况下,所有 batch 都是未取派的
            $batchs = $tour->batchs;
        } else {
            $preBatchs = $tour->batchs()->where('status', '<>', BaseConstService::BATCH_DELIVERING)->get()->sortByDesc('sort_id'); // 已完成的包裹排序值放前面并不影响 -- 此处不包括未开始的
            foreach ($preBatchs as $preBatch) {
                $preBatch->update(['sort_id' => $key]);
                $key++;
            }
            $batchs = $tour->batchs()->where('status', BaseConstService::BATCH_DELIVERING)->get();
        }

        $batchNos = [];

        //必须大于等于 2 才调用优化接口
        if (count($batchs) >= 2) {
            $driverLoc = [
                'batch_no' => 'driver_location',
                'receiver_lat' => $tour->driver_location['latitude'],
                'receiver_lon' => $tour->driver_location['longitude'],
            ];
            app('log')->debug('查看当前 batch 总数为:' . count($batchs) . '当前的 batch 为:', $batchs->toArray());
            app('log')->debug('整合后的数据为:', array_merge([$driverLoc], $batchs->toArray()));

            $batchNos = $this->directionClient->GetRoute(array_merge([$driverLoc], $batchs->toArray()));

            throw_unless(
                $batchNos,
                new BusinessLogicException('优化线路失败')
            );
        }

        $nextBatch = null;

        app('log')->info('当前返回的值为:' . json_encode($batchNos));

        foreach ($batchNos as $k => $batchNo) {
            Batch::where('batch_no', $batchNo)->update(['sort_id' => $key + $k]);
            if (!$nextBatch) {
                $nextBatch = Batch::where('batch_no', $batchNo)->first();
            }
        }

        return $nextBatch;
    }

    /**
     * 处理计算时间和距离的回调
     */
    public function dealCallback()
    {
        throw_unless(
            self::getTourLock($this->formData['line_code']) == 1,
            new BusinessLogicException('不存在的动作')
        );

        $tourLog = TourLog::where('tour_no', $this->formData['line_code'])->where('status', BaseConstService::TOUR_LOG_PENDING)->where('action', $this->formData['type'])->first();
        // app('log')->info('日志的时间戳为:' . $lineLog->timestamp . '当天开始的时间戳为:' . strtotime(date("Y-m-d")));
        if (time() - $tourLog->created_at->timestamp > 3600 * 24 || $tourLog->created_at->timestamp < strtotime(date("Y-m-d"))) { // 标记为异常日志
            app('log')->info('异常的线路日志为:' . $this->formData['line_code']);
            $tourLog->update(['status' => BaseConstService::TOUR_LOG_ERROR]);
            throw new BusinessLogicException('更新时间已超时');
        }

        $info = $this->apiClient->LineInfo($this->formData['line_code']);
        if (!$info || $info['ret'] == 0) { // 返回错误的情况下直接返回
            app('log')->info('更新动作失败,错误信息为:' . $info['msg']);
            self::setTourLock($this->formData['line_code'], 0);
            return '已知道该次更新失败';
        }
        $data = $info['data'];

        app('log')->info('开始更新线路,线路标识为:' . $this->formData['line_code']);
        app('log')->info('api返回的结果为:', $info);

        TourLog::where('tour_no', $this->formData['line_code'])->where('action', $this->formData['type'])->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
        $tour = Tour::where('tour_no', $this->formData['line_code'])->first();
        $max_time = 0;
        $max_distance = 0;

        foreach ($data['loc_res'] as $key => $res) {
            $tourBatch = Batch::where('batch_no', str_replace($this->formData['line_code'], '', $key))->where('tour_no', $this->formData['line_code'])->first();
            $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', $data['timestamp'] + $res['time']);
            $tourBatch->expect_distance = $res['distance'];
            $tourBatch->save();
            $max_time = max($max_time, $res['time']);
            $max_distance = max($max_distance, $res['distance']);
        }

        if ($tour->expect_time == 0) { // 只有未更新过的线路需要更新期望时间和距离
            $tour->expect_time = $max_time;
            $tour->expect_distance = $max_distance;
            $tour->save();
        }
        $tour->lave_distance = $max_distance;

        app('log')->info('更新线路完成,线路标识为:' . $this->formData['line_code']);
        //取消锁
        self::setTourLock($this->formData['line_code'], 0);
        return '更新完成';
    }

    public function getBatchCountInfo($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        return $info;
    }


    public function getUploadService(){
        return self::getInstance(UploadService::class);

    }

    public function export($tour_no,$data)
    {
        $params['dir'] ='tour';
        $params['name'] =$tour_no;
        $params['excel'] =$data;
        return $this->getUploadService()->excelUpload($params);
    }

    /**
     * 导出站点表格
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function batchExcel($id){
        //取出数据
        $cellData=[];
        $tour_no =$this->query->where('id','=',$id)->value('tour_no');
        if(empty($tour_no)){
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no'=>$tour_no],
            ['receiver','receiver_phone','receiver_post_code','receiver_street','receiver_house_number','receiver_city','expect_pickup_quantity','expect_pie_quantity','batch_no'],
            false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();

        //整理结构
        for($i=1;$i<=count($info);$i++) {
            $cellData[$i][0] = $i;
            $cellData[$i][1] = $info[$i-1]['receiver'];
            $cellData[$i][2] = $info[$i-1]['receiver_phone'];
            $cellData[$i][3] = $this->getOrderService()->getInfo(['batch_no'=>$info[$i-1]['batch_no']],['*'],false)['out_user_id']??'';
            $cellData[$i][4] = $info[$i-1]['receiver_street'].' '.$info[$i-1]['receiver_house_number'];
            $cellData[$i][5] = $info[$i-1]['receiver_post_code'];
            $cellData[$i][6] = $info[$i-1]['receiver_city'];
            $cellData[$i][7] = $this->getOrderService()->getInfo(['batch_no'=>$info[$i-1]['batch_no']],['*'],false)['source'];;
            $cellData[$i][8] = $info[$i-1]['expect_pickup_quantity'];
            $cellData[$i][9] = $info[$i-1]['expect_pie_quantity'];
            $cellData[$i][10] = $this->getOrderService()->getList(['batch_no'=>$info[$i-1]['batch_no']],['*'],false)[0]['express_first_no'];
            $cellData[$i][11] = $this->getOrderService()->getList(['batch_no'=>$info[$i-1]['batch_no']],['*'],false)[1]['express_first_no']??'';
        }
        $cellData[0] = array('No','Name','Phone','Acc','Address','Postcode','City','SYS','取件数量','派件数量','Barcode 1','Barcode 2');
        for($i=0;$i<count($cellData);$i++){
            $cellData[$i] = array_values($cellData[$i]);
        }
        $cellData =array_reverse($cellData);
        return $this->export($tour_no,$cellData);
    }

    /**
     * 导出城市线路
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function cityTxt($id)
    {
        $tourInfo =$this->getInfo(['id'=>$id],['*'],false);
        if(empty($tourInfo)){
            throw new BusinessLogicException('数据不存在');
        }
/*        if($tourInfo['status'] < BaseConstService::ORDER_STATUS_1){
            throw new BusinessLogicException('当前状态无法导出取派城市');
        }*/
        $info = $this->getBatchService()->getList(['tour_no'=>$tourInfo['tour_no']],['receiver_city'],false,[],['sort_id' => 'asc'])->toArray();
        $cityList ='';
       for($i=0;$i<count($info);$i++){
           $cityList = $cityList.$info[$i]['receiver_city'].'-';
       }
       $cityList =rtrim($cityList, "-");
       $params['txt']=$tourInfo['line_name'].' '.$tourInfo['driver_name'].':'.$tourInfo['driver_phone'].' '.$cityList;
       $params['name']=$tourInfo['tour_no'];
       $params['dir']='tour';
       return $this->getUploadService()->txtUpload($params);
    }
}
