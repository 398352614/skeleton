<?php

namespace App\Services\Driver;

use App\Models\Order;
use App\Services\BaseConstService;
use Illuminate\Support\Carbon;

class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 当日数据
     * @return array
     */
    public function home()
    {
        $date = Carbon::today()->format('Y-m-d');
        //当日订单

        $todayTotalBatch = $this->getBatchService()->count(['execution_date' => $date, 'driver_id' => auth()->user()->id]);
        $todayDoneBatch = $this->getBatchService()->count(['execution_date' => $date, 'driver_id' => auth()->user()->id, 'status' => BaseConstService::BATCH_CHECKOUT]);
        $takingTour = $this->getTourService()->count(['driver_id' => auth()->user()->id, 'status' => ['in', [BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]]);
        $doneTour = $this->getTourService()->count(['driver_id' => auth()->user()->id, 'status' => BaseConstService::TOUR_STATUS_5]);
        $memorandum = $this->getMemorandumService()->count();
        return [
            //订单统计


            'today_total_tour' => $todayTotalBatch,
            'today_done_tour' => $todayDoneBatch,

            'taking_tour' => $takingTour,
            'done_tour' => $doneTour,
            'memorandum' => $memorandum,

        ];

    }
}
