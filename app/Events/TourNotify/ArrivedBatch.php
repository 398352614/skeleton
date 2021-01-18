<?php
/**
 * 到达站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:33
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ATourNotify;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

class ArrivedBatch extends ATourNotify
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
        $trackingOrderList = collect($this->trackingOrderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
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

    /**
     * 详情模式
     * @return array
     */
    public function getDataList2(): array
    {
        $dataList = $this->fillTrackingOrderList2();
        Log::info('data_list_2', $dataList);
        return $dataList;
    }

    /**
     * 简略模式
     * @return mixed
     */
    public function getDataList3()
    {
        $dataList = parent::simplify($this->fillTrackingOrderList2());
        Log::info('data_list_3',$dataList);
        return $dataList;
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
