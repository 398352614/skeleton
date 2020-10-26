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
use App\Models\Order;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

class NextBatch extends ATourNotify
{
    public function __construct($tour, $batch, $orderList = [])
    {
        $orderList = !empty($orderList) ? $orderList : $this->getOrderList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $orderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_NEXT_BATCH;
    }

    public function getDataList(): array
    {
        $this->batch = Batch::query()->where('batch_no', $this->batch['batch_no'])->first(self::$batchFields)->toArray();
        $orderList = collect($this->orderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        //更新预计耗时
        if (!empty($this->batch['expect_arrive_time'])) {
            $expectTime = strtotime($this->batch['expect_arrive_time']) - time();
            $this->batch['expect_time'] = $expectTime > 0 ? $expectTime : 0;
        } else {
            $this->batch['expect_time'] = 0;
        }
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
        $orderList = Order::query()->where('batch_no', $batchNo)->where('status', BaseConstService::TRACKING_ORDER_STATUS_4)->get();
        return $orderList->toArray();
    }
}
