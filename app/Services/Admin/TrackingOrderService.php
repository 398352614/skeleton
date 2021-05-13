<?php

/**
 * 运单服务
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/20
 * Time: 16:39
 */

namespace App\Services\Admin;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\TrackingOrderInfoResource;
use App\Http\Resources\Api\Admin\TrackingOrderResource;
use App\Jobs\AddOrderPush;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Tour;
use App\Models\TrackingOrder;
use App\Notifications\TourAddTrackingOrder;
use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use App\Services\TrackingOrderTrailService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

/**
 * Class TrackingOrderService
 * @package App\Services\Merchant
 *
 */
class TrackingOrderService extends BaseService
{
    use ExportTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'place_post_code,tour_no,tracking_order_no,order_no,out_order_no,out_user_id,batch_no' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'tour_no' => ['like', 'tour_no'],
        'batch_no' => ['like', 'batch_no'],
        'out_user_id' => ['like', 'out_user_id'],
        'tracking_order_no' => ['like', 'tracking_order_no'],
        'out_order_no' => ['like', 'out_order_no'],
        'order_no' => ['like', 'order_no']
    ];

    protected $tOrderAndOrderSameFields = [
        'merchant_id',
        'out_user_id',
        'out_order_no',
        'order_no',
        'out_status',
        'execution_date',
        'place_fullname',
        'place_phone',
        'place_country',
        'place_province',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_district',
        'place_street',
        'place_address',
        'place_lon',
        'place_lat',
        'mask_code',
        'special_remark',
    ];


    public $headings = [
        'tracking_order_no',
        'type',
        'order_no',
        'merchant_name',
        'status',
        'out_user_id',
        'out_order_no',
        'place_post_code',
        'place_house_number',
        'execution_date',
        'driver_name',
        'batch_no',
        'tour_no',
        'line_name',
        'created_at',
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingOrder $trackingOrder)
    {
        parent::__construct($trackingOrder, TrackingOrderResource::class, TrackingOrderInfoResource::class);
    }

    /**
     * 查询初始化
     * @return array
     */
    public function initIndex()
    {
        $data = [];
        $data['status_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$trackingOrderStatusList);
        $data['type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$trackingOrderTypeList);
        return $data;
    }

    /**
     * 通过地址获得可选日期
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getAbleDateListByAddress($params)
    {
        $this->validate($params);
        $data = $this->getLineService()->getScheduleList($params);
        return $data;
    }

    /**
     * 获取可选日期验证
     * @param $info
     * @throws BusinessLogicException
     */
    public function validate($info)
    {
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_AREA) {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'place_lon' => 'required|string|max:50', 'place_lat' => 'required|string|max:50']);
        } else {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'place_post_code' => 'required|string|max:50']);
        }
        if ($validator->fails()) {
            throw new BusinessLogicException('地址数据不正确，无法拉取可选日期', 3001);
        }
    }

    /**
     * 订单统计
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingOrderCount($params)
    {
        $type = $params['type'] ?? 0;
        return [
            BaseConstService::TRACKING_ORDER_STATUS_0 => $this->singleOrderCount($type),
            BaseConstService::TRACKING_ORDER_STATUS_1 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_1),
            BaseConstService::TRACKING_ORDER_STATUS_2 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_2),
            BaseConstService::TRACKING_ORDER_STATUS_3 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_3),
            BaseConstService::TRACKING_ORDER_STATUS_4 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_4),
            BaseConstService::TRACKING_ORDER_STATUS_5 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_5),
            BaseConstService::TRACKING_ORDER_STATUS_6 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_6),
            BaseConstService::TRACKING_ORDER_STATUS_7 => $this->singleOrderCount($type, BaseConstService::TRACKING_ORDER_STATUS_7),
        ];
    }


    /**
     * 单项订单统计
     * @param $type
     * @param $status
     * @param null $exceptionType
     * @return int
     */
    public function singleOrderCount($type, $status = null, $exceptionType = null)
    {
        $where = [];
        if (!empty($status)) {
            $where = ['status' => $status];
        }
        if (!empty($type)) {
            $where = array_merge($where, ['type' => $type]);
        }
        if (!empty($exceptionType)) {
            $where = array_merge($where, ['exception_label' => $exceptionType]);
        }
        return parent::count($where);
    }


    public function getLineList()
    {
        if (CompanyTrait::getCompany()['show_type'] == BaseConstService::LINE_RULE_SHOW && CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            $info = $this->getLineRangeService()->getList([], ['*'], false);
            $lineId = $info->pluck('line_id')->toArray();
        } elseif (CompanyTrait::getCompany()['show_type'] == BaseConstService::LINE_RULE_SHOW && CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_AREA) {
            $info = $this->getLineAreaService()->getList([], ['*'], false);
            $lineId = $info->pluck('line_id')->toArray();
        } else {
            $info = $this->getLineService()->getList([], ['*'], false);
            $lineId = $info->pluck('id')->toArray();
        }
        if (!empty($lineId)) {
            $data = $this->getLineService()->getList(['id' => ['in', $lineId]], ['id', 'name'], false);
        }
        return $data ?? [];
    }

    public function getPageList()
    {
        if (!empty($this->formData['line_id']) && !empty($this->formData['tour_no'])) {
            $tourList = $this->getTourService()->getList(['line_id' => $this->formData['line_id']])->pluck('tour_no')->toArray();
            $this->query->whereIn('tour_no', $tourList)->where('tour_no', 'like', $this->formData['tour_no']);
        } elseif (!empty($this->formData['line_id'])) {
            $tourList = $this->getTourService()->getList(['line_id' => $this->formData['line_id']])->pluck('tour_no')->toArray();
            $this->query->whereIn('tour_no', $tourList);
        }
        if (!empty($this->formData['post_code'])) {
            $trackingOrderList = $this->getTrackingOrderService()->getList(['place_post_code' => ['like', $this->formData['post_code']]]);
            if (!$trackingOrderList->isEmpty()) {
                $trackingOrderList = $trackingOrderList->pluck('tracking_order_no')->toArray();
                $this->query->whereIn('tracking_order_no', $trackingOrderList);
            }
        }
        $list = parent::getPageList();
        foreach ($list as &$trackingOrder) {
            $batchException = $this->getBatchExceptionService()->getInfo(['batch_no' => $trackingOrder['batch_no']], ['id', 'batch_no', 'stage'], false, ['created_at' => 'desc']);
            $trackingOrder['exception_stage_name'] = !empty($batchException) ? ConstTranslateTrait::batchExceptionStageList($batchException['stage']) : __('正常');
        }
        return $list;
    }

    public function show($id)
    {
        $dbTrackingOrder = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($dbTrackingOrder)) return [];
        $dbTrackingOrder['package_list'] = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['*'], false)->toArray();
        $dbTrackingOrder['material_list'] = $this->getTrackingOrderMaterialService()->getList(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['*'], false)->toArray();
        return $dbTrackingOrder;
    }

    /**
     * 运单新增
     * @param $order
     * @return bool
     * @throws BusinessLogicException
     */
    public function storeByOrder(Order $order)
    {
        $params = $this->fillData($order);
        $tour = $this->store($params, $order->order_no);
        return $tour;
    }

    /**
     * 新增
     * @param $params
     * @param $orderNo
     * @param bool $again
     * @return bool
     * @throws BusinessLogicException
     */
    public function store($params, $orderNo, $again = false)
    {
        //填充网点信息
        $line = $this->fillWarehouseInfo($params, BaseConstService::YES);
        //验证网点是否承接取件/派件
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        $acceptTypeList = explode(',', $warehouse['accept_type']);
        if (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1, $acceptTypeList) &&
            $params['type'] == BaseConstService::TRACKING_ORDER_TYPE_1) {
            throw new BusinessLogicException('该发件人地址所属区域，网点不承接取件订单');
        } elseif (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2, $acceptTypeList) &&
            $params['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
            throw new BusinessLogicException('该收件人地址所属区域，网点不承接派件订单');
        }
        //生成运单号
        $params['tracking_order_no'] = $this->getOrderNoRuleService()->createTrackingOrderNo();
        /**********************************************生成运单********************************************************/
        $trackingOrder = parent::create($params);
        if ($trackingOrder == false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $trackingOrder = $trackingOrder->getAttributes();
        /*****************************************运单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($trackingOrder, $line);
        $trackingOrder = $this->fillBatchTourInfo($trackingOrder, $batch, $tour, $again);
        /*******************************************填充运单信息至订单***************************************************/
        $this->fillToOrder($orderNo, $trackingOrder);
        /*******************************************生成运单包裹和材料***************************************************/
        $this->addAllItemList($orderNo, $trackingOrder);
        //自动记录
        $this->record($trackingOrder);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //运单轨迹-运单创建
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_CREATED);
        //运单轨迹-运单加入站点
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
        //运单轨迹-运单加入取件线路
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
        //订单轨迹-订单创建
        if ($again == true) {
            OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_RESTART);
        } else {
            OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_CREATED);
        }
        return $tour;
    }

    /**
     * 修改
     * 注意：取派订单删除时，因为待取派订单只会生成取件运单,所以只有一个运单
     * @param $order
     * @throws BusinessLogicException
     */
    public function updateByOrder($order)
    {
        $dbTrackingOrder = parent::getInfoLock(['order_no' => $order['order_no']], ['*'], false, ['created_at' => 'desc']);
        if (empty($dbTrackingOrder)) return;
        $dbTrackingOrder = $dbTrackingOrder->toArray();
        //运单重新分配站点
        $trackingOrder = array_merge(Arr::only($order, $this->tOrderAndOrderSameFields), Arr::only($dbTrackingOrder, ['company_id', 'order_no', 'tracking_order_no', 'type']));
        $line = $this->fillWarehouseInfo($trackingOrder);
        //1.若运单状态是待出库或取派中,则不能修改
        //2.若运单状态是取消取派,取派完成,回收站,则无需处理
        //3.若运单状态是待分配或已分配，则能修改
        if ($this->checkIsChange($dbTrackingOrder, $trackingOrder)) {
            if (in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4])) {
                throw new BusinessLogicException('运单状态为[:status_name],不能修改派送信息', 1000, ['status_name' => $dbTrackingOrder['status_name']]);
            }
            if (in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2])) {
                list($batch, $tour) = $this->changeBatch($dbTrackingOrder, $trackingOrder, $line);
                $trackingOrder = array_merge($trackingOrder, self::getBatchTourFillData($batch, $tour));
                TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
                TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
                if ($dbTrackingOrder['execution_date'] != $trackingOrder['execution_date']) {
                    OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_UPDATE, $dbTrackingOrder);
                }
            }
        }
        //1.若运单状态为待分配，已分配，待出库，取派中，则允许修改;否则，不用修改
        if (in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4])) {
            $rowCount = parent::updateById($dbTrackingOrder['id'], $trackingOrder);
            if ($rowCount === false) {
                throw new BusinessLogicException('运单修改失败');
            }
            //删除原运单包裹和材料
            $rowCount = $this->getTrackingOrderPackageService()->delete(['tracking_order_no' => $trackingOrder['tracking_order_no']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
            $rowCount = $this->getTrackingOrderMaterialService()->delete(['tracking_order_no' => $trackingOrder['tracking_order_no']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
            $trackingOrder = array_merge($dbTrackingOrder, $trackingOrder);
            $this->addAllItemList($order['order_no'], $trackingOrder);
        }
    }

    /**
     * 新增运单获取列表
     * @param $orderNo
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    private function addAllItemList($orderNo, $trackingOrder)
    {
        $packageList = $this->getPackageService()->getList(['order_no' => $orderNo, 'status' => ['in', [1, 2]]], ['*'], false)->toArray();
        if (!empty($packageList)) {
            data_set($packageList, '*.tour_no', $trackingOrder['tour_no']);
            data_set($packageList, '*.batch_no', $trackingOrder['batch_no']);
            data_set($packageList, '*.tracking_order_no', $trackingOrder['tracking_order_no']);
            data_set($packageList, '*.type', $trackingOrder['type']);
            data_set($packageList, '*.status', $trackingOrder['status']);
            data_set($packageList, '*.execution_date', $trackingOrder['execution_date']);
            $rowCount = $this->getTrackingOrderPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        $materialList = $this->getMaterialService()->getList(['order_no' => $orderNo], ['*'], false)->toArray();
        if (!empty($materialList)) {
            data_set($materialList, '*.tour_no', $trackingOrder['tour_no']);
            data_set($materialList, '*.batch_no', $trackingOrder['batch_no']);
            data_set($materialList, '*.tracking_order_no', $trackingOrder['tracking_order_no']);
            data_set($materialList, '*.type', $trackingOrder['type']);
            data_set($materialList, '*.execution_date', $trackingOrder['execution_date']);
            $rowCount = $this->getTrackingOrderMaterialService()->insertAll($materialList);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
    }


    /**
     * 继续派送(再次取派)
     * @param $dbOrder
     * @param $order
     * @param $trackingOrderType
     * @return bool
     * @throws BusinessLogicException
     */
    public function storeAgain($dbOrder, $order, $trackingOrderType)
    {
        if (in_array($dbOrder['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_2])) {
            $address = Arr::only($order, ['place_country', 'place_fullname', 'place_phone', 'place_province', 'place_post_code', 'place_house_number', 'place_city', 'place_district', 'place_street', 'place_address', 'place_lat', 'place_lon']);
        } else {
            if ($trackingOrderType == BaseConstService::TRACKING_ORDER_TYPE_1) {
                $address = Arr::only($order, ['place_country', 'place_fullname', 'place_phone', 'place_province', 'place_post_code', 'place_house_number', 'place_city', 'place_district', 'place_street', 'place_address', 'place_lat', 'place_lon']);
            } else {
                $address = [
                    'place_country' => $order['second_place_country'], 'place_fullname' => $order['second_place_fullname'],
                    'place_province' => $order['second_province'] ?? '', 'place_district' => $order['second_district'] ?? '',
                    'place_phone' => $order['second_place_phone'], 'place_post_code' => $order['second_place_post_code'],
                    'place_house_number' => $order['second_place_house_number'], 'place_city' => $order['second_place_city'],
                    'place_street' => $order['second_place_street'], 'place_address' => $order['second_place_address'],
                    'place_lat' => $order['second_place_lat'], 'place_lon' => $order['second_place_lon']
                ];
            }
        }
        $trackingOrder = array_merge($address, ['type' => $trackingOrderType, 'execution_date' => $order['execution_date']]);
        $trackingOrder = array_merge(Arr::only($dbOrder, $this->tOrderAndOrderSameFields), $trackingOrder);
        $tour = $this->store($trackingOrder, $dbOrder['order_no'], true);
        $this->stockUpdate($order);
        return $tour;
    }

    /**
     * 更新库存信息（已超期包裹）
     * @param $order
     * @throws BusinessLogicException
     */
    public function stockUpdate($order)
    {
        $expiredStockList = $this->getStockService()->getList(['order_no' => $order['order_no'], 'expiration_status' => BaseConstService::EXPIRATION_STATUS_2], ['*'], false);
        if (!empty($expiredStockList)) {
            $trackingOrder = $this->getInfo(['order_no' => $order['order_no']], ['*'], false, ['id' => 'desc']);
            if (empty($trackingOrder)) {
                throw new BusinessLogicException('运单不存在');
            }
            $this->getStockService()->update(['order_no' => $trackingOrder['order_no'], 'expiration_status' => BaseConstService::EXPIRATION_STATUS_2], ['tracking_order_no' => $trackingOrder['tracking_order_no'],
                'line_name' => $trackingOrder['line_name'], 'expiration_status' => BaseConstService::EXPIRATION_STATUS_3]);
        }
    }

    /**
     * 通过订单号删除运单
     * 注意：取派订单删除时，因为待取派订单只会生成取件运单,所以只有一个运单
     * @param $orderNo
     * @throws BusinessLogicException
     */
    public function destroyByOrderNo($orderNo)
    {
        $dbTrackingOrder = parent::getInfoLock(['order_no' => $orderNo], ['*'], false);
        if (empty($dbTrackingOrder)) return;
        $dbTrackingOrder = $dbTrackingOrder->toArray();
        //若运单状态不是待分配或已分配或待出库状态,则不能修改
        if (!in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3])) {
            throw new BusinessLogicException('运单状态为[:status_name],不能操作', 1000, ['status_name' => $dbTrackingOrder['status_name']]);
        }
        //站点移除订单
        if (!empty($dbTrackingOrder['batch_no'])) {
            //移除运单
            $this->getBatchService()->removeTrackingOrder($dbTrackingOrder);
            //重新统计站点金额
            !empty($dbTrackingOrder['batch_no']) && $this->getBatchService()->reCountAmountByNo($dbTrackingOrder['batch_no']);
            //重新统计取件线路金额
            !empty($dbTrackingOrder['tour_no']) && $this->getTourService()->reCountAmountByNo($dbTrackingOrder['tour_no']);
        }
        $rowCount = parent::update(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['batch_no' => '', 'tour_no' => '', 'line_id' => null, 'line_name' => '', 'status' => BaseConstService::TRACKING_ORDER_STATUS_7]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //删除运单包裹
        $rowCount = $this->getTrackingOrderPackageService()->update(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['batch_no' => '', 'tour_no' => '', 'status' => BaseConstService::TRACKING_ORDER_STATUS_7]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //删除运单材料
        $rowCount = $this->getTrackingOrderMaterialService()->update(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['batch_no' => '', 'tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        OrderTrailService::orderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::ORDER_TRAIL_DELETE);
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_DELETE);
    }


    /**
     * 判断是否需要更换站点
     * @param $dbTrackingOrder
     * @param $trackingOrder
     * @return bool
     */
    private function checkIsChange($dbTrackingOrder, $trackingOrder)
    {
        $fields = ['execution_date', 'place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street'];
        $newDbOrder = Arr::only($dbTrackingOrder, $fields);
        $newOrder = Arr::only($trackingOrder, $fields);
        return empty(array_diff($newDbOrder, $newOrder)) ? false : true;
    }


    /**
     * 运单更换站点
     * @param $dbTrackingOrder
     * @param $trackingOrder
     * @param $line
     * @param $batchNo
     * @param $tour
     * @param $isAddOrder
     * @param $isFillTrackingOrder
     * @return array
     * @throws BusinessLogicException
     */
    private function changeBatch($dbTrackingOrder, $trackingOrder, $line, $batchNo = null, $tour = [], $isAddOrder = false, $isFillTrackingOrder = false)
    {
        //站点移除运单,添加新的运单
        if (!empty($dbTrackingOrder['batch_no'])) {
            $this->getBatchService()->removeTrackingOrder($dbTrackingOrder);
            //重新统计站点金额
            $this->getBatchService()->reCountAmountByNo($dbTrackingOrder['batch_no']);
            //重新统计取件线路金额
            !empty($dbTrackingOrder['tour_no']) && $this->getTourService()->reCountAmountByNo($dbTrackingOrder['tour_no']);
        }
        list($batch, $tour) = $this->getBatchService()->join($trackingOrder, $line, $batchNo, $tour, $isAddOrder);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        if ($isFillTrackingOrder === true) {
            $this->fillBatchTourInfo($trackingOrder, $batch, $tour, true);
            ($dbTrackingOrder['batch_no'] != $batch['batch_no']) && TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
            ($dbTrackingOrder['tour_no'] != $tour['tour_no']) && TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
            ($dbTrackingOrder['execution_date'] != $tour['execution_date']) && OrderTrailService::orderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_UPDATE, $dbTrackingOrder);
        }
        return [$batch, $tour];
    }


    /**
     * 填充运单信息至订单
     * @param $orderNo
     * @param $trackingOrder
     * @params $fillMaterial
     * @throws BusinessLogicException
     */
    public function fillToOrder($orderNo, $trackingOrder, $fillMaterial = true)
    {
        if ($fillMaterial == true) {
            $rowCount = $this->getMaterialService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getOrderService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 填充运单数据
     * @param $trackingOrder
     * @param $batch
     * @param $tour
     * @param boolean $isUpdateOrder
     * @return array
     * @throws BusinessLogicException
     */
    public function fillBatchTourInfo($trackingOrder, $batch, $tour, $isUpdateOrder = false)
    {
        $data = self::getBatchTourFillData($batch, $tour);
        $rowCount = parent::updateById($trackingOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //更新运单包裹
        $rowCount = $this->getTrackingOrderPackageService()->update(['tracking_order_no' => $trackingOrder['tracking_order_no']], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no'], 'status' => $data['status'], 'execution_date' => $data['execution_date']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //更新运单材料
        $rowCount = $this->getTrackingOrderMaterialService()->update(['tracking_order_no' => $trackingOrder['tracking_order_no']], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no'], 'execution_date' => $data['execution_date']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        $trackingOrder = array_merge($trackingOrder, $data);
        ($isUpdateOrder == true) && $this->getOrderService()->updateByTrackingOrder($trackingOrder);
        return $trackingOrder;
    }

    /**
     * 获取运单填充数据
     * @param $batch
     * @param $tour
     * @return array
     */
    private static function getBatchTourFillData($batch, $tour)
    {
        return [
            'execution_date' => $batch['execution_date'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'driver_phone' => $tour['driver_phone'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::TRACKING_ORDER_STATUS_1
        ];
    }


    /**
     * 从站点中移除运单
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $dbTrackingOrder = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2]);
        if (empty($dbTrackingOrder['batch_no'])) {
            return;
        }
        //运单移除站点和取件线路信息
        $rowCount = parent::updateById($id, ['line_id' => null, 'line_name' => '', 'tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点线路信息
        $rowCount = $this->getTrackingOrderMaterialService()->update(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //包裹移除站点线路信息
        $rowCount = $this->getTrackingOrderPackageService()->update(['tracking_order_no' => $dbTrackingOrder['tracking_order_no']], ['tour_no' => '', 'batch_no' => '', 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        $this->getBatchService()->removeTrackingOrder($dbTrackingOrder);
        //重新统计站点金额
        !empty($dbTrackingOrder['batch_no']) && $this->getBatchService()->reCountAmountByNo($dbTrackingOrder['batch_no']);
        //重新统计取件线路金额
        !empty($dbTrackingOrder['tour_no']) && $this->getTourService()->reCountAmountByNo($dbTrackingOrder['tour_no']);

        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_BATCH, $dbTrackingOrder);
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR, $dbTrackingOrder);
    }

    /**
     * 批量运单从站点移除
     * @param $idList
     * @throws BusinessLogicException
     */
    public function removeListFromBatch($idList)
    {
        $idList = explode_id_string($idList);
        $dbTrackingOrderList = parent::getList(['id' => ['in', $idList]], ['*'], false)->toArray();
        if (empty($dbTrackingOrderList)) {
            throw new BusinessLogicException('所有运单的当前状态不能操作，只允许待分配或已分配状态的运单操作');
        }
        $statusList = [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2];
        $dbTrackingOrderList = Arr::where($dbTrackingOrderList, function ($order) use ($statusList) {
            if (!in_array($order['status'], $statusList)) {
                throw new BusinessLogicException('运单[:order_no]的当前状态不能操作,只允许待分配或已分配状态的运单操作', 1000, ['order_no' => $order['order_no']]);
            }
            return !empty($order['batch_no']);
        });
        $trackingOrderNoList = array_column($dbTrackingOrderList, 'order_no');
        $where = ['order_no' => ['in', $trackingOrderNoList]];
        //运单移除站点和取件线路信息
        $rowCount = parent::update($where, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除取件线路信息
        $rowCount = $this->getTrackingOrderMaterialService()->update($where, ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //包裹移除取件线路信息
        $rowCount = $this->getTrackingOrderPackageService()->update($where, ['tour_no' => '', 'batch_no' => '', 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        foreach ($dbTrackingOrderList as $dbTrackingOrder) {
            $this->getBatchService()->removeTrackingOrder($dbTrackingOrder);
            //重新统计站点金额
            !empty($dbTrackingOrder['batch_no']) && $this->getBatchService()->reCountAmountByNo($dbTrackingOrder['batch_no']);
            //重新统计取件线路金额
            !empty($dbTrackingOrder['tour_no']) && $this->getTourService()->reCountAmountByNo($dbTrackingOrder['tour_no']);
        }
        TrackingOrderTrailService::storeAllByTrackingOrderList($dbTrackingOrderList, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_BATCH);
    }


    /**
     * 通过订单获得可选日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getAbleDateList($id)
    {
//        $expired = BaseConstService::NO;
        if ($id < 0) {
            $dbOrder = $this->getOrderService()->getInfo(['id' => abs($id)], ['*'], false);
            if (empty($dbOrder)) {
                throw new BusinessLogicException('数据不存在');
            }
            $params = $dbOrder->toArray();
            $params['type'] = $this->getTypeByOrderType($dbOrder['type']);
            if ($dbOrder['type'] == BaseConstService::ORDER_TYPE_3) {
                $address = [
                    'place_country' => $dbOrder['second_place_country'], 'place_fullname' => $dbOrder['second_place_fullname'],
                    'place_phone' => $dbOrder['second_place_phone'], 'place_post_code' => $dbOrder['second_place_post_code'],
                    'place_house_number' => $dbOrder['second_place_house_number'], 'place_city' => $dbOrder['second_place_city'],
                    'place_street' => $dbOrder['second_place_street'], 'place_address' => $dbOrder['second_place_address'],
                    'place_lat' => $dbOrder['second_place_lat'], 'place_lon' => $dbOrder['second_place_lon'],
                    'execution_date' => $dbOrder['second_execution_date'],
                    'type' => BaseConstService::TRACKING_ORDER_TYPE_2
                ];
                $params = array_merge($params, $address);
            }
//            $params = Arr::only($params, ['company_id', 'merchant_id', 'execution_date', 'place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat', 'type']);
//            $trackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
//            $params['type'] = $this->getTypeByOrderType($dbOrder['type']);
//            if (!empty($trackingOrderPackageList)) {
//                foreach ($trackingOrderPackageList as $k => $v) {
//                    if ($v['expiration_status'] === BaseConstService::EXPIRATION_STATUS_2) {
//                        $expired = BaseConstService::YES;
//                    }
//                    break;
//                }
//            }

        } else {
            $params = parent::getInfo(['id' => $id], ['*'], false);
            if (empty($params)) {
                throw new BusinessLogicException('数据不存在');
            }
        }
        Log::info('可选日期参数', collect($params)->toArray());
        $data = $this->getLineService()->getScheduleList($params);
        return $data;
    }


    /**
     * 获取可分配的站点列表
     * @param $id
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getAbleBatchList($id, $params)
    {
        $dbTrackingOrder = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbTrackingOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        $dbTrackingOrder = $dbTrackingOrder->toArray();
        $dbTrackingOrder['execution_date'] = $params['execution_date'];
        return $this->getBatchService()->getListByTrackingOrder($dbTrackingOrder);
    }

    /**
     * 将运单分配至站点
     * @param $id
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function assignToBatch($id, $params)
    {
        $dbTrackingOrder = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2]);
        if (!empty($params['batch_no']) && ($dbTrackingOrder['batch_no'] == $params['batch_no'])) {
            return 'true';
        }
        $trackingOrder = array_merge($dbTrackingOrder, ['execution_date' => $params['execution_date']]);
        $line = $this->fillWarehouseInfo($trackingOrder, BaseConstService::YES);
        /***********************************************1.修改*********************************************************/
        $rowCount = parent::updateById($id, $trackingOrder);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        /********************************************2.改变站点****************************************************/
        $this->changeBatch(Arr::only($dbTrackingOrder, ['batch_no', 'tour_no', 'type', 'execution_date']), $trackingOrder, $line, $params['batch_no'] ?? null, null, false, true);
        return 'true';
    }


    /**
     * 批量运单分配至指定取件线路
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignListTour($params)
    {
        list($trackingOrderIdList, $tourNo) = [$params['tracking_order_id_list'], $params['tour_no']];
        /******************************************1.获取数据**********************************************************/
        //获取取件线路信息
        $tour = $this->getTourService()->getInfoLock(['tour_no' => $tourNo, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路当前状态不能操作');
        }
        $tourModel = $tour;
        $tour = $tour->toArray();
        //获取线路信息
        list($dbTrackingOrderList, $lineId) = $this->getAddTrackingOrderList($trackingOrderIdList, $tour['execution_date']);
        //判断当前线路ID是否是取件线路ID
//        if (intval($lineId) != intval($tour['line_id'])) {
//            throw new BusinessLogicException('当前线路已更换，请刷新');
//        }
        $dbTrackingOrderList = Arr::where($dbTrackingOrderList, function ($trackingOrder) use ($tourNo) {
            return (($trackingOrder['tour_no'] != $tourNo) && ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1));
        });
        //获取线路信息
        $line = $this->getLineService()->getInfoLock(['id' => $tour['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('线路不存在');
        }
        $line = $line->toArray();
        /*******************************************2.验证*************************************************************/
        $count = 0;
        if ($tour['status'] == BaseConstService::TOUR_STATUS_4) {
            $materialCount = $this->getMaterialService()->count(['order_no' => ['in', array_column($dbTrackingOrderList, 'order_no')]]);
            if ($materialCount > 0) {
                throw new BusinessLogicException('当前取件线路正在派送中，取件运单加单不能包含材料');
            }
        }
//        if ($tour['expect_pickup_quantity'] + $count > $line['pickup_max_count']) {
//            throw new BusinessLogicException('取件数量超过线路取件运单最大值');
//        }
        //获取取件线路的站点列表
        $dbBatchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['id', 'tour_no', 'place_fullname'], false);
        /*******************************************2.加单*************************************************************/
        foreach ($dbTrackingOrderList as $dbTrackingOrder) {
            $trackingOrder = $dbTrackingOrder;
            data_set($trackingOrder, 'execution_date', $tour['execution_date']);
            list($batch, $tour) = $this->changeBatch($dbTrackingOrder, $trackingOrder, $line, null, $tour, true, true);
        }
        //更新站点顺序
        $this->getBatchService()->updateBatchSort($tour['tour_no']);
        //加单推送
        //dispatch(new AddOrderPush($dbTrackingOrderList, $tour['driver_id']));
        if (!empty($tour['driver_id']) && (in_array($tour['status'], [BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]))) {
            Notification::send(Driver::findOrFail($tour['driver_id']), new TourAddTrackingOrder($dbTrackingOrderList, $dbBatchList->toArray(), $tour));
        }
    }


    /**
     * 获取可加单的运单列表
     * @param $trackingOrderIdList
     * @param $executionDate
     * @return array
     * @throws BusinessLogicException
     */
    public function getAddTrackingOrderList($trackingOrderIdList, $executionDate)
    {
        $lineId = null;
        $dbTrackingOrderList = parent::getList(['id' => ['in', explode(',', $trackingOrderIdList)], 'type' => BaseConstService::TRACKING_ORDER_TYPE_1], ['*'], false)->toArray();
        $statusList = [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2];
        foreach ($dbTrackingOrderList as $dbTrackingOrder) {
            if (!in_array($dbTrackingOrder['status'], $statusList)) {
                throw new BusinessLogicException('运单[:order_no]的不是待分配或已分配状态，不能操作', 1000, ['order_no' => $dbTrackingOrder['order_no']]);
            }
            if ($dbTrackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
                throw new BusinessLogicException('派件运单不允许加单');
            }
//            $dbLineId = $this->getLineService()->getLineIdByInfo($dbTrackingOrder, $executionDate);
//            if (empty($dbLineId) || (!empty($lineId) && ($lineId != $dbLineId))) {
//                return [$dbTrackingOrderList, 0];
//            }
//            $lineId = $dbLineId;
        }
        return [$dbTrackingOrderList, $lineId];
    }

    /**
     * 运单导出
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingOrderExport()
    {
        $this->query->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7);
        $dbTrackingOrderList = $this->setFilter()->getList();
//        if ($dbTrackingOrderList->hasMorePages()) {
//            throw new BusinessLogicException('数据量过大无法导出，运单数不得超过200');
//        }
        if ($dbTrackingOrderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchant = $this->getMerchantService()->getList(['id' => ['in', $dbTrackingOrderList->pluck('merchant_id')->toArray()]]);
        if ($merchant->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $this->getTourService()->getList(['tour_no' => ['in', $dbTrackingOrderList->pluck('tour_no')->toArray()]]);
        $dbTrackingOrderList = collect($dbTrackingOrderList)->toArray();
        foreach ($dbTrackingOrderList as $k => $v) {
            $dbTrackingOrderList[$k]['merchant_name'] = $v['merchant_id_name'];
            $dbTrackingOrderList[$k]['line_name'] = $tour->where('tour_no', $v['tour_no'])->first()['line_name'] ?? '';
            $dbTrackingOrderList[$k]['status'] = $v['status_name'];
            $dbTrackingOrderList[$k]['type'] = $v['type_name'];
            $dbTrackingOrderList[$k]['created_at'] = $v['created_at'];
        }
        foreach ($dbTrackingOrderList as $v) {
            $cellData[] = array_only_fields_sort($v, $this->headings);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $dir = 'trackingOrderOut';
        $name = date('YmdHis') . auth()->user()->id;
        return $this->excelExport($name, $this->headings, $cellData, $dir);
    }

    /**
     * 填充运单数据
     * @param Order $order
     * @return array
     */
    private function fillData(Order $order)
    {
        $order = $order->getAttributes();
        $trackingOrderParams = Arr::only($order, $this->tOrderAndOrderSameFields);
        //运单若是取件类型或者是取派类型,则运单类型为取件类型;否则为派件类型
        $trackingOrderParams['type'] = $this->getTypeByOrderType($order['type']);
        return $trackingOrderParams;
    }

    /**
     * 获取运单类型
     * @param $orderType
     * @return int
     */
    public function getTypeByOrderType($orderType)
    {
        return (intval($orderType) == BaseConstService::ORDER_TYPE_2) ? BaseConstService::TRACKING_ORDER_TYPE_2 : BaseConstService::TRACKING_ORDER_TYPE_1;
    }


    /**
     * 填充网点信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    public function fillWarehouseInfo(&$params, $merchantAlone = BaseConstService::NO)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id']], ['*'], false);
        if ($merchant['below_warehouse'] == BaseConstService::YES) {
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $merchant['warehouse_id']], ['*'], false);
        } else {
            //获取线路
            $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone);
            //获取网点
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        }
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        //填充发件人信息
        $params = array_merge($params, [
            'warehouse_fullname' => $warehouse['fullname'],
            'warehouse_phone' => $warehouse['phone'],
            'warehouse_country' => $warehouse['country'],
            'warehouse_post_code' => $warehouse['post_code'],
            'warehouse_house_number' => $warehouse['house_number'],
            'warehouse_city' => $warehouse['city'],
            'warehouse_street' => $warehouse['street'],
            'warehouse_address' => $warehouse['address'],
            'warehouse_lon' => $warehouse['lon'],
            'warehouse_lat' => $warehouse['lat']
        ]);
        return $line;
    }

    /**
     * 获取运单
     * @param $orderNo
     * @return array
     */
    public function getTrackingOrderByOrderNo($orderNo)
    {
        $trackingOrder = parent::getInfo(['order_no' => $orderNo], ['*'], false, ['created_at' => 'desc']);
        return !empty($trackingOrder) ? $trackingOrder->toArray() : [];
    }

    /**
     * 自动记录
     * @param $params
     * @throws BusinessLogicException
     */
    public function record($params)
    {
        //记录地址
        $info = $this->getAddressService()->getInfoByUnique($params);
        if (empty($info)) {
            $this->getAddressService()->create($params);
        }
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function changeOutStatus($params)
    {
        $ids = explode(',', $params['id_list']);
        for ($i = 0; $i < count($ids); $i++) {
            $rowCount[$i] = parent::updateById($ids[$i], ['out_status' => $params['out_status']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('修改失败，请重新操作');
            }
        }
    }

    /**
     * 终止运单
     * @param $trackingOrderNo
     * @throws BusinessLogicException
     */
    public function end($trackingOrderNo)
    {
        if (empty($trackingOrderNo)) return;
        $trackingOrder = parent::getInfo(['tracking_order_no' => $trackingOrderNo], ['*'], false);
        if (empty($trackingOrder)) return;
        $expiredTrackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => $trackingOrderNo, 'expiration_status' => BaseConstService::EXPIRATION_STATUS_2], ['*'], false);
        if (empty($expiredTrackingOrderPackageList) && in_array($trackingOrder->status, [BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_7])) {
            throw new BusinessLogicException('当前运单正在[:status_name]', 1000, ['status_name' => $trackingOrder->status_name]);
        }
        if (in_array($trackingOrder->status, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2])) {
            $this->removeFromBatch($trackingOrder->id);
            $rowCount = parent::update(['tracking_order_no' => $trackingOrder->tracking_order_no], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
            $rowCount = $this->getTrackingOrderPackageService()->update(['tracking_order_no' => $trackingOrder->tracking_order_no], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        TrackingOrderTrailService::trackingOrderStatusChangeCreateTrail($trackingOrder->toArray(), BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);
    }
}
