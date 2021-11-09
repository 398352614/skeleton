<?php
/**
 * 到达站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:33
 */

namespace App\Events\TourNotify2;


use App\Events\Interfaces\ATourNotify2;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;

class ArrivedBatch extends ATourNotify2
{

    public $tour;

    public $batch;

    public $trackingOrderList;

    public function __construct($tour, $batch, $trackingOrderList = [])
    {
        $trackingOrderList = !empty($trackingOrderList) ? $trackingOrderList : $this->getTrackingOrderList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $trackingOrderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ARRIVED_BATCH;
    }

    public function getDataList(): array
    {
        $this->fillTrackingOrderList();
        return $this->trackingOrderList;
    }

    /**
     * 获取订单列表
     *
     * @param $batchNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getTrackingOrderList($batchNo)
    {
        $trackingOrderList = TrackingOrder::query()->where('batch_no', $batchNo)->where('status', BaseConstService::TRACKING_ORDER_STATUS_4)->get();
        return $trackingOrderList->toArray();
    }
}
