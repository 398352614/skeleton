<?php

namespace App\Services\Admin;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\TourInfoResource;
use App\Http\Resources\Api\Admin\TourResource;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\BaseServices\XLDirectionService;
use App\Services\TrackingOrderTrailService;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use App\Traits\LocationTrait;
use App\Traits\TourRedisLockTrait;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        'car_no' => ['=', 'car_no'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'line_name' => ['like', 'line_name'],
        'tour_no' => ['like', 'tour_no'],
        'driver_name' => ['like', 'driver_name'],
        'driver_id' => ['=', 'driver_id'],
        'tour_no,line_name,driver_name' => ['like', 'key_word']
    ];

    protected $headings = [
        'id',
        'place_fullname',
        'place_phone',
        'out_user_id',
        'place_address',
        'place_post_code',
        'place_city',
        'merchant',
        'expect_pickup_quantity',
        'expect_pie_quantity',
        'express_first_no_one',
        'express_first_no_two',
    ];

    protected $planHeadings = [
        'batch_no',
        'place_fullname',
        'place_phone',
        'place_address',
        'place_post_code',
        'place_city',
        'out_user_id',
        'merchant_name',
        'type',
        'package_quantity',
        'out_order_no',
        'mask_code',
        'material_code_list',
        'material_expect_quantity_list'
    ];

    protected $batchHeadings = [
        'date',
        'driver',
        'total_batch_count',
        'erp_batch_count',
        'mes_batch_count',
        'mix_batch_count',
        'erp_batch_percent',
        'mes_batch_percent',
        'mix_batch_percent'
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
        'place_out_user_id',
        'place_fullname',
        'place_phone',
        'place_post_code',
        'place_address',
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
        'actual_arrive_time',
        'out_arrive_expect_time',
        'expect_arrive_time',
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Tour $tour, GoogleApiService $client, XLDirectionService $directionClient)
    {
        parent::__construct($tour, TourResource::class, TourInfoResource::class);
        $this->apiClient = $client;
        $this->directionClient = $directionClient;
    }

    /**
     * 获取可加单的线路任务列表
     * @param $data
     * @return array|mixed
     * @throws BusinessLogicException
     */
    public function getAddOrderPageList($data)
    {
        list($orderIdList, $executionDate) = [$data['tracking_order_id_list'], $data['execution_date']];

        $this->filters['status'] = ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]];
        list($orderList, $lineId) = $this->getTrackingOrderService()->getAddTrackingOrderList($orderIdList, $executionDate);
        //$this->filters['line_id'] = ['=', $lineId];
        $this->filters['execution_date'] = ['=', $executionDate];
        $list = parent::getPageList();
        return $list;
    }

    /**
     * 线路任务查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPageList()
    {
        if (!empty($this->formData['is_dispatch'])) {
            if (intval($this->formData['is_dispatch']) == 1) {
                $this->query->where('expect_distance', '>', 0)->whereIn('status', [
                    BaseConstService::TOUR_STATUS_1,
                    BaseConstService::TOUR_STATUS_2,
                    BaseConstService::TOUR_STATUS_3,
                    BaseConstService::TOUR_STATUS_4,
                ]);
            } else {
                $this->query->where('expect_distance', '=', 0)->whereIn('status', [
                    BaseConstService::TOUR_STATUS_1,
                    BaseConstService::TOUR_STATUS_2,
                    BaseConstService::TOUR_STATUS_3,
                    BaseConstService::TOUR_STATUS_4,
                ]);;
            }
        }
        if (isset($this->filters['status'][1]) && (intval($this->filters['status'][1]) == 0)) {
            unset($this->filters['status']);
        }
        if (!empty($this->formData['sort_by_time']) && $this->formData['sort_by_time'] == BaseConstService::SORT_BY_TIME_2) {
            $this->query->orderBy('execution_date', 'desc');
        } else {
            $this->query->orderBy('execution_date');
        }
        $list = parent::getPageList();
        $merchantList = $this->getMerchantService()->getList([], ['id', 'name'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        foreach ($list as &$tour) {
            //处理多商家
            if ($tour['merchant_id'] == 0) {
                $tour['merchant_id_name'] = __('多商家');
            } else {
                $tour['merchant_id_name'] = $merchantList[$tour['merchant_id']]['name'] ?? '';
            }
            $tour['is_dispatch'] = ($tour['expect_distance'] == 0) ? 2 : 1;
            //计算站点数量
            $tour['expect_batch_count'] = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->count();
            $tour['actual_batch_count'] = $this->getBatchService()->getList(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_CHECKOUT], ['*'], false)->count();
        }
        return $list;
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
        //查看当前司机是否已被分配给其他线路任务
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'driver_id' => $params['driver_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前司机已被分配，请选择其他司机');
        }
        //获取司机
        $driver = $this->getDriverService()->getInfo(['id' => $params['driver_id'], 'is_locked' => BaseConstService::DRIVER_TO_NORMAL], ['*'], false);
        if (empty($driver)) {
            throw new BusinessLogicException('司机不存在');
        }
        $driver = $driver->toArray();
        if ($driver['is_locked'] == BaseConstService::DRIVER_TO_LOCK) {
            throw new BusinessLogicException('司机已被锁定');
        }
        //线路任务分配 由于线路任务,站点,运单的已分配状态都为2,所以只需取一个状态即可(ORDER_STATUS_2,BATCH_ASSIGNED,TOUR_STATUS_2)
        $rowCount = $this->assignOrCancelAssignAll($tour, ['driver_id' => $driver['id'], 'driver_name' => $driver['fullname'], 'driver_phone' => $driver['phone'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机分配失败，请重新操作');
        }
        $tour['driver_id'] = $driver['id'];
        $tour['driver_name'] = $driver['fullname'];
        $tour['driver_phone'] = $driver['phone'];
        TrackingOrderTrailService::storeByTour($tour, BaseConstService::TRACKING_ORDER_TRAIL_ASSIGN_DRIVER);
    }

    /**
     * 取消司机分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancelAssignDriver($id)
    {
        $tour = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::TOUR_STATUS_2, true);
        $rowCount = $this->assignOrCancelAssignAll($tour, ['driver_id' => null, 'driver_name' => null, 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('司机取消分配失败，请重新操作');
        }
        TrackingOrderTrailService::storeByTour($tour, BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_ASSIGN_DRIVER);
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
        //查看当前车辆是否已被分配给其他线路任务
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'car_id' => $params['car_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前车辆已被分配，请选择其他车辆');
        }
        //获取车辆
        $car = $this->getCarService()->getInfo(['id' => $params['car_id'], 'is_locked' => BaseConstService::CAR_TO_NORMAL], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('车辆不存在');
        }
        //分配
        $car = $car->toArray();
        if ($car['is_locked'] == BaseConstService::CAR_TO_LOCK) {
            throw new BusinessLogicException('车辆已被锁定');
        }
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
     * 分配或取消分配司机或车辆到线路任务-站点-运单
     * @param $tour
     * @param $data
     * @return bool
     */
    private function assignOrCancelAssignAll($tour, $data)
    {
        //线路任务
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) return false;
        //站点
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;
        //运单
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;
        //运单包裹
        $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no']], $data);
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
        //线路任务 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务取消锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败，请重新操作');
        }
        //运单 处理
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单取消锁定失败，请重新操作');
        }
        //运单包裹 处理
        $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单取消锁定失败，请重新操作');
        }
        TrackingOrderTrailService::storeByTour($tour, BaseConstService::TRACKING_ORDER_TRAIL_UN_LOCK);
    }


    /**
     * 站点加入线路任务
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
        //加入线路任务
        $quantity = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => 1] : ['expect_pie_quantity' => 1];
        $tour = !empty($tour) ? $this->joinExistTour($tour, $quantity) : $this->joinNewTour($batch, $line, $quantity);
        return $tour;
    }

    /**
     * 加入新的线路任务
     * @param $line
     * @param $batch
     * @param $quantity
     * @return BaseService|array|\Illuminate\Database\Eloquent\Model|mixed
     * @throws BusinessLogicException
     */
    private function joinNewTour($batch, $line, $quantity)
    {
        //获取网点信息
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
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
                'warehouse_name' => $warehouse['name'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_country' => $warehouse['country'],
                'warehouse_post_code' => $warehouse['post_code'],
                'warehouse_city' => $warehouse['city'],
                'warehouse_street' => $warehouse['street'],
                'warehouse_house_number' => $warehouse['house_number'],
                'warehouse_address' => $warehouse['address'],
                'warehouse_lon' => $warehouse['lon'],
                'warehouse_lat' => $warehouse['lat'],
                'merchant_id' => $batch['merchant_id'] ?? 0
            ], $quantity)
        );
        if ($tour === false) {
            throw new BusinessLogicException('站点加入线路任务失败，请重新操作');
        }
        return $tour->getOriginal();
    }


    /**
     * 加入已存在线路任务
     * @param $tour
     * @param $quantity
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
            throw new BusinessLogicException('站点加入线路任务失败，请重新操作');
        }
        $tour = array_merge($tour, $data);
        return $tour;
    }

    /**
     * 移除站点运单
     * @param $order
     * @param $batch
     * @throws BusinessLogicException
     */
    public function removeBatchTrackingOrder($order, $batch)
    {
        $info = $this->getInfoOfStatus(['tour_no' => $order['tour_no']], true, [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3], true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他运单时,删除站点;若还存在其他运单,则只移除运单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务移除运单失败，请重新操作');
        }
    }

    /**
     * 移除站点
     * @param $batch
     * @param $isCancelBatch
     * @return array
     * @throws BusinessLogicException
     */
    public function removeBatch($batch, $isCancelBatch = false)
    {
        $info = parent::getInfo(['tour_no' => $batch['tour_no']], ['id'], false);
        if (empty($info)) {
            return [];
        }
        $status = $isCancelBatch ? [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4] : [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2];
        $info = $this->getInfoOfStatus(['tour_no' => $batch['tour_no']], true, $status, true);
        $quantity = intval($info['expect_pickup_quantity']) + intval($info['expect_pie_quantity']);
        $batchQuantity = intval($batch['expect_pickup_quantity']) + intval($batch['expect_pie_quantity']);
        //当站点中不存在其他运单时,删除站点;若还存在其他运单,则只移除运单
        if ($quantity - $batchQuantity <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - $batch['expect_pickup_quantity'], 'expect_pie_quantity' => $info['expect_pie_quantity'] - $batch['expect_pie_quantity']];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务移除站点失败，请重新操作');
        }
        return $info;
    }

    /**
     * 通过站点,获取可分配的线路任务
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
                    //取件运单，线路最大运单量验证
                    if ($batch['status'] = BaseConstService::TRACKING_ORDER_TYPE_1 && $tour[$i]['expect_pickup_quantity'] + $batch['expect_pickup_quantity'] < $line['pickup_max_count']) {
                        $data[$i] = $tour[$i];
                    }
                    //派件运单，线路最大运单量验证
                    if ($batch['status'] = BaseConstService::TRACKING_ORDER_TYPE_2 && $tour[$i]['expect_pie_quantity'] + $batch['expect_pie_quantity'] < $line['pie_max_count']) {
                        $data[$i] = $tour[$i];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 获取线路日期
     * @param $id
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function getLineDate($id, $data)
    {
        $params = parent::getInfo(['id' => $id], ['id'], false);
        if (empty($params)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batch = $this->getBatchService()->getInfo(['tour_no' => $params->tour_no], ['*'], false);
        $data = $this->getLineService()->getScheduleListByLine($batch->toArray(), $data['line_id']);
        return $data;
    }

    /**
     * 线路分配
     * @param $id
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function assignTourToTour($id, $params)
    {
        $info = parent::getInfo(['id' => $id], ['id', 'tour_no'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['id'], false)->toArray();
        return $this->getBatchService()->assignListToTour(array_column($batchList, 'id'), $params);
    }


    /**
     * 分配站点至线路任务
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
        $tour = $this->getTourInfo($batch, $line, true, $params['tour_no'] ?? '', true);
        if (!empty($params['tour_no']) && empty($tour)) {
            throw new BusinessLogicException('当前指定线路任务不符合当前站点');
        }
        $quantity = ['expect_pickup_quantity' => $batch['expect_pickup_quantity'], 'expect_pie_quantity' => $batch['expect_pie_quantity']];
        //若存在线路任务，判断当前线路任务中是否已存在相同站点,若存在，则合并
        if (!empty($tour)) {
            $batch = $this->getBatchService()->mergeTwoBatch($tour, $batch);
            $tour = $this->joinExistTour($tour, $quantity);
        } else {
            $tour = $this->joinNewTour($batch, $line, $quantity);
        }
        return [$tour, $batch];
    }

    /**
     * 获取线路任务信息
     * @param $batch
     * @param $line
     * @param $isLock
     * @param $tourNo
     * @param $isAssign
     * @param $isAddOrder
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTourInfo($batch, $line, $isLock = true, $tourNo = null, $isAssign = false, $isAddOrder = false)
    {
        if (!empty($tourNo)) {
            $this->query->where('tour_no', '=', $tourNo);
        }
        //若不存在线路任务或者超过最大运单量,则新建线路任务
        if ((intval($batch['expect_pickup_quantity']) > 0) && (($isAssign == false) && ($isAddOrder == false))) {
            $this->query->where(DB::raw('expect_pickup_quantity+' . 1), '<=', $line['pickup_max_count']);
        }
        if ((intval($batch['expect_pie_quantity']) > 0) && ($isAssign == false) && ($isAddOrder == false)) {
            $this->query->where(DB::raw('expect_pie_quantity+' . 1), '<=', $line['pie_max_count']);
        }
        $where = ['line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]];
        if ($isAddOrder == true) {
            $where['status'] = ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]];
        }
        isset($batch['merchant_id']) && $where['merchant_id'] = $batch['merchant_id'];
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
     * @param $batchIds
     * @return Batch
     * @throws BusinessLogicException
     */
    public function getNextBatchAndUpdateIndex($batchIds): Batch
    {
        if (count($batchIds) == 1) {
            return Batch::where('id', collect($batchIds)->first())->first();
        }
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
            if (!empty($tempbatch)) {
                $tempbatch->update(['sort_id' => $key + 1]);
            }
        }
        if ($batch ?? null) {
            return $batch;
        }

        throw new BusinessLogicException('未查找到下一个目的地');
    }

    /**
     * 更新批次配送顺序
     * @param $data
     * @return string
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function updateBatchIndex($data)
    {
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        // set_time_limit(240);
        Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '更新线路传入的参数', $data);
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
     * @throws BusinessLogicException
     */
    public function autoOpTour($data)
    {
        // set_time_limit(240);
        $tour = Tour::where('tour_no', $data['tour_no'])->firstOrFail();
        if ($tour->status == BaseConstService::TOUR_STATUS_5) {
            throw new BusinessLogicException('线路任务已完成，不能优化');
        }
        $this->getApiTimesService()->timesCount('directions_times', $tour->company_id);
        TourLog::create([
            'tour_no' => $data['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);
        $this->getApiTimesService()->timesCount('actual_directions_times', $tour->company_id);

        if (empty($tour->company_id)) {
            $companyId = $tour['company_id'];
        } else {
            $companyId = $tour->company_id;
        }
        Log::info($companyId);
        TourOptimizationService::getOpInstance($tour->company_id)->autoUpdateTour($tour);

        return '修改线路成功';
    }

    /**
     * 处理计算时间和距离的回调
     * @throws BusinessLogicException
     * @throws \Throwable
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
            $tourLog->update(['status' => BaseConstService::TOUR_LOG_ERROR]);
            self::setTourLock($this->formData['line_code'], 0);
            throw new BusinessLogicException('更新时间已超时');
        }

        $info = $this->apiClient->lineInfo($this->formData['line_code']);
        if (!$info || $info['ret'] == 0) { // 返回错误的情况下直接返回
            self::setTourLock($this->formData['line_code'], 0);
            return '已知道该次更新失败';
        }
        $data = $info['data'];


        TourLog::where('tour_no', $this->formData['line_code'])->where('action', $this->formData['type'])->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
        $tour = Tour::where('tour_no', $this->formData['line_code'])->first();
        $max_time = 0;
        $max_distance = 0;

        foreach ($data['loc_res'] as $key => $res) {
            $tourBatch = Batch::where('batch_no', str_replace($this->formData['line_code'], '', $key))->where('tour_no', $this->formData['line_code'])->first();
            $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', time() + $res['time']);
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

        //取消锁
        self::setTourLock($this->formData['line_code'], 0);
        return '更新完成';
    }

    /**
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchCountInfo($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info['batch_count'] = $this->getBatchService()->count(['tour_no' => $info['tour_no']]);
        //如果已回网点，处理网点相关数据
        if ($info['status'] == BaseConstService::TOUR_STATUS_5) {
            $batchList = $this->getBatchService()->getList(['tour_no' => $info['tour_no']], ['*'], false);
            if (empty($batchList)) {
                throw new BusinessLogicException('数据不存在');
            }
            $batchList = $batchList->toArray();
            $batch = collect($batchList)->sortByDesc('actual_arrive_time')->first();
            if(!empty($batch['actual_arrive_time'])){
                $info['warehouse_actual_time'] = strtotime($info['end_time']) - strtotime($batch['actual_arrive_time']);
            }else{
                $info['warehouse_actual_time'] = strtotime($info['end_time']);
            }
            if (!$info['warehouse_actual_time'] == 0) {
                $warehouseActualTimeHuman = CarbonInterval::second($info['warehouse_actual_time'])->cascade()->forHumans();
            } else {
                $warehouseActualTimeHuman = '0秒';
            }
            if (!$info['warehouse_expect_time'] == 0) {
                $warehouseExpectTimeHuman = CarbonInterval::second($info['warehouse_expect_time'])->cascade()->forHumans();
            } else {
                $warehouseExpectTimeHuman = '0秒';
            }
            $info['warehouse_actual_distance'] = $info['warehouse_expect_distance'];
            $info['warehouse_actual_arrive_time'] = $info['end_time'];
            $info['warehouse_actual_time_human'] = $warehouseActualTimeHuman;
            $info['warehouse_expect_time_human'] = $warehouseExpectTimeHuman;
        }
        $info['batchs'] = collect($info['batchs'])->sortBy('sort_id')->all();
        $info['batchs'] = array_values($info['batchs']);
        $trackingOrderTotalList = $this->getTrackingOrderService()->getList(['tour_no' => $info['tour_no']], ['*'], false);
        foreach ($info['batchs'] as $k => $v) {
            $info['batchs'][$k] = collect($info['batchs'][$k])->toArray();
            $trackingOrderList = array_values(collect($trackingOrderTotalList)->where('batch_no', $v['batch_no'])->all());
            $info['batchs'][$k]['out_user_id'] = '';
            if (count($trackingOrderList) > 1) {
                if (in_array(config('tms.erp_merchant_id'), collect($trackingOrderList)->pluck('merchant_id')->toArray())) {
                    $order = collect($trackingOrderList)->where('merchant_id', config('tms.erp_merchant_id'))->where('out_user_id', '<>', '')->first();
                } elseif (in_array(config('tms.eushop_merchant_id'), collect($trackingOrderList)->pluck('merchant_id')->toArray())) {
                    $order = collect($trackingOrderList)->where('merchant_id', config('tms.eushop_merchant_id'))->where('out_user_id', '<>', '')->first();
                }
                if (empty($order)) {
                    $order = collect($trackingOrderList)->where('out_user_id', '<>', '')->first();;
                }
                if (count(collect($trackingOrderList)->groupBy('out_user_id')) == 1) {
                    $info['batchs'][$k]['out_user_id'] = $order['out_user_id'] ?? '';
                } else {
                    $info['batchs'][$k]['out_user_id'] = ($order['out_user_id'] ?? '') . ' ' . __('等');
                }
            } elseif (count($trackingOrderList) == 1) {
                $info['batchs'][$k]['out_user_id'] = collect($trackingOrderList)->sortBy('out_user_id')->toArray()[0]['out_user_id'];
            }
            $info['batchs'][$k]['sort_id'] = $k + 1;
        }
        $info['batchs'] = array_values($info['batchs']);
        $pickupTrackingOrderList = collect($trackingOrderTotalList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_1)->toArray();
        $pieTrackingOrderList = collect($trackingOrderTotalList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_2)->toArray();
        $info['expect_pickup_package_quantity'] = $this->getTrackingOrderPackageService()->count(['tracking_order_no' => ['in', array_column($pickupTrackingOrderList, 'tracking_order_no')]]);
        $info['expect_pie_package_quantity'] = $this->getTrackingOrderPackageService()->count(['tracking_order_no' => ['in', array_column($pieTrackingOrderList, 'tracking_order_no')]]);
        return $info;
    }

    /**
     * 通过线路ID 获取可加入的线路任务列表
     * @param $lineId
     * @return array
     */
    public function getListJoinByLineId($data)
    {
        $list = parent::getList(['line_id' => $data['line_id'], 'execution_date' => $data['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]], ['id', 'tour_no'], false)->toArray();
        return $list;
    }

    protected function getRelativeUrl(string $url): string
    {
        return str_replace(config('app.url'), '', $url);
    }

    /**
     * 导出站点表格
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function batchExport($params)
    {
        $firstDate = Carbon::create($params['year'], $params['month'])->format('Y-m-d');
        $today = Carbon::today();
        if ($today->year < $params['year'] || ($today->year == $params & $today->month > $params['month'])) {
            throw new BusinessLogicException('只能选择本月之前的月份');
        } elseif ($today->year == $params['year'] && $today->month == $params['month']) {
            $lastDate = $today->subDay()->format('Y-m-d');
        } else {
            $lastDate = Carbon::create($params['year'], $params['month'])->endOfMonth()->format('Y-m-d');
        }
        $tourList = parent::getList(['execution_date' => ['between', [$firstDate, $lastDate]], 'status' => BaseConstService::TOUR_STATUS_5], ['*'], false, [], ['execution_date' => 'asc']);
        $erpMerchantId = config('tms.erp_merchant_id') . ',' . config('tms.tcp_merchant_id');
        $mesMerchantId = config('tms.eushop_merchant_id');
        $status = BaseConstService::BATCH_CHECKOUT . ',' . BaseConstService::BATCH_CANCEL;
        $companyId = auth()->user()->company_id;
        $erpBatchCountSql = "SELECT COUNT(*) as num,tour_no FROM `batch` as b WHERE b.`execution_date` BETWEEN '{$firstDate}' AND '{$lastDate}' AND (SELECT a.`id` FROM `tracking_order` as a WHERE a.`merchant_id` IN ({$erpMerchantId}) AND a.`batch_no`=b.`batch_no` LIMIT 1)<>'' AND b.`status` IN ({$status}) AND b.`company_id`={$companyId} GROUP BY b.tour_no;";
        $mesBatchCountSql = "SELECT COUNT(*) as num,tour_no FROM `batch` as b WHERE b.`execution_date` BETWEEN '{$firstDate}' AND '{$lastDate}' AND (SELECT a.`id` FROM `tracking_order` as a WHERE a.`merchant_id`={$mesMerchantId} AND a.`batch_no`=b.`batch_no` LIMIT 1)<>'' AND b.`status` IN ({$status}) AND b.`company_id`={$companyId} GROUP BY b.tour_no;";
        $mixBatchCountSql = "SELECT COUNT(*) as num,tour_no FROM `batch` as b WHERE b.`execution_date` BETWEEN '{$firstDate}' AND '{$lastDate}' AND (SELECT a.`id` FROM `tracking_order` as a WHERE a.`merchant_id` IN ({$erpMerchantId}) AND a.`batch_no`=b.`batch_no` LIMIT 1)<>'' AND (SELECT d.`id` FROM `tracking_order` as d WHERE d.`merchant_id`={$mesMerchantId} AND d.`batch_no`=b.`batch_no` LIMIT 1)<>'' AND b.`status` IN ({$status}) AND b.`company_id`={$companyId} GROUP BY b.tour_no";
        $erpBatchList = array_create_index(collect(DB::select($erpBatchCountSql))->map(function ($value) {
            return (array)$value;
        })->toArray(), 'tour_no');
        $mesBatchList = array_create_index(collect(DB::select($mesBatchCountSql))->map(function ($value) {
            return (array)$value;
        })->toArray(), 'tour_no');
        $mixBatchList = array_create_index(collect(DB::select($mixBatchCountSql))->map(function ($value) {
            return (array)$value;
        })->toArray(), 'tour_no');
        $dataList = [];
        foreach ($tourList as $tour) {
            $mixBatchCount = $mixBatchList[$tour['tour_no']]['num'] ?? 0;
            $erpBatchCount = ($erpBatchList[$tour['tour_no']]['num'] ?? 0) - $mixBatchCount;
            $mesBatchCount = ($mesBatchList[$tour['tour_no']]['num'] ?? 0) - $mixBatchCount;
            $totalBatchCount = $mixBatchCount + $erpBatchCount + $mesBatchCount;
            $mixBatchPercent = $totalBatchCount == 0 ? 0 : round($mixBatchCount * 100 / $totalBatchCount, 2);
            $erpBatchPercent = $totalBatchCount == 0 ? 0 : round($erpBatchCount * 100 / $totalBatchCount, 2);
            $mesBatchPercent = $totalBatchCount == 0 ? 0 : 100 - $mixBatchPercent - $erpBatchPercent;
            $data = [
                'date' => $tour['execution_date'] . ' ' . ConstTranslateTrait::weekList(Carbon::create($tour['execution_date'])->dayOfWeek),
                'driver' => $tour['line_name'] . ' ' . $tour['driver_name'],
                'mix_batch_count' => $mixBatchCount,
                'erp_batch_count' => $erpBatchCount,
                'mes_batch_count' => $mesBatchCount,
                'total_batch_count' => $totalBatchCount,
                'mix_batch_percent' => $mixBatchPercent,
                'erp_batch_percent' => $erpBatchPercent,
                'mes_batch_percent' => $mesBatchPercent,
            ];
            $dataList[] = array_only_fields_sort($data, $this->batchHeadings);
        }
        $headings = [[$params['year'] . '-' . $params['month']], $this->batchHeadings];
        $dir = 'batchCount';
        $name = date('Ymd') . $companyId;
        return $this->excelExport($name, $headings, $dataList, $dir);
    }

    /**
     * 统计运单数量
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
     * 导出线路任务
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
        $tour['expect_pie_package_quantity'] = $tour['actual_pie_package_quantity'] = $tour['expect_pickup_package_quantity'] = $tour['actual_pickup_package_quantity'] = $tour['expect_material_quantity'] = $tour['actual_material_quantity'] = 0;
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();;
        $packageList = $this->getTrackingOrderPackageService()->getList(['order_no' => ['in', collect($trackingOrderList)->pluck('order_no')->toArray()]], ['*'], false)->toArray();
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false, [], ['actual_arrive_time' => 'asc', 'created_at' => 'asc'])->toArray();
        if (empty($batchList)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (empty($trackingOrderList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $materialList = $this->getTrackingOrderMaterialService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        for ($i = 0; $i < count($batchList); $i++) {
            $batchList[$i]['out_user_id'] = collect($trackingOrderList)->where('batch_no', $batchList[$i]['batch_no'])->first() ? collect($trackingOrderList)->where('batch_no', $batchList[$i]['batch_no'])->first()['out_user_id'] : '';
            $batchList[$i]['expect_pie_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_2)->where('batch_no', $batchList[$i]['batch_no'])->all());
            $batchList[$i]['actual_pie_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_2)->where('batch_no', $batchList[$i]['batch_no'])->where('status', BaseConstService::PACKAGE_STATUS_3)->all());
            $batchList[$i]['expect_pickup_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_1)->where('batch_no', $batchList[$i]['batch_no'])->all());
            $batchList[$i]['actual_pickup_package_quantity'] = count(collect($packageList)->where('type', BaseConstService::TRACKING_ORDER_TYPE_1)->where('batch_no', $batchList[$i]['batch_no'])->where('status', BaseConstService::PACKAGE_STATUS_3)->all());
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
            $cellData[$i][0] = $cellData[$i][1] = $cellData[$i][2] = $cellData[$i][2] = $cellData[$i][3] = $cellData[$i][4] = $cellData[$i][5] = $cellData[$i][6] = $cellData[$i][7] = $cellData[$i][8] = $cellData[$i][9] = 0;
            $cellData[$i][10] = $batchList[$i]['out_user_id'];
            $cellData[$i][11] = $batchList[$i]['place_fullname'];
            $cellData[$i][12] = $batchList[$i]['place_phone'];
            $cellData[$i][13] = $batchList[$i]['place_post_code'];
            $cellData[$i][14] = $batchList[$i]['place_address'];
            $cellData[$i][15] = $batchList[$i]['expect_pie_quantity'];
            $cellData[$i][16] = $batchList[$i]['actual_pie_quantity'];
            $cellData[$i][17] = $batchList[$i]['expect_pickup_quantity'];
            $cellData[$i][18] = $batchList[$i]['actual_pickup_quantity'];
            $cellData[$i][19] = $batchList[$i]['expect_pie_package_quantity'];
            $cellData[$i][20] = $batchList[$i]['actual_pie_package_quantity'];
            $cellData[$i][21] = $batchList[$i]['expect_pickup_package_quantity'];
            $cellData[$i][22] = $batchList[$i]['actual_pickup_package_quantity'];
            $cellData[$i][23] = $batchList[$i]['expect_material_quantity'];
            $cellData[$i][24] = $batchList[$i]['actual_material_quantity'];
            $cellData[$i][25] = $batchList[$i]['status'];
            $cellData[$i][26] = $batchList[$i]['actual_arrive_time'];
            $cellData[$i][27] = $batchList[$i]['out_expect_arrive_time'];
            $cellData[$i][28] = $tour['status'] == BaseConstService::TOUR_STATUS_5 ? $batchList[$i]['expect_arrive_time'] : null;
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

    /**
     * 计划导出
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function planExport($id)
    {
        $tour = $this->query->where('id', '=', $id)->first();
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $headings = [
            ['execution_date', $tour['execution_date']],
            ['line_name', $tour['line_name']],
            ['driver_name', $tour['driver_name']],
            ['car_no', $tour['car_no']],
            [],
            $this->planHeadings,
        ];
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        $materialList = $this->getTrackingOrderMaterialService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        $packageList = $this->getTrackingOrderPackageService()->getList(['tour_no' => $tour['tour_no']], ['*'], false)->toArray();
        $orderList = $this->getOrderService()->getList(['tracking_order_no' => ['in', collect($trackingOrderList)->pluck('tracking_order_no')->toArray()]], ['*'], false)->toArray();
        if (empty($materialList) && empty($packageList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        if (empty($batchList) || empty($orderList) || empty($trackingOrderList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', collect($trackingOrderList)->pluck('merchant_id')->toArray()]], ['*'], false)->toArray();
        foreach ($trackingOrderList as $k => $v) {
            $trackingOrderList[$k]['out_user_id'] = collect($orderList)->where('tracking_order_no', $v['tracking_order_no'])->first()['out_user_id'] ?? '';
            $trackingOrderList[$k]['sort_id'] = collect($batchList)->where('batch_no', $v['batch_no'])->first()['sort_id'] ?? 1000;
            $trackingOrderList[$k]['merchant_name'] = collect($merchantList)->where('id', $v['merchant_id'])->first()['name'] ?? '';
            $trackingOrderList[$k]['package_quantity'] = collect($packageList)->where('order_no', $v['order_no'])->count();
            $trackingOrderList[$k]['type'] = $trackingOrderList[$k]['type_name'];
            $trackingOrderList[$k]['place_address'] = $trackingOrderList[$k]['place_street'] . ' ' . $trackingOrderList[$k]['place_house_number'];
            $trackingOrderList[$k]['material_code_list'] = implode("\r", collect($materialList)->where('order_no', $v['order_no'])->pluck('code')->toArray());
            $trackingOrderList[$k]['material_expect_quantity_list'] = implode("\r", collect($materialList)->where('order_no', $v['order_no'])->pluck('expect_quantity')->toArray());
        }
        $trackingOrderList = array_values(collect($trackingOrderList)->sortBy('sort_id')->toArray());
        for ($i = 0, $j = count($trackingOrderList); $i < $j; $i++) {
            $trackingOrderList[$i] = array_only_fields_sort($trackingOrderList[$i], $this->planHeadings);
        }
        $sort = [];
        for ($i = 0, $j = count($trackingOrderList); $i < $j; $i++) {
            if (!empty($trackingOrderList[$i + 1]) && $trackingOrderList[$i]['batch_no'] !== $trackingOrderList[$i + 1]['batch_no']) {
                $sort = array_merge($sort, [$i + 1]);
            }
        }
        $params['sort'] = array_merge($sort, [count($trackingOrderList)]);
        $data = $trackingOrderList;
        $count = count($trackingOrderList);
        //材料总计
        $tourMaterial = [];
        foreach ($materialList as $k => $v) {
            if (empty($tourMaterial[$v['code']])) {
                $tourMaterial[$v['code']] = 0;
            }
            $tourMaterial[$v['code']] += $v['expect_quantity'];
        }
        $newTourMaterial = [];
        foreach ($tourMaterial as $k => $v) {
            $newTourMaterial[$k]['code'] = $k;
            $newTourMaterial[$k]['quantity'] = $v;
        }
        $newTourMaterial = array_values($newTourMaterial);
        $data[$count] = ['batch_no' => ' '];
        $data[$count + 1] = ['batch_no' => __('材料汇总')];
        $data[$count + 2] = ['batch_no' => __('材料代码'), 'out_user_id' => __('材料数量')];

        foreach ($newTourMaterial as $k => $v) {
            $data[$k + $count + 3]['batch_no'] = $v['code'];
            $data[$k + $count + 3]['out_user_id'] = $v['quantity'];
        }
        $dir = 'plan';
        $name = date('Ymd') . $tour['tour_no'] . auth()->user()->id;
        return $this->excelExport($name, $headings, $data, $dir, $params);
    }

    /**
     * 获取有序站点列表
     * @param $tourNo
     * @param bool $onlyId
     * @return array
     */
    public function getBatchListSortBySortId($tourNo, $onlyId = false)
    {
        $batchList = $this->getBatchService()->getlist(['status' => ['in', [BaseConstService::BATCH_CANCEL, BaseConstService::BATCH_CHECKOUT]], 'tour_no' => $tourNo], ['id', 'sort_id'], false)->toArray();
        $ingBatchList = $this->getBatchService()->getlist(['status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING]], 'tour_no' => $tourNo], ['id', 'sort_id'], false)->toArray();
        $batchList = array_merge($batchList, $ingBatchList);
        if ($onlyId == true) {
            $batchList = array_column($batchList, 'id');
            $batchList = array_values($batchList);
        }
        return $batchList;
    }

    /**
     * 路线导航
     * @param $tourNo
     * @return string
     * @throws BusinessLogicException
     */
    public function routeNavigation($tourNo)
    {
        return $this->autoOpTour(['tour_no' => $tourNo]);
    }

    /**
     * 线路刷新
     * @param $tourNo
     * @param array $batchIdList
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function routeRefresh($tourNo, $batchIdList = [])
    {
        if (empty($batchList)) {
            $batchIdList = $this->getBatchListSortBySortId($tourNo, true);
        }
        $this->updateBatchIndex(['tour_no' => $tourNo, 'batch_ids' => $batchIdList]);
    }

    /**
     * 导出站点地图
     * @param $id
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchPng($id)
    {
        $tourInfo = $this->getInfo(['id' => $id], ['*'], false);
        if (empty($tourInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $this->getBatchService()->getList(['tour_no' => $tourInfo['tour_no']], ['*'], false, [], ['sort_id' => 'asc'])->toArray();
        $params[0]['lon'] = $tourInfo['warehouse_lon'];
        $params[0]['lat'] = $tourInfo['warehouse_lat'];
        for ($i = 1; $i <= count($info); $i++) {
            $params[$i]['lon'] = $info[$i - 1]['place_lon'];
            $params[$i]['lat'] = $info[$i - 1]['place_lat'];
        }
        $name = $tourInfo['tour_no'];
        return LocationTrait::getBatchMap($params, $name);
    }

}
