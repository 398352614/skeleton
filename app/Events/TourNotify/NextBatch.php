<?php
/**
 * 下一个站点事件
 * User: long
 * Date: 2020/4/2
 * Time: 14:45
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ATourNotify;
use App\Events\Interfaces\ShouldSendNotify2Merchant;
use App\Models\Order;
use App\Services\BaseConstService;

class NextBatch extends ATourNotify
{
    public function __construct($tour, $batch, $orderList = [])
    {
        $orderList = $orderList ?? $this->getOrderList($this->batch['batch_no']);
        parent::__construct($tour, $batch, [], $orderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_NEXT_BACTH;
    }

    public function getDataList(): array
    {
        $orderList = $this->getOrderList($this->batch['batch_no']);
        $orderList = collect($orderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($orderList as $merchantId => $merchantOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'order_list' => $merchantOrderList]);
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