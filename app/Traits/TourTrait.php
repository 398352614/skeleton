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
        empty($cancelOrderList) && OrderTrailService::storeAllByOrderList($cancelOrderList, BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        //派送订单
        $orderList = Order::query()->select(['*'])->whereNotIn('order_no', array_column($cancelOrderList, 'order_no'))->get()->toArray();
        !empty($orderList) && OrderTrailService::storeAllByOrderList($orderList, BaseConstService::ORDER_TRAIL_DELIVERING);
        //触发司机出库1
        event(new OutWarehouse($tour));
        //触发司机出库事件2
        data_set($cancelOrderList, '*.status', 'cancel');
        data_set($orderList, '*.status', 'delivering');
        $newOrderList = array_merge($cancelOrderList, $orderList);
        $batchList = Batch::query()->where('tour_no', $tour['tour_no'])->where('status', BaseConstService::BATCH_DELIVERING)->get()->toArray();
        event(new \App\Events\TourNotify\OutWarehouse($tour, $batchList, $newOrderList));

    }

    public static function afterBatchArrived($tour, $batch)
    {
        //触发司机到达站点
        event(new BatchArrived($batch));

        //触发司机到达站点2
        event(new \App\Events\TourNotify\ArrivedBatch($tour, $batch));
    }


    public static function afterBatchCancel($tour, $batch)
    {
        OrderTrailService::storeByBatchNo($batch['batch_no'], BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        self::dealBatchEvent($tour, $batch);

        event(new \App\Events\TourNotify\CancelBatch($tour, $batch));
    }


    public static function afterBatchSign($tour, $batch)
    {
        OrderTrailService::storeByBatchNo($batch['batch_no'], BaseConstService::ORDER_TRAIL_DELIVERED);
        self::dealBatchEvent($tour, $batch);
        event(new \App\Events\TourNotify\AssignBatch($tour, $batch));
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
        $location = ['latitude' => $batch['receiver_lat'], 'longitude' => $batch['receiver_lon']];
        $nextBatch = Batch::query()->where('status', BaseConstService::BATCH_DELIVERING)->orderBy('sort_id', 'asc')->first(['batch_no']);
        if (!empty($nextBatch)) {
            event(new AfterDriverLocationUpdated(Tour::where('tour_no', $tour['tour_no'])->first(), $nextBatch->batch_no, $location, true));
        }
    }

}