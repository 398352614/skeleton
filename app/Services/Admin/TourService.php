<?php

namespace App\Services\Admin;

use App\Events\AfterTourInit;
use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourResource;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\GoogleApiService;
use App\Services\OrderNoRuleService;
use Illuminate\Support\Facades\DB;

class TourService extends BaseService
{
    /**
     * @var GoogleApiService
     */
    public $apiClient;

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword']
    ];

    public function __construct(Tour $tour, GoogleApiService $client)
    {
        $this->model = $tour;
        $this->query = $this->model::query();
        $this->resource = TourResource::class;
        $this->infoResource = TourResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
        $this->apiClient = $client;
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
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        //查看当前司机是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'driver_id' => $params['driver_id'], 'execution_date' => $tour['execution_date']], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前司机已被分配,请选择其他司机');
        }
        //获取司机
        $driver = $this->getDriverService()->getInfo(['id' => $params['driver_id'], 'is_locked' => BaseConstService::DRIVER_TO_NORMAL], ['*'], false);
        if (empty($driver)) {
            throw new BusinessLogicException('司机不存在或已被锁定');
        }
        //分配
        $driver = $driver->toArray();
        $rowCount = parent::updateById($tour['id'], ['driver_id' => $driver['id'], 'driver_name' => $driver['last_name'] . $driver['first_name']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('分配司机失败,请重新操作');
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
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        //查看当前司机是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'car_id' => $params['car_id'], 'execution_date' => $tour['execution_date']], ['*'], false);
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
        $rowCount = parent::updateById($tour['id'], ['car_id' => $car['id'], 'car_no' => $car['car_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('分配车辆失败,请重新操作');
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
        $tour = parent::getInfoLock(['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => BaseConstService::TOUR_STATUS_1], ['*'], false);
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
        $tour = parent::create(array_merge([
                'tour_no' => $this->getOrderNoRuleService()->createTourNo(),
                'line_id' => $line['id'],
                'line_name' => $line['name'],
                'execution_date' => $batch['execution_date'],
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['name'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_post_code' => $warehouse['post_code'],
                'warehouse_city' => $warehouse['city'],
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

    public function getNextBatch($batchIds): Batch
    {
        foreach ($batchIds as $key => $batchId) {
            $batch = Batch::where('id', $batchId)->where('status', BaseConstService::BATCH_DELIVERING)->first();
            if ($batch) {
                return $batch;
            }
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
        set_time_limit(240);
        self::setTourLock($this->formData['tour_no'], 1); // 加锁

        app('log')->info('更新线路传入的参数为:', $this->formData);

        $tour = Tour::where('code', $this->formData['tour_no'])->firstOrFail();

        $nextBatch = $this->getNextBatch($this->formData['batch_ids']);

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_DRIVER,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        //0.5s执行一次
        while (time_nanosleep(0, 500000000) === true) {
            app('log')->info('每 0.5 秒查询一次修改是否完成');
            //锁不存在代表更新完成
            if (!$this->getTourLock($tour->tour_no)) {
                return '修改线路成功';
            }

        }
        app('log')->error('进入此处代表修改线路失败');
        throw new BusinessLogicException('修改线路失败');
    }

    /**
     * 处理计算时间和距离的回调
     */
    public function dealCallback()
    {
        throw_unless(
            self::getTourLock($this->formData['tour_no']) == 1,
            new BusinessLogicException('不存在的动作')
        );

        $tourLog = TourLog::where('tour_no', $this->formData['tour_no'])->where('status', BaseConstService::TOUR_LOG_PENDING)->where('action', $this->formData['type'])->first();
        // app('log')->info('日志的时间戳为:' . $lineLog->timestamp . '当天开始的时间戳为:' . strtotime(date("Y-m-d")));
        if (time() - $tourLog->created_at > 3600 * 24 || $tourLog->created_at < strtotime(date("Y-m-d"))) { // 标记为异常日志
            app('log')->info('异常的线路日志为:' . $this->formData['tour_no']);
            $tourLog->update(['status' => BaseConstService::TOUR_LOG_ERROR]);
            throw new BusinessLogicException('更新时间已超时');
        }

        $info = $this->apiClient->LineInfo($this->formData['tour_no']);
        if (!$info || $info['ret'] == 0) { // 返回错误的情况下直接返回
            app('log')->info('更新动作失败,错误信息为:' . $info['msg']);
            self::setTourLock($this->formData['tour_no'], 0);
            return '已知道该次更新失败';
        }
        $data = $info['data'];

        app('log')->info('开始更新线路,线路标识为:' . $this->formData['tour_no']);
        app('log')->info('api返回的结果为:', $info);

        TourLog::where('line_code', $this->formData['tour_no'])->where('action', $this->formData['type'])->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
        $tour = Tour::where('tour_no', $this->formData['tour_no'])->first();
        $max_time = 0;
        $max_distance = 0;

        foreach ($data['loc_res'] as $key => $res) {
            $tourBatch = Batch::where('batch_no', $key)->where('tour_no', $this->formData['tour_no'])->first();
            $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', $data['timestamp'] + $res['time']);
            $tourBatch->expect_distance = $res['distance'];
            $tourBatch->save();
            $max_time = max($max_time, $res['time']);
            $max_distance = max($max_distance, $res['distance']);
        }

        if ($tour->expected_time == 0) { // 只有未更新过的线路需要更新期望时间和距离
            $tour->expected_time = $max_time;
            $tour->expected_distance = $max_distance;
            $tour->save();
        }
        $tour->lave_distance = $max_distance;

        app('log')->info('更新线路完成,线路标识为:' . $this->formData['tour_no']);
        //取消锁
        self::setTourLock($this->formData['tour_no'], 0);
        return '更新完成';
    }

    /**
     * 在途锁,更新操作同时只能存在一个!!!
     */
    public static function getTourLock(string $tourNo): int
    {
        $lock = app('reis')->get('tourUpdateOpration' . $tourNo);
        if ($lock === null) {
            return 0;
        }
        return $lock;
    }

    public static function setTourLock(string $tourNo, int $value)
    {
        return app('reis')->put('tourUpdateOpration' . $tourNo, $value);
    }

}