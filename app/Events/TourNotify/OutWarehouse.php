<?php

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
use App\Services\BaseConstService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * 司机出库事件
 */
class OutWarehouse extends ATourNotify
{
    /**
     * OutWarehouse constructor.
     * @param $tour
     * @param $batchList
     * @param $trackingOrderList
     */
    public function __construct($tour, $batchList, $trackingOrderList)
    {
        parent::__construct($tour, [], $batchList, $trackingOrderList);
    }


    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_OUT_WAREHOUSE;
    }

    public function getDataList(): array
    {
        $this->fillTrackingOrderList(true);
        $trackingOrderList = collect($this->trackingOrderList)->groupBy(function ($trackingOrder) {
            return $trackingOrder['merchant_id'] . '-' . $trackingOrder['batch_no'];
        })->toArray();
        $batchList = collect($this->batchList)->keyBy('batch_no')->toArray();
        $newBatchList = [];
        foreach ($trackingOrderList as $merchantIdBatchNo => $merchantBatchList) {
            list($merchantId, $batchNo) = explode('-', $merchantIdBatchNo);
            if (!empty($batchList[$batchNo])) {
                $newBatchList[$merchantId][] = array_merge($batchList[$batchNo], ['merchant_id' => $merchantId, 'tracking_order_list' => $merchantBatchList]);
            } else {
                $newBatchList[$merchantId][] = array_merge(Arr::only($merchantBatchList[0], self::$batchFields), ['merchant_id' => $merchantId, 'tracking_order_list' => $merchantBatchList]);
            }
        }
        $tourList = [];
        foreach ($newBatchList as $merchantId => $merchantBatchList) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch_list' => $merchantBatchList]);
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
     * 获取第三方对接内容
     * @param bool $status
     * @param string $msg
     * @return string
     */
    public function getThirdPartyContent(bool $status, string $msg = ''): string
    {
        if ($status == true) {
            $content = '出库推送成功';
        } else {
            $content = '出库推送失败,失败原因:' . $msg;
        }
        return $content;
    }
}
