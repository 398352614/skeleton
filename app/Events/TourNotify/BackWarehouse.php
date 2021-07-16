<?php

namespace App\Events\TourNotify;

use App\Events\Interfaces\ATourNotify;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

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
    public function __construct($tour)
    {
        parent::__construct($tour, [], [], []);
    }


    public function notifyType(): string
    {
        return BaseConstService::NOTIFY_BACK_WAREHOUSE;
    }


    public function getDataList(): array
    {
        return [];
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
        Log::info('data_list_3', $dataList);
        return $dataList;
    }
}
