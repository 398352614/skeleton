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
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use App\Traits\LocationTrait;
use Doctrine\DBAL\Driver\OCI8\Driver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\OrderTrailService;
use App\Services\Traits\TourRedisLockTrait;

class TourService extends BaseService
{
    use TourRedisLockTrait, ExportTrait;

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
        'line_name' => ['like', 'line_name'],
        'tour_no' => ['like', 'tour_no'],
        'driver_name' => ['like', 'driver_name'],
        'line_name,driver_name' => ['like', 'key_word'],
    ];

    protected $headings = [
        'id',
        'receiver_fullname',
        'receiver_phone',
        'out_user_id',
        'receiver_address',
        'receiver_post_code',
        'receiver_city',
        'merchant',
        'expect_pickup_quantity',
        'expect_pie_quantity',
        'express_first_no_one',
        'express_first_no_two',
    ];

    protected $tourHeadings = [
        'tour_no',
        'line_name',
        'driver_name',
        'execution_date',
        'expect_pie_package_quantity',
        'actual_pie_package_quantity',
        'expect_pickup_package_quantity',
        'actual_pickup_package_quantity',
        'expect_material_quantity',
        'actual_material_quantity',
        'receiver_fullname',
        'receiver_phone',
        'receiver_post_code',
        'receiver_address',
        'expect_pie_quantity',
        'actual_pie_quantity',
        'expect_pickup_quantity',
        'actual_pickup_quantity',
        'expect_pie_package_quantity',
        'actual_pie_package_quantity',
        'expect_pickup_package_quantity',
        'actual_pickup_package_quantity',
        'expect_material_quantity',
        'actual_material_quantity',
        'status',
        'actual_arrive_time'
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Tour $tour, GoogleApiService $client, XLDirectionService $directionClient)
    {
        parent::__construct($tour, TourResource::class, TourInfoResource::class);
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
     * 包裹 服务
     * @return PackageService
     */
    private function getPackageService()
    {
        return self::getInstance(PackageService::class);
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

    /**
     * 材料服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);

    }

    /**
     * 获取可加单的取件线路列表
     * @param $orderIdList
     * @return array|mixed
     * @throws BusinessLogicException
     */
    public function getAddOrderPageList($orderIdList)
    {
        $this->filters['status'] = ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]];
        list($orderList, $lineId) = $this->getOrderService()->getAddOrderList($orderIdList);
        $this->filters['line_id'] = ['=', $lineId];
        $list = parent::getPageList();
        return $list;
    }

    public function getPageList()
    {
        if (isset($this->filters['status'][1]) && (intval($this->filters['status'][1]) == 0)) {
            unset($this->filters['status']);
        }
        if(!empty($this->formData['sort_by_time']) && $this->formData['sort_by_time'] == BaseConstService::SORT_BY_TIME_2){
            $this->query->orderBy('begin_time','desc');
        }else{
            $this->query->orderBy('begin_time');
        }
        return parent::getPageList();
    }

    /**
     * 分配司机
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignDriver($id, $params)
    {
        $tour = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::TOUR_STATUS_1, false);
        //查看当前司机是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['driver_id' => $params['driver_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前司机已被分配，请选择其他司机');
        }
        //获取司机
        $driver = $this->getDriverService()->getInfo(['id' => $params['driver_id'], 'is_locked' => BaseConstService::DRIVER_TO_NORMAL], ['*'], false);
        if (empty($driver)) {
            throw new BusinessLogicException('司机不存在或已被锁定');
        }
        $driver = $driver->toArray();
        //取件线路分配 由于取件线路,站点,订单的已分配状态都为2,所以只需取一个状态即可(ORDER_STATUS_2,BATCH_ASSIGNED,TOUR_STATUS_2)
        $rowCount = $this->assignOrCancelAssignAll($tour, ['driver_id' => $driver['id'], 'driver_name' => $driver['fullname'], 'driver_phone' => $driver['phone'], 'status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机分配失败，请重新操作');
        }
        $tour['driver_id'] = $driver['id'];
        $tour['driver_name'] = $driver['fullname'];
        $tour['driver_phone'] = $driver['phone'];
        OrderTrailService::storeByTour($tour, BaseConstService::ORDER_TRAIL_ASSIGN_DRIVER);
    }

    /**
     * 取消司机分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancelAssignDriver($id)
    {
        $tour = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::TOUR_STATUS_2, true);
        $rowCount = $this->assignOrCancelAssignAll($tour, ['driver_id' => null, 'driver_name' => null, 'status' => BaseConstService::ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机取消分配失败，请重新操作');
        }
        OrderTrailService::storeByTour($tour, BaseConstService::ORDER_TRAIL_CANCEL_ASSIGN_DRIVER);
    }


    /**
     * 分配车辆
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignCar($id, $params)
    {
        $tour = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3], false);
        //查看当前车辆是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['car_id' => $params['car_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前车辆已被分配，请选择其他车辆');
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
            throw new BusinessLogicException('车辆分配失败，请重新操作');
        }
    }

    /**
     * 取消车辆分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancelAssignCar($id)
    {
        $tour = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2], false);
        $rowCount = $this->assignOrCancelAssignAll($tour, ['car_id' => null, 'car_no' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆取消分配失败，请重新操作');
        }
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
        //包裹
        $rowCount = $this->getPackageService()->update(['tour_no' => $tour['tour_no']], $data);
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
        $tour = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::TOUR_STATUS_3, false);
        //取件线路 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取件线路取消锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败，请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_3], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单取消锁定失败，请重新操作');
        }
        //包裹 处理
        $rowCount = $this->getPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::PACKAGE_STATUS_3], ['status' => BaseConstService::PACKAGE_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆取消分配失败，请重新操作');
        }
        OrderTrailService::storeByTour($tour, BaseConstService::ORDER_TRAIL_UN_LOCK);
    }


    /**
     * 站点加入取件线路
     * @param $batch
     * @param $line
     * @param $order
     * @param $tour
     * @return BaseService|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function join($batch, $line, $order, $tour = [])
    {
        $tour = !empty($tour) ? $tour : $this->getTourInfo($batch, $line);
        //加入取件线路
        $quantity = (intval($order['type']) === BaseConstService::ORDER_TYPE_1) ? ['expect_pickup_quantity' => 1] : ['expect_pie_quantity' => 1];
        $tour = !empty($tour) ? $this->joinExistTour($tour, $quantity) : $this->joinNewTour($batch, $line, $quantity);
        return $tour;
    }

    /**
     * 加入新的取件线路
     * @param $line
     * @param $batch
     * @param $quantity
     * @return BaseService|array|\Illuminate\Database\Eloquent\Model|mixed
     * @throws BusinessLogicException
     */
    private function joinNewTour($batch, $line, $quantity)
    {
        //获取仓库信息
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在！');
        }
        $warehouse = $warehouse->toArray();
        $tourNo = $this->getOrderNoRuleService()->createTourNo();
        $tour = parent::create(
            array_merge([
                'tour_no' => $tourNo,
                'line_id' => $line['id'],
                'line_name' => $line['name'],
                'execution_date' => $batch['execution_date'],
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['fullname'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_country' => $warehouse['country'],
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
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作！');
        }
        return $tour->getOriginal();
    }


    /**
     * 加入已存在取件线路
     * @param $tour
     * @param $quantity
     * @param $amount
     * @return mixed
     * @throws BusinessLogicException
     */
    public function joinExistTour($tour, $quantity)
    {
        $data = [
            'expect_pickup_quantity' => !empty($quantity['expect_pickup_quantity']) ? $tour['expect_pickup_quantity'] + $quantity['expect_pickup_quantity'] : $tour['expect_pickup_quantity'],
            'expect_pie_quantity' => !empty($quantity['expect_pie_quantity']) ? $tour['expect_pie_quantity'] + $quantity['expect_pie_quantity'] : $tour['expect_pie_quantity'],
        ];
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作！');
        }
        $tour = array_merge($tour, $data);
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
        $info = $this->getInfoOfStatus(['tour_no' => $dbOrder['tour_no']], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2], true);
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
        $rowCount = parent::updateById($info['id'], $data);
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
        $info = $this->getInfoOfStatus(['tour_no' => $order['tour_no']], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3], true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他订单时,删除站点;若还存在其他订单,则只移除订单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('取件移除订单失败，请重新操作');
        }
    }

    /**
     * 移除站点
     * @param $batch
     * @throws BusinessLogicException
     */
    public function removeBatch($batch)
    {
        $info = $this->getInfoOfStatus(['tour_no' => $batch['tour_no']], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2], true);
        $quantity = intval($info['expect_pickup_quantity']) + intval($info['expect_pie_quantity']);
        $batchQuantity = intval($batch['expect_pickup_quantity']) + intval($batch['expect_pie_quantity']);
        //当站点中不存在其他订单时,删除站点;若还存在其他订单,则只移除订单
        if ($quantity - $batchQuantity <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - $batch['expect_pickup_quantity'], 'expect_pie_quantity' => $info['expect_pie_quantity'] - $batch['expect_pie_quantity']];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('取件移除站点失败，请重新操作');
        }
    }

    /**
     * 通过站点,获取可分配的取件线路
     * @param $batch
     * @param $line
     * @return array
     */
    public function getListByBatch($batch, $line)
    {
        $data = [];
        $tour = $this->getList(['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]], ['*'], false)->toArray();
        if (!empty($tour) && !empty($line)) {
            //当日截止时间验证
            for ($i = 0, $j = count($tour); $i < $j; $i++) {
                if ((date('Y-m-d') == $batch['execution_date'] && time() < strtotime($batch['execution_date'] . ' ' . $line['order_deadline']) ||
                    date('Y-m-d') !== $batch['execution_date'])) {
                    //取件订单，线路最大订单量验证
                    if ($batch['status'] = BaseConstService::ORDER_TYPE_1 && $tour[$i]['expect_pickup_quantity'] + $batch['expect_pickup_quantity'] < $line['pickup_max_count']) {
                        $data[$i] = $tour[$i];
                    }
                    //派件订单，线路最大订单量验证
                    if ($batch['status'] = BaseConstService::ORDER_TYPE_2 && $tour[$i]['expect_pie_quantity'] + $batch['expect_pie_quantity'] < $line['pie_max_count']) {
                        $data[$i] = $tour[$i];
                    }
                }
            }
        }
        return $data;
    }


    /**
     * 分配站点至取件线路
     * @param $batch
     * @param $line
     * @param $params
     * @return BaseService|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws BusinessLogicException
     */
    public function assignBatchToTour($batch, $line, $params)
    {
        if (!empty($batch['tour_no'])) {
            $this->removeBatch($batch);
        }
        $tour = $this->getTourInfo($batch, $line, true, $params['tour_no'] ?? '');
        if (!empty($params['tour_no']) && empty($tour)) {
            throw new BusinessLogicException('当前指定取件线路不符合当前站点');
        }
        $quantity = ['expect_pickup_quantity' => $batch['expect_pickup_quantity'], 'expect_pie_quantity' => $batch['expect_pie_quantity']];
        //若存在取件线路，判断当前取件线路中是否已存在相同站点,若存在，则合并
        if (!empty($tour)) {
            $batch = $this->getBatchService()->mergeTwoBatch($tour, $batch);
            $tour = $this->joinExistTour($tour, $quantity);
        } else {
            $tour = $this->joinNewTour($batch, $line, $quantity);
        }
        return [$tour, $batch];
    }

    /**
     * 获取取件线路信息
     * @param $batch
     * @param $line
     * @param $isLock
     * @param $tourNo
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTourInfo($batch, $line, $isLock = true, $tourNo = null)
    {
        if (!empty($tourNo)) {
            $this->query->where('tour_no', '=', $tourNo);
        }
        //若不存在取件线路或者超过最大订单量,则新建取件线路
        if (intval($batch['expect_pickup_quantity']) > 0) {
            $this->query->where(DB::raw('expect_pickup_quantity+' . intval($batch['expect_pickup_quantity'])), '<=', $line['pickup_max_count']);
        }
        if (intval($batch['expect_pie_quantity']) > 0) {
            $this->query->where(DB::raw('expect_pie_quantity+' . intval($batch['expect_pie_quantity'])), '<=', $line['pie_max_count']);
        }
        $where = ['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]];
        $tour = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        return !empty($tour) ? $tour->toArray() : [];
    }

    /**
     * 重新统计金额
     * @param $tourNo
     * @throws BusinessLogicException
     */
    public function reCountAmountByNo($tourNo)
    {
        $totalReplaceAmount = $this->getBatchService()->sum('replace_amount', ['tour_no' => $tourNo]);
        $totalSettlementAmount = $this->getBatchService()->sum('settlement_amount', ['tour_no' => $tourNo]);
        $rowCount = parent::update(['tour_no' => $tourNo], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }


    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
     * @throws BusinessLogicException
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
    public function updateBatchIndex($data)
    {
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        // set_time_limit(240);

        app('log')->info('更新线路传入的参数为:', $data);

        $tour = Tour::where('tour_no', $data['tour_no'])->firstOrFail();

        throw_if(
            $tour->batchs->count() != count($data['batch_ids']),
            new BusinessLogicException('线路的站点数量不正确')
        );

        //此处的所有 batchids 应该经过验证!
        $nextBatch = $this->getNextBatchAndUpdateIndex($data['batch_ids']);

        TourLog::create([
            'tour_no' => $data['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);
        event(new AfterTourUpdated($tour, $nextBatch->batch_no));
        return '修改线路成功';
    }

    /**
     * 自动优化线路任务
     * @param $data
     * @return string
     */
    public function autoOpTour($data)
    {
        //需要先判断当前 tour 是否被锁定状态!!! 中间件或者 validate 验证规则???
        // set_time_limit(240);

        $tour = Tour::where('tour_no', $data['tour_no'])->firstOrFail();
        $nextBatch = $this->autoOpIndex($tour); // 自动优化排序值并获取下一个目的地
        if (!$nextBatch) {
            $nextBatch = Batch::where('tour_no', $data['tour_no'])->first();
            // self::setTourLock($this->formData['tour_no'], 0);
        }

        TourLog::create([
            'tour_no' => $data['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);
        event(new AfterTourUpdated($tour, $nextBatch->batch_no));
        return '修改线路成功';
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
            self::setTourLock($this->formData['line_code'], 0);
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
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info['batchs'] = collect($info['batchs'])->sortBy('sort_id')->all();
        foreach ($info['batchs'] as $k => $v) {
            $info['batchs'][$k]['sort_id'] = $k + 1;
        }
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        return $info;
    }


    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    protected function getRelativeUrl(string $url): string
    {
        return str_replace(config('app.url'), '', $url);
    }

    /**
     * 导出站点表格
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function batchExport($id)
    {
        //取出数据
        $cellData = [];
        $tour_no = $this->query->where('id', '=', $id)->value('tour_no');
        if (empty($tour_no)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tour_no], ['*'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        //整理结构
        for ($i = 0; $i < count($info); $i++) {
            $orderInfo = $this->getOrderService()->getList(['batch_no' => $info[$i]['batch_no']], ['*'], false);
            if (empty($orderInfo)) {
                throw new BusinessLogicException('数据不存在');
            }
            $packageInfo = $this->getPackageService()->getList(['order_no' => $orderInfo[0]['order_no']], ['*'], false);
            if (empty($packageInfo)) {
                throw new BusinessLogicException('数据不存在');
            }
            $cellData[$i][0] = $i + 1;
            $cellData[$i][1] = $info[$i]['receiver_fullname'];
            $cellData[$i][2] = $info[$i]['receiver_phone'];
            $cellData[$i][3] = $orderInfo[0]['out_user_id'] ?? '';
            $cellData[$i][4] = $info[$i]['receiver_street'] . ' ' . $info[$i]['receiver_house_number'];
            $cellData[$i][5] = $info[$i]['receiver_post_code'];
            $cellData[$i][6] = $info[$i]['receiver_city'];
            $cellData[$i][7] = $orderInfo[0]['merchant_id_name'];
            $cellData[$i][8] = $info[$i]['expect_pickup_quantity'];
            $cellData[$i][9] = $info[$i]['expect_pie_quantity'];
            $cellData[$i][10] = $packageInfo[0]['express_first_no'] ?? "";
            $cellData[$i][11] = $packageInfo[1]['express_first_no'] ?? "";
        }
        for ($i = 0; $i < count($cellData); $i++) {
            $cellData[$i] = array_values($cellData[$i]);
        }
        $cellData = array_reverse($cellData);
        $dir = 'batchList';
        $name = date('Ymd') . $tour_no . auth()->user()->company_id;
        return $this->excelExport($name, $this->headings, $cellData, $dir);
    }

    /**
     * 导出城市线路
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function cityExport($id)
    {
        $tourInfo = $this->getInfo(['id' => $id], ['*'], false);
        if (empty($tourInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tourInfo['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $cityList = '';
        for ($i = 0; $i < count($info); $i++) {
            $cityList = $cityList . $info[$i]['receiver_city'] . '-';
        }
        $cityList = rtrim($cityList, "-");
        $params['name'] = $tourInfo['tour_no'];
        $params['txt'] = $tourInfo['line_name'] . ' ' . $tourInfo['driver_name'] . ':' . $tourInfo['driver_phone'] . ' ' . $cityList;
        $params['dir'] = 'tour';
        //return $this->txtExport($params['name'],$params['txt'],$params['dir']);
        return $params;
    }

    /**
     * 导出站点地图
     * @param $id
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function mapExport($id)
    {
        $tourInfo = $this->getInfo(['id' => $id], ['*'], false);
        if (empty($tourInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tourInfo['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $params[0]['lon'] = $tourInfo['warehouse_lon'];
        $params[0]['lat'] = $tourInfo['warehouse_lat'];
        for ($i = 1; $i <= count($info); $i++) {
            $params[$i]['lon'] = $info[$i - 1]['receiver_lon'];
            $params[$i]['lat'] = $info[$i - 1]['receiver_lat'];
        }
        $name = $tourInfo['tour_no'];
        return LocationTrait::getBatchMap($params, $name);
    }

    /**
     * 统计订单数量
     *
     * @param $info
     * @param $line
     * @param int $type 1-取件2-派件3-取件和派件
     * @return array
     */
    public function sumOrderCount($info, $line, $type = 1)
    {
        $arrCount = [];
        if ($type === 1) {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        } elseif ($type === 2) {
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        } else {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date']]);
        }
        return $arrCount;
    }

    /**
     * 导出取件线路
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function tourExport($id)
    {
        //取出数据
        $cellData = [];
        $statusToMerchantStatus = [
            BaseConstService::BATCH_WAIT_ASSIGN => BaseConstService::MERCHANT_BATCH_STATUS_1,
            BaseConstService::BATCH_ASSIGNED => BaseConstService::MERCHANT_BATCH_STATUS_1,
            BaseConstService::BATCH_WAIT_OUT => BaseConstService::MERCHANT_BATCH_STATUS_1,
            BaseConstService::BATCH_DELIVERING => BaseConstService::MERCHANT_BATCH_STATUS_2,
            BaseConstService::BATCH_CHECKOUT => BaseConstService::MERCHANT_BATCH_STATUS_3,
            BaseConstService::BATCH_CANCEL => BaseConstService::MERCHANT_BATCH_STATUS_4
        ];

        $tour = $this->query->where('id', '=', $id)->first();
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour['expect_pie_package_quantity'] = 0;
        $tour['actual_pie_package_quantity'] = 0;
        $tour['expect_pickup_package_quantity'];
        $tour['actual_pickup_package_quantity'] = 0;
        $tour['expect_material_quantity'] = 0;
        $tour['actual_material_quantity'] = 0;

        $packageList = $this->getPackageService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        if (empty($packageList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        if (empty($batchList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $orderList = $this->getOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        if (empty($orderList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $materialList = $this->getMaterialService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        for ($i = 0; $i < count($batchList); $i++) {
            $batchList[$i]['expect_pie_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::ORDER_TYPE_2)->where('batch_no', $batchList[$i]['batch_no'])->all());
            $batchList[$i]['actual_pie_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::ORDER_TYPE_2)->where('batch_no', $batchList[$i]['batch_no'])->where('status', BaseConstService::PACKAGE_STATUS_5)->all());
            $batchList[$i]['expect_pickup_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::ORDER_TYPE_1)->where('batch_no', $batchList[$i]['batch_no'])->all());
            $batchList[$i]['actual_pickup_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::ORDER_TYPE_1)->where('batch_no', $batchList[$i]['batch_no'])->where('status', BaseConstService::PACKAGE_STATUS_5)->all());
            $batchList[$i]['expect_material_quantity'] = collect($materialList)->where('batch_no', $batchList[$i]['batch_no'])->pluck('expect_quantity')->sum();
            $batchList[$i]['actual_material_quantity'] = collect($materialList)->where('batch_no', $batchList[$i]['batch_no'])->pluck('actual_quantity')->sum();
            $batchList[$i]['status'] = __(ConstTranslateTrait::merchantBatchStatusList($statusToMerchantStatus[$batchList[$i]['status']]));
            $tour['expect_pie_package_quantity'] += $batchList[$i]['expect_pie_package_quantity'];
            $tour['actual_pie_package_quantity'] += $batchList[$i]['actual_pie_package_quantity'];
            $tour['expect_pickup_package_quantity'] += $batchList[$i]['expect_pickup_package_quantity'];
            $tour['actual_pickup_package_quantity'] += $batchList[$i]['actual_pickup_package_quantity'];
            $tour['expect_material_quantity'] += $batchList[$i]['expect_material_quantity'];
            $tour['actual_material_quantity'] += $batchList[$i]['actual_material_quantity'];
        }

        for ($i = 0; $i < count($batchList); $i++) {
            $cellData[$i][0] = '';
            $cellData[$i][1] = '';
            $cellData[$i][2] = '';
            $cellData[$i][3] = '';
            $cellData[$i][4] = '';
            $cellData[$i][5] = '';
            $cellData[$i][6] = '';
            $cellData[$i][7] = '';
            $cellData[$i][8] = '';
            $cellData[$i][9] = '';
            $cellData[$i][10] = $batchList[$i]['receiver_fullname'];
            $cellData[$i][11] = $batchList[$i]['receiver_phone'];
            $cellData[$i][12] = $batchList[$i]['receiver_post_code'];
            $cellData[$i][13] = $batchList[$i]['receiver_address'];
            $cellData[$i][14] = $batchList[$i]['expect_pie_quantity'];
            $cellData[$i][15] = $batchList[$i]['actual_pie_quantity'];
            $cellData[$i][16] = $batchList[$i]['expect_pickup_quantity'];
            $cellData[$i][17] = $batchList[$i]['actual_pickup_quantity'];
            $cellData[$i][18] = $batchList[$i]['expect_pie_package_quantity'];
            $cellData[$i][19] = $batchList[$i]['actual_pie_package_quantity'];
            $cellData[$i][20] = $batchList[$i]['expect_pickup_package_quantity'];
            $cellData[$i][21] = $batchList[$i]['actual_pickup_package_quantity'];
            $cellData[$i][22] = $batchList[$i]['expect_material_quantity'];
            $cellData[$i][23] = $batchList[$i]['actual_material_quantity'];
            $cellData[$i][24] = $batchList[$i]['status'];
            $cellData[$i][25] = $batchList[$i]['actual_arrive_time'];
        }
        $cellData[0][0] = $tour['tour_no'];
        $cellData[0][1] = $tour['line_name'];
        $cellData[0][2] = $tour['driver_name'];
        $cellData[0][3] = $tour['execution_date'];
        $cellData[0][4] = $tour['expect_pie_package_quantity'];
        $cellData[0][5] = $tour['actual_pie_package_quantity'];
        $cellData[0][6] = $tour['expect_pickup_package_quantity'];
        $cellData[0][7] = $tour['actual_pickup_package_quantity'];
        $cellData[0][8] = $tour['expect_material_quantity'];
        $cellData[0][9] = $tour['actual_material_quantity'];

        for ($i = 0; $i < count($cellData); $i++) {
            $cellData[$i] = array_values($cellData[$i]);
        }
        $dir = 'tour';
        $name = date('Ymd') . $tour['tour_no'] . auth()->user()->id;
        return $this->excelExport($name, $this->tourHeadings, $cellData, $dir);
    }
}
