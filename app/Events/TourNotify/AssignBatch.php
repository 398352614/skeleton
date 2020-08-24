<?php
/**
 * 签收站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:41
 */

namespace App\Events\TourNotify;


use App\Events\Interfaces\ATourNotify;
use App\Models\AdditionalPackage;
use App\Models\Material;
use App\Models\Order;
use App\Models\Package;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

class AssignBatch extends ATourNotify
{

    /**
     * AssignBatch constructor.
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
        return BaseConstService::NOTIFY_ASSIGN_BATCH;
    }

    public function getDataList(): array
    {
        $this->batch['delivery_count'] = 0;
        $orderNoList = array_column($this->orderList, 'order_no');
        $packageList = Package::query()->whereIn('order_no', $orderNoList)->get(['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount', 'is_auth', 'auth_fullname', 'auth_birth_date'])->toArray();
        foreach ($packageList as $k => $v) {
            $packageList[$k]['delivery_count'] = floatval($v['delivery_amount']) == 0.00 ? 0 : 1;
        }
        $packageList = array_create_group_index($packageList, 'order_no');
        Log::info('package_list', $packageList);
        $materialList = Material::query()->whereIn('order_no', $orderNoList)->get(['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'])->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        $this->orderList = collect($this->orderList)->map(function ($order) use ($packageList, $materialList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            $order['material_list'] = $materialList[$order['order_no']] ?? [];
            return collect($order);
        })->toArray();
        $orderList = $this->orderList;
        foreach ($orderList as $k => $v) {
            $orderList[$k]['delivery_count'] = 0;
            if (!empty($packageList[$v['order_no']])) {
                $deliveryCountList = collect($packageList[$v['order_no']])->pluck('delivery_count')->toArray();
                foreach ($deliveryCountList as $x => $y) {
                    $orderList[$k]['delivery_count'] += $y;
                }
            }
            $this->batch['delivery_count'] += $orderList[$k]['delivery_count'];
            Log::info('order', $orderList[$k]);
        }
        unset($packageList, $materialList);
        $orderList = collect($orderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($orderList as $merchantId => $merchantOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'order_list' => $merchantOrderList]);
        }
        //顺带包裹
        $additionalPackageList = AdditionalPackage::query()->whereIn('batch_no', $this->batch['batch_no'])->get(['merchant_id', 'package_no']);
        if (!empty($additionalPackageList)) {
            $additionalPackageList = $additionalPackageList->groupBy('merchant_id')->toArray();
        } else {
            $additionalPackageList = [];
        }
        Log::info('顺带包裹', $additionalPackageList);
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch' => $batch, 'additional_package_list' => $additionalPackageList[$merchantId]]);
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
