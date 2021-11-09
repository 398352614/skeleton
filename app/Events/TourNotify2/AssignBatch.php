<?php
/**
 * 签收站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:41
 */

namespace App\Events\TourNotify2;


use App\Events\Interfaces\ATourNotify2;
use App\Models\Package;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;

class AssignBatch extends ATourNotify2
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
        $this->fillTrackingOrderList();
        return $this->trackingOrderList;
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
