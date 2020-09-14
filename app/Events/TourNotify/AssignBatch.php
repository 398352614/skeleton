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
        //获取包裹
        $packageList = Package::query()->whereIn('order_no', $orderNoList)->get(['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount', 'is_auth', 'auth_fullname', 'auth_birth_date'])->toArray();
        foreach ($packageList as $k => $v) {
            $packageList[$k]['delivery_count'] = floatval($v['delivery_amount']) == 0.00 ? 0 : 1;
        }
        //获取材料
        $packageList = array_create_group_index($packageList, 'order_no');
        Log::info('package_list', $packageList);
        $materialList = Material::query()->whereIn('order_no', $orderNoList)->get(['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'])->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        //将包裹材料组装至订单下
        $this->orderList = collect($this->orderList)->map(function ($order) use ($packageList, $materialList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            $order['material_list'] = $materialList[$order['order_no']] ?? [];
            return collect($order);
        })->toArray();
        //统计提货数量
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
        }
        unset($packageList, $materialList);
        $orderList = collect($orderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($orderList as $merchantId => $merchantOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'order_list' => $merchantOrderList]);
        }
        //顺带包裹
        $additionalPackageList = AdditionalPackage::query()->where('batch_no', $this->batch['batch_no'])->get(['merchant_id', 'package_no', 'delivery_amount', 'sticker_no', 'sticker_amount']);
        if (!empty($additionalPackageList)) {
            $additionalPackageList = $additionalPackageList->groupBy('merchant_id')->toArray();
        } else {
            $additionalPackageList = [];
        }
        //处理顺带包裹提货数
        foreach ($additionalPackageList as $k => $v) {
            Log::info($additionalPackageList[$k]);
            $additionalPackageList[$k]['delivery_count'] = floatval($additionalPackageList[$k]['delivery_amount']) == 0.00 ? 0 : 1;
            $this->batch['delivery_count'] += $additionalPackageList[$k]['delivery_count'];
        }
        Log::info('顺带包裹', $additionalPackageList);
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $batch['additional_package_list'] = $additionalPackageList[$merchantId] ?? [];
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

    /**
     * 获取第三方对接内容
     * @param bool $status
     * @param string $msg
     * @return string
     */
    public function getThirdPartyContent(bool $status, string $msg = ''): string
    {
        if ($status == true) {
            $content = '签收推送成功';
        } else {
            $content = '签收推送失败,失败原因:' . $msg;
        }
        return $content;
    }
}
