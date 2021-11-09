<?php

namespace App\Events\TourNotify2;

use App\Events\Interfaces\ATourNotify2;
use App\Services\BaseConstService;

/**
 * 司机回仓事件
 */
class BackWarehouse extends ATourNotify2
{
    public $tour;

    /**
     * Create a new event instance.
     *
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
        return BaseConstService::NOTIFY_BACK_WAREHOUSE;
    }


    public function getDataList(): array
    {
        $this->fillTrackingOrderList();
        return $this->trackingOrderList;
    }
}
