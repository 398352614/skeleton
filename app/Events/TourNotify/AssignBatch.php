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
        //处理顺带包裹提货数
        $additionalPackageList = AdditionalPackage::query()->where('batch_no', $this->batch['batch_no'])->get(['merchant_id', 'package_no', 'delivery_amount', 'sticker_no', 'sticker_amount']);
        foreach ($additionalPackageList as $k => $v) {
            $additionalPackageList[$k]['delivery_count'] = floatval($additionalPackageList[$k]['delivery_amount']) == 0.00 ? 0 : 1;
            $this->batch['delivery_count'] += $additionalPackageList[$k]['delivery_count'];
        }
        $trackingOrderList = collect($this->trackingOrderList)->groupBy('merchant_id')->toArray();
        $batchList = [];
        foreach ($trackingOrderList as $merchantId => $merchantTrackingOrderList) {
            $batchList[$merchantId] = array_merge($this->batch, ['merchant_id' => $merchantId, 'tracking_order_list' => $merchantTrackingOrderList]);
        }
        if (!empty($additionalPackageList)) {
            $additionalPackageList = $additionalPackageList->groupBy('merchant_id')->toArray();
        } else {
            $additionalPackageList = [];
        }
        Log::info($additionalPackageList);
        $tourList = [];
        foreach ($batchList as $merchantId => $batch) {
            $batch['additional_package_list'] = $additionalPackageList[$merchantId] ?? [];
            $tourList[$merchantId] = array_merge($this->tour, ['merchant_id' => $merchantId, 'batch' => $batch]);
        }
        return $tourList;
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
