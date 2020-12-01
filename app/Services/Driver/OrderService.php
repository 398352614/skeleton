<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:58
 */

namespace App\Services\Driver;

use App\Events\OrderExecutionDateUpdated;
use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;

class OrderService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 订单出库
     * @param $tourNo
     * @param $action
     * @throws BusinessLogicException
     */
    public function outWarehouse($tourNo)
    {
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tourNo, 'status' => [BaseConstService::TRACKING_ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_6]], ['*'], false)->toArray();
        //订单处理
        $ingOrderNoList = $cancelOrderList = [];
        foreach ($trackingOrderList as $trackingOrder) {
            if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_4) {
                $ingOrderNoList[] = $trackingOrder['order_no'];
                continue;
            }
            if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
                $cancelOrderList[] = $trackingOrder['order_no'];
                continue;
            }
        }
        $rowCount = parent::update(['order_no' => ['in', $ingOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = parent::update(['order_no' => ['in', $cancelOrderList]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //包裹处理
        $ingPackageNoList = $cancelPackageNoList = [];
        $trackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', array_column($trackingOrderList, 'tracking_order_no')]], ['*'], false)->toArray();
        foreach ($trackingOrderPackageList as $trackingOrderPackage) {
            if ($trackingOrderPackage['status'] == BaseConstService::TRACKING_ORDER_STATUS_4) {
                $ingPackageNoList[] = $trackingOrderPackage['express_first_no'];
                continue;
            }
            if ($trackingOrderPackage['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
                $cancelPackageNoList[] = $trackingOrderPackage['express_first_no'];
                continue;
            }
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $ingPackageNoList]], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $cancelPackageNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 取消取派
     * @param $batchNo
     * @throws BusinessLogicException
     */
    public function batchCancel($batchNo)
    {
        //订单处理
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batchNo, 'status' => BaseConstService::TRACKING_ORDER_STATUS_6], ['*'], false)->toArray();
        $rowCount = parent::update(['order_no' => ['in', array_column($trackingOrderList, 'order_no')]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //包裹处理
        $trackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', array_column($trackingOrderList, 'tracking_order_no')]], ['*'], false)->toArray();
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', array_column($trackingOrderPackageList, 'express_first_no')]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 签收
     * @param $batchNo
     * @throws BusinessLogicException
     */
    public function batchSign($batchNo)
    {
        //订单处理
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batchNo, 'status' => BaseConstService::TRACKING_ORDER_STATUS_5], ['*'], false)->toArray();
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        $trackingOrderNoList = array_column($trackingOrderList, 'order_no');
        $orderList = parent::getList(['order_no' => ['in', $trackingOrderNoList]], ['*'], false)->toArray();
        $signOrderNoList = [];
        foreach ($orderList as $order) {
            if (($order['type'] == BaseConstService::ORDER_TYPE_3) && ($trackingOrderList[$order['order_no']]['type']) == BaseConstService::TRACKING_ORDER_TYPE_1) {
                continue;
            }
            $signOrderNoList[] = $order['order_no'];
        }
        $rowCount = parent::update(['order_no' => ['in', $signOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //包裹处理
        $signPackageNoList = $cancelPackageNoList = [];
        $trackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', $trackingOrderNoList]], ['*'], false)->toArray();
        $packageList = $this->getPackageService()->getList(['express_first_no' => ['in', array_column($trackingOrderPackageList, 'express_first_no')]], ['*'], false)->toArray();
        $packageList = array_create_index($packageList, 'order_no');
        foreach ($trackingOrderPackageList as $trackingOrderPackage) {
            if ($trackingOrderPackage['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
                $cancelPackageNoList[] = $trackingOrderPackage['express_first_no'];
                continue;
            }
            if (($packageList[$trackingOrderPackage['order_no']]['type'] == BaseConstService::ORDER_TYPE_3) && ($trackingOrderPackage['type'] == BaseConstService::TRACKING_ORDER_TYPE_1)) {
                continue;
            }
            $signPackageNoList = $trackingOrderPackage['express_first_no'];
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $signPackageNoList]], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $cancelPackageNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }


    /**
     * 反写运单信息至订单
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function updateByTrackingOrder($trackingOrder)
    {
        $dbOrder = $this->getInfoOfStatus(['order_no' => $trackingOrder['order_no']], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('订单[:order_no]不存在', 1000, ['order_no' => $trackingOrder['order_no']]);
        }
        //若是取派中的派件,修改第二日期;否则，修改第一日期
        if (($dbOrder['type'] == BaseConstService::ORDER_TYPE_3) && ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2)) {
            $data = ['second_execution_date' => $trackingOrder['execution_date']];
        } else {
            $data = ['execution_date' => $trackingOrder['execution_date']];
        }
        //若运单为取派中，则订单修改为取派中;其他，不处理
        if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_4) {
            $data['status'] = BaseConstService::ORDER_STATUS_2;
        }
        $rowCount = parent::updateById($dbOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $executionDate = !empty($data['execution_date']) ? $data['execution_date'] : $dbOrder['execution_date'];
        $secondExecutionDate = !empty($data['second_execution_date']) ? $data['second_execution_date'] : $dbOrder['second_execution_date'];
        $status = !empty($data['status']) ? $data['status'] : $dbOrder['status'];
        event(new OrderExecutionDateUpdated($dbOrder['order_no'], $dbOrder['out_order_no'] ?? '', $executionDate, $secondExecutionDate, $status, '', ['tour_no' => '', 'line_id' => '', 'line_name' => '']));
    }


    /**
     * 获取运单类型
     * @param $order
     * @param TrackingOrder
     * @return int|null
     */
    public function getTrackingOrderType($order, TrackingOrder $trackingOrder = null)
    {
        (empty($trackingOrder)) && $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], [' * '], false, ['created_at' => 'desc']);
        //1.运单不存在,直接获取运单类型
        if (empty($trackingOrder)) {
            return $this->getTrackingOrderService()->getTypeByOrderType($order['type']);
        }
        $trackingOrder = $trackingOrder->toArray();
        //2.当运单存在时，若运单不是取派完成或者取派失败,则表示已存在取派的运单
        if (!in_array($trackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6])) {
            return null;
        }
        //3.当运单存在时，若运单为取派失败,则新增取派失败的运单
        if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
            return $trackingOrder['type'];
        }
        //4.当运单存在时，当运单为取派完成，若订单为取件或派件,则表示不需要新增运单
        if (in_array($order['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_2])) {
            return null;
        }
        //5.当运单存在时，当运单为取派完成，当订单为取派件,若运单为派件类型，则表示不需要新增运单
        if ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
            return null;
        }
        //6.当运单存在时，当运单为取派完成，当订单为取派件,若运单为取件类型，则表示新增派件派件运单
        return BaseConstService::TRACKING_ORDER_TYPE_2;
    }
}
