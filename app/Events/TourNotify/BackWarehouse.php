<?php

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
use App\Services\BaseConstService;

/**
 * 司机回仓事件
 */
class BackWarehouse extends ATourNotify
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
        return [];
    }
}
