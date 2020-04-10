<?php
/**
 * 到达站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:33
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ATourNotify;
use App\Models\Order;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

class ArrivedBatch extends ATourNotify
{

    public $tour;

    public $batch;

    public $orderList;

    public function __construct($tour, $batch, $orderList = [])
    {
        $orderList = !empty($orderList) ? $orderList : $this->getOrderList($this->batch['batch_no']);
        parent::__construct($tour, $batch, [], $orderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ARRIVED_BATCH;
    }

    public function getDataList(): array
    {
        $orderList = collect($this->orderList)->groupBy('merchant_id')->toArray();
        Log::info('order-list:' . json_encode($orderList));
        $batchList = [];
        foreach ($orderList as $merchantId => $merchantOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'order_list' => $merchantOrderList]);
        }
        Log::info('batch-list:' . json_encode($batchList));
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch' => $batch]);
        }
        Log::info('tour-list:' . json_encode($tourList));
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