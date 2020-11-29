<?php

/**
 * 运单服务
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/20
 * Time: 16:39
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\TrackingOrderInfoResource;
use App\Http\Resources\Api\Admin\TrackingOrderResource;
use App\Jobs\AddOrderPush;
use App\Models\Order;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use App\Services\TrackingOrderTrailService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\ExportTrait;
use Illuminate\Support\Arr;

class TrackingOrderService extends BaseService
{
    use ExportTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'tour_no,tracking_order_no,order_no,out_order_no,out_user_id' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'tour_no' => ['like', 'tour_no'],
        'batch_no' => ['like', 'batch_no'],
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
        'place_post_code',
        'place_house_number',
        'place_city',
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
        'driver_fullname',
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
        $list = parent::getPageList();
        $tourNoList = collect($list)->where('tour_no', '<>', '')->pluck('tour_no')->toArray();
        $tour = $this->getTourService()->getList(['tour_no' => ['in', $tourNoList]], ['*'], false);
        foreach ($list as $k => $v) {
            $list[$k]['line_id'] = $tour->where('tour_no', $v['tour_no'])->first()['line_id'] ?? '';
            $list[$k]['line_name'] = $tour->where('tour_no', $v['tour_no'])->first()['line_name'] ?? '';
        }
        foreach ($list as &$trackingOrder) {
            $batchException = $this->getBatchExceptionService()->getInfo(['batch_no' => $trackingOrder['batch_no']], ['id', 'batch_no', 'stage'], false, ['created_at' => 'desc']);
            $trackingOrder['exception_stage_name'] = !empty($batchException) ? ConstTranslateTrait::batchExceptionStageList($batchException['stage']) : __('正常');
        }
        return $list;
    }

    public function show($id)
    {
        $dbTrackingOrder = parent::getInfo(['id' => $id], ['*'], true);
        return !empty($dbTrackingOrder) ? $dbTrackingOrder : [];
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
    private function store($params, $orderNo, $again = false)
    {
        //填充仓库信息
        $line = $this->fillWarehouseInfo($params, BaseConstService::YES);
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
        $trackingOrder = $this->fillBatchTourInfo($trackingOrder, $batch, $tour);
        /*******************************************填充运单信息至订单***************************************************/
        $this->fillToOrder($orderNo, $trackingOrder);
        //自动记录
        $this->record($trackingOrder);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //运单轨迹-运单创建
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_CREATED);
        //运单轨迹-运单加入站点
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
        //运单轨迹-运单加入取件线路
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
        //订单轨迹-订单创建
        OrderTrailService::OrderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_CREATED);
        if ($again == true) {
            OrderTrailService::OrderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_RESTART);
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
                TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
                TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
                if ($dbTrackingOrder['execution_date'] != $trackingOrder['execution_date']) {
                    OrderTrailService::OrderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_UPDATE, $dbTrackingOrder);
                }
            }
        }
        //1.若运单状态为待分配，已分配，待出库，取派中，则允许修改;否则，不用修改
        if (in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4])) {
            $rowCount = parent::updateById($dbTrackingOrder['id'], $trackingOrder);
            if ($rowCount === false) {
                throw new BusinessLogicException('运单修改失败');
            }
        }
    }

    /**
     * 再次取派
     * @param $dbOrder
     * @param $order
     * @param $trackingOrderType
     * @return bool
     * @throws BusinessLogicException
     */
    public function storeAgain($dbOrder, $order, $trackingOrderType)
    {
        if (in_array($dbOrder['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_2])) {
            $address = Arr::only($order, ['place_country', 'place_fullname', 'place_phone', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lat', 'place_lon']);
        } else {
            if ($trackingOrderType == BaseConstService::TRACKING_ORDER_TYPE_1) {
                $address = Arr::only($order, ['place_country', 'place_fullname', 'place_phone', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lat', 'place_lon']);
            } else {
                $address = [
                    'place_country' => 'second_place_country', 'place_fullname' => 'second_place_fullname',
                    'place_phone' => 'second_place_phone', 'place_post_code' => 'second_place_post_code',
                    'place_house_number' => 'second_place_house_number', 'place_city' => 'second_place_city',
                    'place_street' => 'second_place_street', 'place_address' => 'second_place_address',
                    'place_lat' => 'second_place_lat', 'place_lon' => 'second_place_lon'
                ];
            }
        }
        $trackingOrder = array_merge($address, ['type' => $trackingOrderType, 'execution_date' => $order['execution_date']]);
        $trackingOrder = array_merge(Arr::only($dbOrder, $this->tOrderAndOrderSameFields), $trackingOrder);
        return $this->store($trackingOrder, $dbOrder['order_no'], true);
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
        //若运单状态不是待分配或已分配状态,则不能修改
        if (!in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2])) {
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
            //清除材料的取件信息
            $rowCount = $this->getMaterialService()->update(['order_no' => $orderNo], ['batch_no' => '', 'tour_no' => '']);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败,请重新操作');
            }
        }
        $rowCount = parent::delete(['order_no' => $orderNo]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        OrderTrailService::OrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::ORDER_TRAIL_DELETE);
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
            ($dbTrackingOrder['batch_no'] != $batch['batch_no']) && TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
            ($dbTrackingOrder['tour_no'] != $tour['tour_no']) && TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
            ($dbTrackingOrder['execution_date'] != $tour['execution_date']) && OrderTrailService::OrderStatusChangeCreateTrail($trackingOrder, BaseConstService::ORDER_TRAIL_UPDATE, $tour);
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
            $rowCount = $this->getMaterialService()->update(['order_no' => $orderNo], ['batch_no' => $trackingOrder['batch_no'], 'tour_no' => $trackingOrder['tour_no'], 'tracking_order_no' => $trackingOrder['tracking_order_no']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $orderNo], ['tracking_order_no' => $trackingOrder['tracking_order_no']]);
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
        $rowCount = parent::updateById($id, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        $this->getBatchService()->removeTrackingOrder($dbTrackingOrder);
        //重新统计站点金额
        !empty($dbTrackingOrder['batch_no']) && $this->getBatchService()->reCountAmountByNo($dbTrackingOrder['batch_no']);
        //重新统计取件线路金额
        !empty($dbTrackingOrder['tour_no']) && $this->getTourService()->reCountAmountByNo($dbTrackingOrder['tour_no']);

        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_BATCH, $dbTrackingOrder);
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($dbTrackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR, $dbTrackingOrder);
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
        $params = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($params)) {
            throw new BusinessLogicException('数据不存在');
        }
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
     * @throws \WebSocket\BadOpcodeException
     */
    public function assignListTour($params)
    {
        list($trackingOrderIdList, $tourNo) = [$params['id_list'], $params['tour_no']];
        /******************************************1.获取数据**********************************************************/
        //获取取件线路信息
        $tour = $this->getTourService()->getInfoLock(['tour_no' => $tourNo, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路当前状态不能操作');
        }
        $tour = $tour->toArray();
        //获取线路信息
        list($dbTrackingOrderList, $lineId) = $this->getAddTrackingOrderList($trackingOrderIdList, $tour['execution_date']);
        //判断当前线路ID是否是取件线路ID
        if (intval($lineId) != intval($tour['line_id'])) {
            throw new BusinessLogicException('当前线路已更换，请刷新');
        }
        $dbTrackingOrderList = Arr::where($dbTrackingOrderList, function ($trackingOrder) use ($tourNo) {
            return (($trackingOrder['tour_no'] != $tourNo) && ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1));
        });
        //获取线路信息
        $line = $this->getLineService()->getInfoLock(['id' => $lineId], ['*'], false);
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
        if ($tour['expect_pickup_quantity'] + $count > $line['pickup_max_count']) {
            throw new BusinessLogicException('取件数量超过线路取件运单最大值');
        }
        /*******************************************2.加单*************************************************************/
        foreach ($dbTrackingOrderList as $dbTrackingOrder) {
            $trackingOrder = $dbTrackingOrder;
            data_set($trackingOrder, 'execution_date', $tour['execution_date']);
            list($batch, $tour) = $this->changeBatch($dbTrackingOrder, $trackingOrder, $line, null, $tour, true, true);
        }

        //加单推送
        dispatch(new AddOrderPush($dbTrackingOrderList, $tour['driver_id']));
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
            $dbLineId = $this->getLineService()->getLineIdByInfo($dbTrackingOrder, $executionDate);
            if (empty($dbLineId) || (!empty($lineId) && ($lineId != $dbLineId))) {
                return [$dbTrackingOrderList, 0];
            }
            $lineId = $dbLineId;
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
        $dbTrackingOrderList = $this->getPageList();
        if ($dbTrackingOrderList->hasMorePages()) {
            throw new BusinessLogicException('数据量过大无法导出，运单数不得超过200');
        }
        if ($dbTrackingOrderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $merchant = $this->getMerchantService()->getList(['id' => ['in', $dbTrackingOrderList->pluck('merchant_id')->toArray()]]);
        if ($merchant->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $this->getTourService()->getList(['tour_no' => ['in', $dbTrackingOrderList->pluck('tour_no')->toArray()]]);
        $dbTrackingOrderList = $dbTrackingOrderList->toArray(request());
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
     * 填充仓库信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    private function fillWarehouseInfo(&$params, $merchantAlone = BaseConstService::NO)
    {
        //获取线路
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone);
        //获取仓库
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在');
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
}
