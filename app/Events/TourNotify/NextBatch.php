<?php
/**
 * 下一个站点事件
 * User: long
 * Date: 2020/4/2
 * Time: 14:45
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ShouldSendNotify2Merchant;
use App\Models\Order;
use App\Services\BaseConstService;

class NextBatch implements ShouldSendNotify2Merchant
{
    public $tour;

    public $nextBatch;

    public $orderList;

    public function __construct($tour, $nextBatch, $orderList = [])
    {
        $this->tour = $tour;
        $this->nextBatch = $nextBatch;
        $this->orderList = $orderList ?? $this->getOrderList($this->nextBatch['batch_no']);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_NEXT_BACTH;
    }

    public function getDataList(): array
    {
        $orderList = $this->getOrderList($this->nextBatch['batch_no']);
        $orderList = collect($orderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($orderList as $merchantId => $merchantOrderList) {
            $batchList[$merchantId] = array_merge($this->nextBatch, ['merchant_id' => $merchantId, 'order_list' => $merchantOrderList]);
        }
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch' => $batch]);
        }
        return $tourList;
    }

    /**
     * 获取订单列表
     *
     * @param $batchNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getOrderList($batchNo)
    {
        $orderList = Order::query()->where('batch_no', $batchNo)->where('status', BaseConstService::ORDER_STATUS_4)->get();
        return $orderList->toArray();
    }
}