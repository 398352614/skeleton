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
use App\Jobs\ActualOutWarehouse;
use App\Listeners\SendNotify2Merchant;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Package;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use App\Services\TrackingOrderTrailService;
use Illuminate\Support\Facades\Log;

trait TourTrait
{
    public static function afterOutWarehouse($tour, $cancelOrderList)
    {
        //取消派送订单，取派失败
        !empty($cancelOrderList) && TrackingOrderTrailService::storeAllByTrackingOrderList($cancelOrderList, BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);
        //派送订单
        $orderList = Order::query()->select(['*'])->where('tour_no', $tour['tour_no'])->whereNotIn('order_no', array_column($cancelOrderList, 'order_no'))->get()->toArray();
        !empty($orderList) && TrackingOrderTrailService::storeAllByTrackingOrderList($orderList, BaseConstService::TRACKING_ORDER_TRAIL_DELIVERING);
        //触发司机出库1
        event(new OutWarehouse($tour));
        //出库通知
        dispatch(new \App\Jobs\OutWarehouse($tour['tour_no'], array_merge($cancelOrderList, $orderList)));
    }

    public static function actualOutWarehouse($tour)
    {
        $orderList = Order::query()->select(['*'])->where('tour_no', $tour['tour_no'])->get()->toArray();
        //智能调度
        dispatch(new ActualOutWarehouse($tour['tour_no'], $orderList));
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
        data_set($orderList, '*.status', BaseConstService::TRACKING_ORDER_STATUS_6);
        data_set($batch, 'status', BaseConstService::TRACKING_ORDER_STATUS_6);
        TrackingOrderTrailService::storeAllByTrackingOrderList($orderList, BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);
        //取消取派通知
        event(new \App\Events\TourNotify\CancelBatch($tour, $batch, $orderList));
        //处理站点
        self::dealBatchEvent($tour, $batch);
    }


    public static function afterBatchSign($tour, $batch)
    {
        $orderList = Order::query()->where('batch_no', $batch['batch_no'])->whereIn('status', [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6])->get()->toArray();
        $groupOrderList = array_create_group_index($orderList, 'status');
        //若存在签收成功的订单列表,则记录
        if (!empty($groupOrderList[BaseConstService::TRACKING_ORDER_STATUS_5])) {
            TrackingOrderTrailService::storeAllByTrackingOrderList($groupOrderList[BaseConstService::TRACKING_ORDER_STATUS_5], BaseConstService::TRACKING_ORDER_TRAIL_DELIVERED);
            OrderTrailService::storeAllByOrderList($groupOrderList[BaseConstService::ORDER_STATUS_3], BaseConstService::ORDER_TRAIL_FINISH);
        }
        //若存在签收失败的订单列表,则记录
        if (!empty($groupOrderList[BaseConstService::TRACKING_ORDER_STATUS_6])) {
            TrackingOrderTrailService::storeAllByTrackingOrderList($groupOrderList[BaseConstService::TRACKING_ORDER_STATUS_6], BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);
            OrderTrailService::storeAllByOrderList($groupOrderList[BaseConstService::TRACKING_ORDER_STATUS_4], BaseConstService::ORDER_TRAIL_FAIL);
        }
        unset($groupOrderList);
        //签收通知
        event(new \App\Events\TourNotify\AssignBatch($tour, $batch, $orderList));
        //处理站点
        self::dealBatchEvent($tour, $batch);
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
        //若存在下一个站点
        $nextBatch = self::getNextBatch($tour['tour_no']);
        if (!empty($nextBatch)) {
            $location = ['latitude' => $batch['place_lat'], 'longitude' => $batch['place_lon']];
            //更新站点预计和司机位置
            event(new AfterDriverLocationUpdated(Tour::where('tour_no', $tour['tour_no'])->first(), $nextBatch->batch_no, $location, true, true));
        }
    }

    public static function getNextBatch($tourNo)
    {
        return $nextBatch = Batch::query()->where('tour_no', $tourNo)->where('status', BaseConstService::BATCH_DELIVERING)->orderBy('sort_id', 'asc')->first(['batch_no', 'expect_arrive_time', 'expect_time', 'expect_distance']);
    }

}
