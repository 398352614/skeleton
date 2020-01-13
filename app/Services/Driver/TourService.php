<?php

/**
 * 取件线路 服务
 * User: long
 * Date: 2019/12/30
 * Time: 11:55
 */

namespace App\Services\Driver;

use App\Events\AfterTourUpdated;
use App\Events\Order\Delivered;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourBatchResource;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use App\Services\Traits\TourRedisLockTrait;

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
    use TourRedisLockTrait;

    public function __construct(Tour $tour)
    {
        $this->request = request();
        $this->formData = request()->all();
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
        $params = Arr::add($params, 'begin_time', now());
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
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('tour_no', $tour['tour_no'])->get(), BaseConstService::ORDER_TRAIL_DELIVERING);
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
            'expect_arrive_time', 'actual_arrive_time', 'expect_pickup_quantity', 'actual_pickup_quantity', 'expect_pie_quantity', 'actual_pie_quantity','receiver_lon','recerver_lat'
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


    /**
     * 站点到达 主要处理到达时间和里程
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchArrive($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $now = now();
        $actualTime = strtotime($now) - strtotime($tour['begin_time']);
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['actual_arrive_time' => $now, 'actual_time' => $actualTime]);
        if ($rowCount === false) {
            throw new BusinessLogicException('更新到达时间失败,请重新操作');
        }
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
        $orderList['pickup'] = $orderList['1'] ?? [];
        $orderList['pie'] = $orderList['2'] ?? [];
        unset($orderList['1'], $orderList['2']);
        $batch['order_list'] = $orderList;
        $batch['sticker_amount'] = BaseConstService::STICKER_AMOUNT;
        $batch['tour_id'] = $tour['id'];
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
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('batch_no', $batch['batch_no'])->get(), BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
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
        //获取客户取派订单列表
        if (empty($params['item_list'])) {
            throw new BusinessLogicException('单号信息不能为空');
        }
        $orderList = collect(json_decode($params['item_list'], true))->unique('order_id')->keyBy('order_id')->toArray();
        $orderIdList = array_keys($orderList);
        //获取当前站点下的所有订单
        $pickupCount = $pieCount = 0;
        $totalStickerAmount = 0.00;
        $dbOrderCollection = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'order_no', 'batch_no', 'type'], false);
        $dbOrderList = $dbOrderCollection->toArray();
        OrderTrailService::OrderStatusChangeUseOrderCollection($dbOrderCollection, BaseConstService::ORDER_TRAIL_DELIVERED);
        foreach ($dbOrderList as $dbOrder) {
            $stickerAmount = 0.00;
            //判断是否签收
            if (in_array(intval($dbOrder['id']), $orderIdList)) {
                $status = BaseConstService::ORDER_STATUS_5;
                //判断取件或派件
                if (intval($dbOrder['type']) === BaseConstService::ORDER_TYPE_1) {
                    $pickupCount += 1;
                    $totalStickerAmount += BaseConstService::STICKER_AMOUNT;
                    $stickerAmount = BaseConstService::STICKER_AMOUNT;
                } else {
                    $pieCount += 1;
                }
            } else {
                $status = BaseConstService::ORDER_STATUS_6;
            }
            $rowCount = $this->getOrderService()->updateById($dbOrder['id'], ['status' => $status, 'sticker_no' => $orderList[$dbOrder['id']]['sticker_no'] ?? '', 'sticker_amount' => $stickerAmount]);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        //站点处理
        $batchData = [
            'status' => BaseConstService::BATCH_CHECKOUT,
            'actual_pickup_quantity' => $pickupCount,
            'actual_pie_quantity' => $pieCount,
            'sticker_amount' => $totalStickerAmount,
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
            'sticker_amount' => $tour['sticker_amount'] + $totalStickerAmount,
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
        $data = Arr::add($data, 'end_time', now());
        $rowCount = parent::updateById($tour['id'], Arr::add($data, 'status', BaseConstService::TOUR_STATUS_5));
        if ($rowCount === false) {
            throw new BusinessLogicException('司机入库失败,请重新操作');
        }
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

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();

        throw_if(
            $tour->batchs->count() != $this->formData['batch_ids'],
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
        while (time_nanosleep(0, 500000000) === true) {
            app('log')->info('每 0.5 秒查询一次修改是否完成');
            //锁不存在代表更新完成
            if (!$this->getTourLock($tour->tour_no)) {
                return '修改线路成功';
            }
        }
        app('log')->error('进入此处代表修改线路失败');
        self::setTourLock($this->formData['tour_no'], 0); // 取消锁
        throw new BusinessLogicException('修改线路失败');
    }

    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
     */
    public function getNextBatchAndUpdateIndex($batchIds): Batch
    {
        $first = false;
        foreach ($batchIds as $key => $batchId) {
            if (!$first) {
                $batch = Batch::where('id', $batchId)->whereIn('status', [BaseConstService::BATCH_DELIVERING, BaseConstService::BATCH_ASSIGNED])->first();
                if ($batch) {
                    $first = true; // 找到了下一个目的地
                }
            }
        }
        if ($batch) {
            return $batch;
        }

        throw new BusinessLogicException('未查找到下一个目的地');
    }
}
