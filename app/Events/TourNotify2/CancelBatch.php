<?php
/**
 * 取消派送站点 事件
 * User: long
 * Date: 2020/4/2
 * Time: 15:33
 */

namespace App\Events\TourNotify2;


use App\Events\Interfaces\ATourNotify2;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;

class CancelBatch extends ATourNotify2
{

    /**
     * CancelBatch constructor.
     * @param $tour
     * @param $batch
     * @param array $trackingOrderList
     */
    public function __construct($tour, $batch, $trackingOrderList = [])
    {
        $trackingOrderList = !empty($trackingOrderList) ? $trackingOrderList : $this->getTrackingOrderList($batch['batch_no']);
        parent::__construct($tour, $batch, [], $trackingOrderList);
    }

    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_CANCEL_BATCH;
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
    public function getTrackingOrderList($batchNo)
    {
        $trackingOrderList = TrackingOrder::query()->where('batch_no', $batchNo)->where('status', BaseConstService::TRACKING_ORDER_STATUS_4)->get();
        return $trackingOrderList->toArray();
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
            $content = '取消取派推送成功';
        } else {
            $content = '取消取派推送失败,失败原因:' . $msg;
        }
        return $content;
    }
}
