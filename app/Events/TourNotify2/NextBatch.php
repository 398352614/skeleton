<?php
/**
 * 下一个站点事件
 * User: long
 * Date: 2020/4/2
 * Time: 14:45
 */

namespace App\Events\TourNotify2;

use App\Events\Interfaces\ATourNotify2;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;

class NextBatch extends ATourNotify2
{
    public function __construct($tour, $batch, $trackingOrderList = [])
    {
        $trackingOrderList = !empty($trackingOrderList) ? $trackingOrderList : $this->getTrackingOrderList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $trackingOrderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_NEXT_BATCH;
    }

    public function getDataList(): array
    {
        $this->fillTrackingOrderList();
        //更新预计耗时
        foreach ($this->trackingOrderList as $merchantId => $trackingOrderList) {
            foreach ($trackingOrderList as $k => $v) {
                if (!empty($v['expect_arrive_time'])) {
                    $expectTime = strtotime($v['expect_arrive_time']) - time();
                    $this->trackingOrderList[$merchantId][$k]['expect_time'] = $expectTime > 0 ? $expectTime : 0;
                } else {
                    $this->trackingOrderList[$merchantId][$k]['expect_time'] = 0;
                }
            }
        }
        return $this->trackingOrderList;
    }

    /**
     * 获取运单列表
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
