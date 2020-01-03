<?php

/**
 * 取件线路 服务
 * User: long
 * Date: 2019/12/30
 * Time: 11:55
 */

namespace App\Services\Driver;

use App\Events\Order\Delivered;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourBatchResource;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;

/**
 * Class TourService
 * @package App\Services\Driver
 * 取件线路流程
 * 1.开始装货 取件线路状态 已分配-待出库
 * 2.出仓库   取件线路状态 待出库-取派中
 * 3.到达站点 取件线路状态 取派中  有三种情况:1-签收 2-异常上报 3-取消取派
 * 4.回仓库   取件线路状态 取派中-已完成
 */
class TourService extends BaseService
{
    public function __construct(Tour $tour)
    {
        $this->request = request();
        $this->model = $tour;
        $this->query = $this->model::query();
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
     * 站点 服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
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
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
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
     * 锁定-开始装货
     * @param $id
     * @throws BusinessLogicException
     */
    public function lock($id)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_2) {
            throw new BusinessLogicException('取件线路当前状态不允许装货');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('取件线路未分配车辆,请先分配车辆');
        }
        //取件线路 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取件线路锁定失败,请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::BATCH_WAIT_OUT]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点锁定失败,请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单锁定失败,请重新操作');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('tour_no', $tour['tour_no'])->get(), BaseConstService::ORDER_TRAIL_LOCK);
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
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败,请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单取消锁定失败,请重新操作');
        }
    }


    /**
     * 备注
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function remark($id, $params)
    {
        $rowCount = parent::updateById($id, ['remark' => $params['remark']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('备注失败,请重新操作');
        }
    }

    /**
     * 修改车辆
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function changeCar($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不允许分配车辆');
        }
        $tour = $tour->toArray();
        //查看当前车辆是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'car_id' => $params['car_id'], 'execution_date' => $tour['execution_date'], 'driver_id' => ['<>', null]], ['*'], false);
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
        $rowCount = $this->updateTourAll($tour, ['car_id' => $car['id'], 'car_no' => $car['car_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆分配失败,请重新操作');
        }
    }

    /**
     * 修改取件线路-站点-订单
     * @param $tour
     * @param $data
     * @return bool
     */
    private function updateTourAll($tour, $data)
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
     * 出库
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function outWarehouse($id, $params)
    {
        $tour = $this->checkOutWarehouse($id);
        $params = Arr::only($params, ['begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic']);
        $params = Arr::add($params, 'status', BaseConstService::TOUR_STATUS_4);
        //取件线路更换状态
        $rowCount = parent::updateById($id, $params);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //站点更换状态
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::BATCH_DELIVERING]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //订单更换状态
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no']], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('tour_no', $tour->tour_no)->get(), BaseConstService::ORDER_TRAIL_DELIVERING);
    }

    /**
     * 验证-出库
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    private function checkOutWarehouse($id)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_3) {
            throw new BusinessLogicException('取件线路当前状态不允许出库');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('当前未分配车辆,请先分配车辆');
        }
        return $tour;
    }


    /**
     * 取件线路中的站点列表
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchList($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $batchFields = [
            'id', 'batch_no', 'tour_no', 'status',
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address',
            'expect_arrive_time', 'actual_arrive_time', 'expect_pickup_quantity', 'actual_pickup_quantity', 'expect_pie_quantity', 'actual_pie_quantity'
        ];
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], $batchFields, false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        $tour['batch_count'] = count($batchList);
        $tour['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_CHECKOUT]);
        $tour['batch_list'] = $batchList;
        return TourBatchResource::make($tour)->toArray(request());
    }

    /**
     * 达到时-获取站点的订单列表
     * @param $id
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchOrderList($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $orderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'type', 'batch_no', 'order_no', 'status'], false)->toArray();
        $orderList = array_create_group_index($orderList, 'type');
        $batch['order_list'] = $orderList;
        return $orderList;
    }


    //站点到达 主要处理到达时间和里程
    public function batchArrive($id, $params)
    {
    }


    /**
     * 到达后-站点详情
     * @param $id
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchInfo($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $orderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'order_no', 'type', 'batch_no', 'status'], false);
        $orderList = collect($orderList)->map(function ($order, $key) {
            /**@var Order $order */
            return collect(Arr::add($order->toArray(), 'status_name', $order->status_name));
        });
        $orderList = array_create_group_index($orderList, 'type');
        $batch['order_list'] = $orderList;
        return $batch;
    }

    /**
     * 站点异常上报
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchException($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许上报异常');
        }
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能上报异常');
        }
        //生成站点异常
        $batchExceptionNo = $this->getOrderNoRuleService()->createBatchExceptionNo();
        $data = [
            'batch_exception_no' => $batchExceptionNo,
            'batch_no' => $batch['batch_no'],
            'receiver' => $batch['receiver'],
            'source' => __('司机来源'),
            'stage' => $params['stage'],
            'type' => $params['type'],
            'remark' => $params['exception_remark'],
            'picture' => $params['picture'],
            'driver_name' => auth()->user()->last_name . auth()->user()->first_name
        ];
        $rowCount = $this->getBatchExceptionService()->create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败,请重新操作');
        }
        //站点异常
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败,请重新操作');
        }
        //订单异常
        $rowCount = $this->getOrderService()->update(['batch_no' => $batch['batch_no']], ['exception_label' => BaseConstService::BATCH_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败,请重新操作');
        }
    }


    /**
     * 站点取消取派
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchCancel($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许站点取消取派');
        }
        //异常站点和取派中的站点都可以取消取派
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能取消取派');
        }
        //站点取消取派
        $data = Arr::only($params, ['cancel_type', 'cancel_remark', 'cancel_picture']);
        $rowCount = $this->getBatchService()->updateById($batch['id'], Arr::add($data, 'status', BaseConstService::BATCH_CANCEL));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败,请重新操作');
        }
        //订单取消取派
        $rowCount = $this->getOrderService()->update(['batch_no' => $batch['batch_no']], Arr::add($data, 'status', BaseConstService::ORDER_STATUS_6));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败,请重新操作');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('batch_no', $batch['batch_no'])->get(), BaseConstService::ORDER_TRAIL_CANNEL_DELIVER);
    }

    /**
     * 站点签收
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchSign($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许站点签收');
        }
        //异常站点和取派中的站点都可以签收
        if (intval($batch['status'] !== BaseConstService::BATCH_DELIVERING)) {
            throw new BusinessLogicException('站点当前状态不能签收');
        }
        //获取当前站点下的所有订单
        $pickupCount = $pieCount = 0;
        $dbOrderCollection = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'order_no', 'batch_no', 'type'], false);
        $dbOrderList = $dbOrderCollection->toArray();
        OrderTrailService::OrderStatusChangeUseOrderCollection($dbOrderCollection, BaseConstService::ORDER_TRAIL_DELIVERED);
        $cancelOrderIdList = array_unique(explode(',', $params['cancel_order_id_list']));
        foreach ($dbOrderList as $dbOrder) {
            if (in_array(intval($dbOrder['id']), $cancelOrderIdList)) {
                $status = BaseConstService::ORDER_STATUS_6;
            } else {
                if (intval($dbOrder['type']) === BaseConstService::ORDER_TYPE_1) {
                    $pickupCount += 1;
                } else {
                    $pieCount += 1;
                }
                $status = BaseConstService::ORDER_STATUS_5;
            }
            $rowCount = $this->getOrderService()->updateById($dbOrder['id'], ['status' => $status]);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        //站点处理
        $batchData = [
            'status' => BaseConstService::BATCH_CHECKOUT,
            'actual_pickup_quantity' => $pickupCount,
            'actual_pie_quantity' => $pieCount,
            'order_amount' => $params['order_amount'],
            'signature' => $params['signature'],
            'pay_type' => $params['pay_type'],
            'pay_picture' => $params['pay_picture']
        ];
        $rowCount = $this->getBatchService()->updateById($batch['id'], $batchData);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        //更新取件线路信息
        $tourData = [
            'actual_pickup_quantity' => intval($tour['actual_pickup_quantity']) + $pickupCount,
            'actual_pie_quantity' => intval($tour['actual_pie_quantity']) + $pieCount,
            'order_amount' => $tour['order_amount'] + $params['order_amount'],
            'replace_amount' => $tour['replace_amount'] + $batch['replace_amount'],
            'settlement_amount' => $tour['settlement_amount'] + $batch['replace_amount'],
        ];
        $rowCount = parent::updateById($id, $tourData);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
    }


    /**
     * 验证-站点
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function checkBatch($id, $params)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfo(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前取件线路');
        }

        return [$tour, $batch];
    }

    /**
     * 验证-站点并锁定数据
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function checkBatchLock($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfoLock(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前取件线路');
        }
        return [$tour, $batch];
    }

    /**
     * 司机入库
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function inWarehouse($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许回仓库');
        }
        $batchCount = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => ['not in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]]);
        if ($batchCount !== 0) {
            throw new BusinessLogicException('当前取件线路还有未完成站点,请先处理');
        }
        $data = Arr::only($params, ['end_signature', 'end_signature_remark']);
        $rowCount = parent::updateById($tour['id'], Arr::add($data, 'status', BaseConstService::TOUR_STATUS_5));
        if ($rowCount === false) {
            throw new BusinessLogicException('司机入库失败,请重新操作');
        }
    }
}
