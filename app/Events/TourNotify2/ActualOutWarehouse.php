<?php

namespace App\Events\TourNotify2;

use App\Events\Interfaces\ATourNotify2;
use App\Services\BaseConstService;

/**
 * 司机出库事件
 */
class ActualOutWarehouse extends ATourNotify2
{
    /**
     * ActualOutWarehouse constructor.
     * @param $tour
     * @param $batchList
     * @param $trackingOrderList
     */
    public function __construct($tour, $batchList, $trackingOrderList)
    {
        parent::__construct($tour, [], $batchList, $trackingOrderList);
    }


    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_ACTUAL_OUT_WAREHOUSE;
    }

    public function getDataList(): array
    {
        $this->fillTrackingOrderList();
        return $this->trackingOrderList;
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
            $content = '确定出库,预计到达时间推送成功';
        } else {
            $content = '确定出库,预计到达时间推送失败,失败原因:' . $msg;
        }
        return $content;
    }
}
