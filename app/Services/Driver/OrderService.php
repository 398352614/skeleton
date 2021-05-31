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
use App\Traits\ConstTranslateTrait;
use Doctrine\DBAL\Driver\OCI8\Driver;

class OrderService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 订单出库
     * @param $tourNo
     * @param $newCancelTrackingOrderList
     * @throws BusinessLogicException
     */
    public function outWarehouse($tourNo, $newCancelTrackingOrderList)
    {
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tour_no' => $tourNo, 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_4]]], ['*'], false)->toArray();
        if (!empty($newCancelTrackingOrderList)) {
            $trackingOrderList = array_create_index($trackingOrderList, 'tracking_order_no');
            $newCancelTrackingOrderList = array_create_index($newCancelTrackingOrderList, 'tracking_order_no');
            $trackingOrderList = array_merge($trackingOrderList, $newCancelTrackingOrderList);
            $trackingOrderList = array_values($trackingOrderList);
            unset($newCancelTrackingOrderList);
        }
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
        //过滤取派失败订单
        $cancelOrderList = $this->filterCancelOrderNoList($cancelOrderList, $trackingOrderList);
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
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $ingPackageNoList], 'order_no' => ['in', $ingOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $cancelPackageNoList], 'order_no' => ['in', $cancelOrderList]], ['status' => BaseConstService::ORDER_STATUS_4]);
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
        $cancelOrderNoList = array_column($trackingOrderList, 'order_no');
        $cancelOrderNoList = $this->filterCancelOrderNoList($cancelOrderNoList, $trackingOrderList);
        $rowCount = parent::update(['order_no' => ['in', $cancelOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //包裹处理
        $trackingOrderPackageList = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => ['in', array_column($trackingOrderList, 'tracking_order_no')]], ['*'], false)->toArray();
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', array_column($trackingOrderPackageList, 'express_first_no')], 'order_no' => ['in', $cancelOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
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
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batchNo, 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6]]], ['*'], false)->toArray();
        $trackingOrderNoList = array_column($trackingOrderList, 'tracking_order_no');
        $orderNoList = array_column($trackingOrderList, 'order_no');
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        $orderList = parent::getList(['order_no' => ['in', $orderNoList]], ['*'], false)->toArray();
        $signOrderNoList = $cancelOrderNoList = [];
        foreach ($orderList as $order) {
            if ($trackingOrderList[$order['order_no']]['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
                $cancelOrderNoList[] = $order['order_no'];
                continue;
            }
            if (($order['type'] == BaseConstService::ORDER_TYPE_3) && ($trackingOrderList[$order['order_no']]['type']) == BaseConstService::TRACKING_ORDER_TYPE_1) {
                continue;
            }
            $signOrderNoList[] = $order['order_no'];
        }
        $rowCount = parent::update(['order_no' => ['in', $signOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $cancelOrderNoList = $this->filterCancelOrderNoList($cancelOrderNoList, $trackingOrderList);
        $rowCount = parent::update(['order_no' => ['in', $cancelOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
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
            $signPackageNoList[] = $trackingOrderPackage['express_first_no'];
        }
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $signPackageNoList], 'order_no' => ['in', $signOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_3, 'actual_quantity' => 1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //考虑订单签收，包裹取消取派的情况
        $orderNoList = array_merge($cancelOrderNoList, $signOrderNoList);
        $rowCount = $this->getPackageService()->update(['express_first_no' => ['in', $cancelPackageNoList], 'order_no' => ['in', $orderNoList]], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 过滤取派失败订单
     * @param $cancelOrderNoList
     * @param $trackingOrderList
     * @return mixed
     */
    private function filterCancelOrderNoList($cancelOrderNoList, $trackingOrderList)
    {
        if (empty($cancelOrderNoList)) return [];
        $orderList = $this->getOrderService()->getList(['order_no' => ['in', $cancelOrderNoList]], ['merchant_id'], false)->toArray();
        $merchantIdList = array_unique(array_column($orderList, 'merchant_id'));
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', $merchantIdList]], ['*'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        foreach ($cancelOrderNoList as $key => $cancelOrderNo) {
            $type = $trackingOrderList[$cancelOrderNo]['type'];
            $merchantId = $trackingOrderList[$cancelOrderNo]['merchant_id'];
            $count = $this->getTrackingOrderService()->count(['driver_id' => ['all', null], 'order_no' => $cancelOrderNo, 'type' => $type, 'status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
            $times = ($type == BaseConstService::TRACKING_ORDER_TYPE_1) ? $merchantList[$merchantId]['pickup_count'] : $merchantList[$merchantId]['pie_count'];
            $times = intval($times);
            if ($times == 0 || ($count < $times)) {
                unset($cancelOrderNoList[$key]);
            }
        }
        return $cancelOrderNoList;
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
        event(new OrderExecutionDateUpdated($dbOrder['order_no'], $dbOrder['out_order_no'] ?? '', $executionDate, $secondExecutionDate, $status, '', ['tour_no' => '', 'line_id' => $trackingOrder['line_id'] ?? '', 'line_name' => $trackingOrder['line_name'] ?? '']));
    }


    /**
     * 获取运单类型
     * @param $order
     * @param TrackingOrder
     * @return int|null
     */
    public function getTrackingOrderType($order, TrackingOrder $trackingOrder = null)
    {
        (empty($trackingOrder)) && $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], ['*'], false, ['created_at' => 'desc']);
        //1.运单不存在,直接获取运单类型
        if (empty($trackingOrder)) {
            return $this->getTrackingOrderService()->getTypeByOrderType($order['type']);
        }
        $trackingOrder = $trackingOrder->toArray();
        //2.当运单存在时，若运单不是取派完成或者取派失败,则表示已存在取派的运单
        if ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 && !in_array($trackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6])) {
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
//        if ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
//            return null;
//        }
        //6.当运单存在时，当运单为取派完成，当订单为取派件,若运单为取件类型，则表示新增派件派件运单
        return BaseConstService::TRACKING_ORDER_TYPE_2;
    }

    /**
     * 同步订单状态
     * @param $idList
     * @param bool $stockException
     */
    public function synchronizeStatusList($idList, $stockException = false)
    {
        //获取订单列表
        $idList = explode_id_string($idList);
        $orderList = parent::getList(['id' => ['in', $idList]], ['*'], false)->toArray();
        $orderNoList = array_column($orderList, 'order_no');
        //获取运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['order_no' => ['in', $orderNoList]], ['id', 'order_no', 'out_order_no', 'batch_no', 'tour_no', 'type', 'status'], false, [], ['id' => 'asc'])->toArray();
        //这里只会得到订单的最新运单
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        //获取包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount', 'is_auth', 'auth_fullname', 'auth_birth_date'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        //获取材料列表
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'], false)->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        //获取站点列表
        $batchNoList = array_column($trackingOrderList, 'batch_no');
        $batchList = $this->getBatchService()->getList(['batch_no' => ['in', $batchNoList]], ['*'], false)->toArray();
        $batchList = array_create_index($batchList, 'batch_no');
        //获取取件线路列表
        $tourNoList = array_column($trackingOrderList, 'tour_no');
        $tourList = $this->getTourService()->getList(['tour_no' => ['in', $tourNoList]], ['*'], false)->toArray();
        $tourList = array_create_index($tourList, 'tour_no');
        //组合数据
        foreach ($orderList as &$order) {
            $orderNo = $order['order_no'];
            $order['package_list'] = $packageList[$orderNo] ?? [];
            $order['material_list'] = $materialList[$orderNo] ?? [];
            $order['delivery_count'] = (floatval($order['delivery_amount']) == 0) ? 0 : 1;
            if (empty($trackingOrderList[$orderNo])) {
                $order['cancel_remark'] = $order['signature'] = $order['line_name'] = $order['driver_name'] = $order['driver_phone'] = $order['car_no'] = '';
                $order['tracking_order_type'] = $order['tracking_order_status'] = $order['pay_type'] = $order['line_id'] = $order['driver_id'] = $order['car_id'] = $order['tracking_order_type_name'] = null;
                continue;
            }
            $order['tracking_type'] = $order['tracking_order_type'] = $trackingOrderList[$orderNo]['type'];
            $order['tracking_order_status'] = $trackingOrderList[$orderNo]['status'];
            if ($stockException == true) {
                $order['tracking_type'] = $order['tracking_order_type'] = BaseConstService::TRACKING_ORDER_TYPE_1;
                $order['tracking_order_status'] = BaseConstService::TRACKING_ORDER_STATUS_5;
            }
            $order['tracking_order_type_name'] = ConstTranslateTrait::trackingOrderTypeList($order['tracking_order_type']);
            $order['tracking_order_status_name'] = ConstTranslateTrait::trackingOrderStatusList($order['tracking_order_status']);
            $order['cancel_remark'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['cancel_remark'] ?? '';
            $order['signature'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['signature'] ?? '';
            $order['pay_type'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['pay_type'] ?? null;
            $order['line_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['line_id'] ?? null;
            $order['line_name'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['line_name'] ?? '';
            $order['driver_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_id'] ?? null;
            $order['driver_name'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_name'] ?? '';
            $order['driver_phone'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_phone'] ?? '';
            $order['car_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['car_id'] ?? null;
            $order['car_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['car_no'] ?? '';
            $order['batch_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['batch_no'] ?? '';
            $order['tour_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['tour_no'] ?? '';
        }
        dispatch(new \App\Jobs\SyncOrderStatus($orderList));
    }
}
