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
use App\Http\Resources\TrackingOrderResource;
use App\Models\Order;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\TrackingOrderTrailService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;

class TrackingOrderService extends BaseService
{
    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no,out_user_id' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'source' => ['=', 'source'],
        'tour_no' => ['like', 'tour_no'],
        'batch_no' => ['like', 'batch_no'],
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(TrackingOrder $trackingOrder)
    {
        parent::__construct($trackingOrder, TrackingOrderResource::class, null);
    }

    /**
     * 线路邮编范围 服务
     * @return LineRangeService
     */
    private function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 线路区域范围 服务
     * @return LineAreaService
     */
    private function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
    }

    /**
     * 线路服务
     * @return LineService
     */
    private function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
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
     * 仓库 服务
     * @return WareHouseService
     */
    private function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 站点异常 服务
     * @return BatchExceptionService
     */
    private function getBatchExceptionService()
    {
        return self::getInstance(BatchExceptionService::class);
    }

    /**
     * 发件人地址 服务
     * @return SenderAddressService
     */
    private function getSenderAddressService()
    {
        return self::getInstance(SenderAddressService::class);
    }

    /**
     * 收人地址 服务
     * @return ReceiverAddressService
     */
    private function getReceiverAddressService()
    {
        return self::getInstance(ReceiverAddressService::class);
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
        foreach ($list as &$order) {
            $batchException = $this->getBatchExceptionService()->getInfo(['batch_no' => $order['batch_no']], ['id', 'batch_no', 'stage'], false, ['created_at' => 'desc']);
            $order['exception_stage_name'] = !empty($batchException) ? ConstTranslateTrait::batchExceptionStageList($batchException['stage']) : __('正常');
        }
        return $list;
    }

    /**
     * 订单新增
     * @param $order
     * @return bool
     * @throws BusinessLogicException
     */
    public function storeByOrder(Order $order)
    {
        $params = $this->fillData($order);
        //填充发件人信息
        $line = $this->fillSender($params, BaseConstService::YES);
        /**********************************************生成运单********************************************************/
        $trackingOrder = parent::create($params);
        if ($trackingOrder == false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $trackingOrder = $trackingOrder->getAttributes();
        /*****************************************运单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($trackingOrder, $line);
        //自动记录
        $this->record($trackingOrder);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //订单轨迹-订单创建
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_CREATED);
        //订单轨迹-订单加入站点
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH, $batch);
        //订单轨迹-订单加入取件线路
        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($trackingOrder, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR, $tour);
        return 'true';
    }

    private function fillData(Order $order)
    {
        $order = $order->getAttributes();
        $trackingOrderParams = Arr::only($order, ['merchant_id', 'out_user_id', 'out_order_no', 'order_no', 'execution_date', 'receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address']);
        //订单若是取件类型或者是取派类型,则运单类型为取件类型;否则为派件类型
        $trackingOrderParams['type'] = (intval($order['type']) == 2) ? 1 : 2;
        return $trackingOrderParams;
    }


    /**
     * 填充发件人信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    private function fillSender(&$params, $merchantAlone = BaseConstService::NO)
    {
        //获取线路
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::ORDER_OR_BATCH_1, $merchantAlone);
        //获取仓库
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在');
        }
        //填充发件人信息
        $params = array_merge($params, [
            'sender_fullname' => $warehouse['fullname'],
            'sender_phone' => $warehouse['phone'],
            'sender_country' => $warehouse['country'],
            'sender_post_code' => $warehouse['post_code'],
            'sender_house_number' => $warehouse['house_number'],
            'sender_city' => $warehouse['city'],
            'sender_street' => $warehouse['street'],
            'sender_address' => $warehouse['address'],
        ]);
        return $line;
    }

    /**
     * 自动记录
     * @param $params
     * @throws BusinessLogicException
     */
    public function record($params)
    {
        //记录发件人地址
        $info = $this->getSenderAddressService()->getInfoByUnique($params);
        if (empty($info)) {
            $this->getSenderAddressService()->create($params);
        }
        //记录收件人地址
        $info = $this->getReceiverAddressService()->getInfoByUnique($params);
        if (empty($info)) {
            $this->getReceiverAddressService()->create($params);
        }
    }


}
