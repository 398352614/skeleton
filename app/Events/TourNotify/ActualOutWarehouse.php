<?php

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Package;
use App\Models\Tour;
use App\Services\BaseConstService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * 司机出库事件
 */
class ActualOutWarehouse extends ATourNotify
{
    /**
     * ActualOutWarehouse constructor.
     * @param $tour
     * @param $batchList
     * @param $orderList
     */
    public function __construct($tour, $batchList, $orderList)
    {
        parent::__construct($tour, [], $batchList, $orderList);
    }


    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ACTUAL_OUT_WAREHOUSE;
    }

    public function getDataList(): array
    {
        $packageList = Package::query()->whereIn('order_no', array_column($this->orderList, 'order_no'))->get(['order_no', 'express_first_no', 'status'])->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        $this->orderList = collect($this->orderList)->map(function ($order) use ($packageList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            return collect($order);
        })->toArray();
        unset($packageList);
        $batchList = collect($this->batchList)->keyBy('batch_no')->toArray();
        $orderList = collect($this->orderList)->groupBy(function ($order) {
            return $order['merchant_id'] . '-' . $order['batch_no'];
        })->toArray();
        $newBatchList = [];
        foreach ($orderList as $merchantIdBatchNo => $merchantBatchList) {
            list($merchantId, $batchNo) = explode('-', $merchantIdBatchNo);
            if (!empty($batchList[$batchNo])) {
                $newBatchList[$merchantId][] = array_merge($batchList[$batchNo], ['merchant_id' => $merchantId, 'order_list' => $merchantBatchList]);
            } else {
                $newBatchList[$merchantId][] = array_merge(Arr::only($merchantBatchList[0], self::$batchFields), ['merchant_id' => $merchantId, 'order_list' => $merchantBatchList]);
            }
        }
        $tourList = [];
        foreach ($newBatchList as $merchantId => $merchantBatchList) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch_list' => $merchantBatchList]);
        }
        return $tourList;
    }
}
