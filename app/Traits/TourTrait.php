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
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use Illuminate\Support\Arr;

trait TourTrait
{
    public static function afterOutWarehouse($tour, $cancelOrderList)
    {
        //取消派送订单，取派失败
        empty($cancelOrderList) && OrderTrailService::storeAllByOrderList($cancelOrderList, BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        //派送订单
        $orderList = Order::query()->select(['id', 'company_id', 'merchant_id', 'order_no'])->whereNotIn('order_no', array_column($cancelOrderList, 'order_no'))->get()->toArray();
        !empty($orderList) && OrderTrailService::storeAllByOrderList($orderList, BaseConstService::ORDER_TRAIL_DELIVERING);

        //触发司机出库
        event(new OutWarehouse($tour));

    }

    public static function afterBatchArrived($tour, $batch)
    {
        //触发司机到达站点
        event(new BatchArrived($batch));
    }


    public static function afterBatchCancel($tour, $batch)
    {
        OrderTrailService::storeByBatchNo($batch['batch_no'], BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
        self::dealBatchEvent($tour, $batch);
    }


    public static function afterBatchSign($tour, $batch)
    {
        OrderTrailService::storeByBatchNo($batch['batch_no'], BaseConstService::ORDER_TRAIL_DELIVERED);
        self::dealBatchEvent($tour, $batch);
    }

    public static function afterBackWarehouse($tour)
    {
        //触发返回仓库
        event(new BackWarehouse($tour));
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
        $location = ['lat' => $batch['receiver_lat'], 'lon' => $batch['receiver_lon']];
        $nextBatch = Batch::query()->where('status', BaseConstService::BATCH_DELIVERING)->orderBy('sort_id', 'asc')->first(['batch_no']);
        if (!empty($nextBatch)) {
            event(new AfterDriverLocationUpdated(Tour::where('tour_no', $tour['tour_no'])->first(), $nextBatch->batch_no, $location, true));
        }
    }

}