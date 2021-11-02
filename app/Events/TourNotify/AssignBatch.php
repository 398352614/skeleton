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
use App\Models\Package;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignBatch extends ATourNotify
{

    /**
     * AssignBatch constructor.
     * @param $tour
     * @param $batch
     * @param array $trackingOrderList
     */
    public function __construct($tour, $batch, $trackingOrderList = [])
    {
        $trackingOrderList = !empty($trackingOrderList) ? $trackingOrderList : $this->getTrackingOrderAndPackageList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $trackingOrderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ASSIGN_BATCH;
    }

    public function getDataList(): array
    {
        $this->batch['delivery_count'] = 0;
        $this->fillTrackingOrderList(true, true);
        //订单处理
        $trackingOrderList = collect($this->trackingOrderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($trackingOrderList as $merchantId => $merchantTrackingOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'tracking_order_list' => $merchantTrackingOrderList, 'delivery_count' => array_sum(array_column($merchantTrackingOrderList, 'delivery_count'))]);
        }
        //处理顺带包裹提货数
        $additionalPackageList = AdditionalPackage::query()->where('batch_no', $this->batch['batch_no'])->get(['merchant_id', 'package_no', 'sticker_amount', 'sticker_no', DB::raw('IFNULL(delivery_amount,0.00) as delivery_amount'), DB::raw('IF(IFNULL(delivery_amount,0.00)=0.00,0,1) as delivery_count')]);
        if ($additionalPackageList->isNotEmpty()) {
            $additionalPackageList = $additionalPackageList->groupBy('merchant_id')->toArray();
        } else {
            $additionalPackageList = [];
        }
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            Log::info($merchantId);
            if($merchantId == config('tms.erp_merchant_id')){
                $batch['pay_type'] = 1;
            }
            Log::info($batch['pay_type']);
            $batch['additional_package_list'] = $additionalPackageList[$merchantId] ?? [];
            $batch['delivery_count'] += !empty($additionalPackageList[$merchantId]) ? array_sum(array_column($additionalPackageList[$merchantId], 'delivery_count')) : 0;
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
     * 获取运单列表
     *
     * @param $batchNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getTrackingOrderAndPackageList($batchNo)
    {
        $trackingOrderList = TrackingOrder::query()->where('batch_no', $batchNo)->where('status', BaseConstService::TRACKING_ORDER_STATUS_5)->get()->toArray();
        $packageList = Package::query()->whereIn('order_no', array_column($trackingOrderList, 'order_no'))->get()->toArray();
        $packageList = collect($packageList)->groupBy('order_no')->toArray();
        foreach ($trackingOrderList as &$order) {
            $order['package_list'] = $packageList[$order['order_no']] ?? '';
        }
        return $trackingOrderList;
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
