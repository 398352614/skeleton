<?php

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        $todayTotalTour = $this->getTourService()->count(['execution_date' => $date, 'driver_id' => auth()->user()->id]);
        $todayDoneTour = $this->getTourService()->count(['execution_date' => $date, 'driver_id' => auth()->user()->id, 'status' => BaseConstService::TOUR_STATUS_5]);
        $takingTour = $this->getTourService()->count(['driver_id' => auth()->user()->id, 'status' => ['in', [BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]]);
        $doneTour = $this->getTourService()->count(['driver_id' => auth()->user()->id, 'status' => BaseConstService::TOUR_STATUS_5]);
        $memorandum = $this->getMemorandumService()->count();
        return [
            //订单统计


            'today_total_tour' => $todayTotalTour,
            'today_done_tour' => $todayDoneTour,

            'taking_tour' => $takingTour,
            'done_tour' => $doneTour,
            'memorandum' => $memorandum,

        ];

    }
}
