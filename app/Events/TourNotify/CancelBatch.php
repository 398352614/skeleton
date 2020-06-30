<?php
/**
 * 取消派送站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:33
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ATourNotify;
use App\Models\Order;
use App\Models\Package;
use App\Services\BaseConstService;

class CancelBatch extends ATourNotify
{

    /**
     * CancelBatch constructor.
     * @param $tour
     * @param $batch
     * @param array $orderList
     */
    public function __construct($tour, $batch, $orderList = [])
    {
        $orderList = !empty($orderList) ? $orderList : $this->getOrderAndPackageList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $orderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_CANCEL_BATCH;
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
        $orderList = collect($this->orderList)->groupBy('merchant_id')->toArray();
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
    public function getOrderAndPackageList($batchNo)
    {
        $orderList = Order::query()->where('batch_no', $batchNo)->where('status', BaseConstService::ORDER_STATUS_5)->get()->toArray();
        $packageList = Package::query()->whereIn('order_no', array_column($orderList, 'order_no'))->get()->toArray();
        $packageList = collect($packageList)->groupBy('order_no')->toArray();
        foreach ($orderList as &$order) {
            $order['package_list'] = $packageList[$order['order_no']] ?? '';
        }
        return $orderList;
    }
}