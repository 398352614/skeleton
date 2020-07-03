<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/3/30
 * Time: 15:59
 */

namespace App\Traits;

use App\Events\AfterDriverLocationUpdated;
use App\Events\TourDriver\BatchArrived;
use App\Events\TourDriver\BatchDepart;
use App\Events\TourDriver\BackWarehouse;
use App\Events\TourDriver\OutWarehouse;
use App\Events\TourNotify\CancelBatch;
use App\Events\TourNotify\NextBatch;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;

trait TourTrait
{
    public static function afterOutWarehouse($tour, $cancelOrderList)
    {
        //取消派送订单，取派失败
        !empty($cancelOrderList) && OrderTrailService::storeAllByOrderList($cancelOrderList, BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        //派送订单
        $orderList = Order::query()->select(['*'])->where('tour_no', $tour['tour_no'])->whereNotIn('order_no', array_column($cancelOrderList, 'order_no'))->get()->toArray();
        !empty($orderList) && OrderTrailService::storeAllByOrderList($orderList, BaseConstService::ORDER_TRAIL_DELIVERING);
        //触发司机出库1
        event(new OutWarehouse($tour));
        //智能调度-之后再进行出库通知
        dispatch(new \App\Jobs\OutWarehouse($tour['tour_no'], array_merge($cancelOrderList, $orderList)));
    }

    public static function afterBatchArrived($tour, $batch)
    {
        //触发司机到达站点
        event(new BatchArrived($batch));

        //触发司机到达站点2
        event(new \App\Events\TourNotify\ArrivedBatch($tour, $batch));
    }


    public static function afterBatchCancel($tour, $batch, $orderList)
    {
        data_set($orderList, '*.status', BaseConstService::ORDER_STATUS_6);
        OrderTrailService::storeAllByOrderList($orderList, BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        self::dealBatchEvent($tour, $batch);

        event(new \App\Events\TourNotify\CancelBatch($tour, $batch, $orderList));

        //通知下一个站点事件
        $nextBatch = self::getNextBatch($tour['tour_no']);
        if (!empty($nextBatch)) {
            event(new NextBatch($tour, $nextBatch->toArray()));
        }
    }


    public static function afterBatchSign($tour, $batch)
    {
        $orderList = Order::query()->where('batch_no', $batch['batch_no'])->whereIn('status', [BaseConstService::ORDER_STATUS_5, BaseConstService::ORDER_STATUS_6])->get()->toArray();
        $groupOrderList = array_create_group_index($orderList, 'status');
        //若存在签收成功的订单列表,则记录
        if (!empty($groupOrderList[BaseConstService::ORDER_STATUS_5])) {
            OrderTrailService::storeAllByOrderList($groupOrderList[BaseConstService::ORDER_STATUS_5], BaseConstService::ORDER_TRAIL_DELIVERED);
        }
        //若存在签收失败的订单列表,则记录
        if (!empty($groupOrderList[BaseConstService::ORDER_STATUS_6])) {
            OrderTrailService::storeAllByOrderList($groupOrderList[BaseConstService::ORDER_STATUS_6], BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        }
        unset($groupOrderList);
        self::dealBatchEvent($tour, $batch);
        event(new \App\Events\TourNotify\AssignBatch($tour, $batch, $orderList));

        //通知下一个站点事件
        $nextBatch = self::getNextBatch($tour['tour_no']);
        if (!empty($nextBatch)) {
            event(new NextBatch($tour, $nextBatch->toArray()));
        }
    }

    public static function afterBackWarehouse($tour)
    {
        //触发返回仓库
        event(new BackWarehouse($tour));

        event(new \App\Events\TourNotify\BackWarehouse($tour));
    }


    /**
     * 处理站点签收/取消 事件
     * @param $tour
     * @param $batch
     */
    private static function dealBatchEvent($tour, $batch)
    {
        //触发司机离开站点
        event(new BatchDepart($batch));
        //触发站点预计到达时间
        $nextBatch = self::getNextBatch($tour['tour_no']);
        $location = ['latitude' => $batch['receiver_lat'], 'longitude' => $batch['receiver_lon']];
        if (!empty($nextBatch)) {
            event(new AfterDriverLocationUpdated(Tour::where('tour_no', $tour['tour_no'])->first(), $nextBatch->batch_no, $location, true));
        }
    }

    public static function getNextBatch($tourNo)
    {
        return $nextBatch = Batch::query()->where('tour_no', $tourNo)->where('status', BaseConstService::BATCH_DELIVERING)->orderBy('sort_id', 'asc')->first(['batch_no', 'expect_arrive_time', 'expect_time', 'expect_distance']);
    }

}
