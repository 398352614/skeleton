<?php
/**
 * 下一个站点事件
 * User: long
 * Date: 2020/4/2
 * Time: 14:45
 */

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
use App\Models\Batch;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

class NextBatch extends ATourNotify
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
        $trackingOrderList = collect($this->trackingOrderList)->groupBy('merchant_id')->toArray();
        //获取站点列表
        $this->batch = Batch::query()->where('batch_no', $this->batch['batch_no'])->first(self::$batchFields)->toArray();
        $batchList = [];
        //更新预计耗时
        if (!empty($this->batch['expect_arrive_time'])) {
            $expectTime = strtotime($this->batch['expect_arrive_time']) - time();
            $this->batch['expect_time'] = $expectTime > 0 ? $expectTime : 0;
        } else {
            $this->batch['expect_time'] = 0;
        }
        foreach ($trackingOrderList as $merchantId => $merchantTrackingOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'tracking_order_list' => $merchantTrackingOrderList]);
        }
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch' => $batch]);
        }
        Log::info('data_list_1',$tourList);
        return $tourList;
    }

    public function getDataList2(): array
    {
        $dataList = $this->fillTrackingOrderList2();
        //更新预计耗时
        foreach ($dataList as $merchantId => $merchantTrackingOrderList) {
            foreach ($merchantTrackingOrderList as $k => $v) {
                if (!empty($v['expect_arrive_time'])) {
                    $expectTime = strtotime($v['expect_arrive_time']) - time();
                    $trackingOrderList[$merchantId][$k]['expect_time'] = $expectTime > 0 ? $expectTime : 0;
                } else {
                    $trackingOrderList[$merchantId][$k]['expect_time'] = 0;
                }
            }
        }
        Log::info('data_list_2', $dataList);
        return $dataList;
    }

    public function getDataList3()
    {
        $dataList = parent::simplify($this->fillTrackingOrderList2());
        Log::info('data_list_3', $dataList);
        return $dataList;
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
