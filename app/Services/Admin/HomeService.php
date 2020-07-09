<?php


namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Carbon;

class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 司机 服务
     * @return DriverService
     */
    private function getDriverService()
    {
        return self::getInstance(DriverService::class);
    }

    /**
     * 车辆 服务
     * @return CarService
     */
    private function getCarService()
    {
        return self::getInstance(CarService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }


    //当日数据
    public function home()
    {
        $date = Carbon::today()->format('Y-m-d');
        //当日订单
        $noTakeOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_1]);//待分配
        $assignOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_2]);//已分配
        $waitOutOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_3]);//待出库
        $takingOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_4]);//取派中
        $signedOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_5]);//已完成
        //异常订单
        $cancelOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_6]);//取消取派
        $exceptionOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);//异常
        //司机及车辆统计
        $carSum = $this->getCarService()->count();//车辆总数
        $driverSum = $this->getDriverService()->count();//司机总数
        //已分配车辆数
        $assignCar = Tour::query()->whereNotNull('car_id')->where('execution_date', $date)->where('status', 'in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2])->count();
        $assignDriver = Tour::query()->where('execution_date', $date)->where('status', BaseConstService::TOUR_STATUS_2)->count();

        //$assignCar = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_2]);//已分配
        $waitOutCar = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_3]);//待出库
        $takingCar = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_4]);//配送中
        $graph = $this->thisWeekCount();
        return [
            'preparing_order' => $noTakeOrder + $assignOrder + $waitOutOrder,
            'taking_order' => $takingOrder,
            'signed_order' => $signedOrder,
            'cancel_order' => $cancelOrder,
            'exception_order' => $exceptionOrder,

            //司机统计
            'sum_driver' => $driverSum,
            //'preparing_driver' => $assignDriver + $waitOutCar,
            //'taking_driver' => $takingCar,
            //'signed_driver' => $signedCar,
            'working_driver' => $assignDriver + $waitOutCar + $takingCar,
            'free_driver' => $driverSum - $assignCar - $waitOutCar - $takingCar,

            //车辆统计
            'sum_car' => $carSum,
            //'preparing_car' => $assignCar + $waitOutCar,
            //'taking_car' => $takingCar,
            //'signed_car' => $signedCar,
            'working_car' => $assignCar + $waitOutCar + $takingCar,
            'free_car' => $carSum - $assignCar - $waitOutCar - $takingCar,

            //表格
            'graph' => $graph,
        ];

    }

    //本周订单统计
    public function thisWeekCount()
    {
        $day = Carbon::today();
        $no = $day->dayOfWeek;
        if ($no === 0) {
            $no = $no + 7;
        }
        return $this->ordercount($day, $no);
    }

    //上周订单统计
    public function lastWeekCount()
    {
        $day = Carbon::today()->subWeek()->endOfWeek();
        $no = $day->dayOfWeek + 7;
        return $this->ordercount($day, $no);
    }

    //本月订单统计
    public function thisMonthCount()
    {
        $day = Carbon::today();
        $no = $day->day;
        return $this->ordercount($day, $no);
    }

    //上月订单统计
    public function lastMonthCount()
    {
        $day = Carbon::today()->subMonth()->endOfMonth();
        $no = $day->daysInMonth;
        return $this->ordercount($day, $no);
    }

    //订单统计
    public function orderCount(Carbon $day, $no)
    {
        $countInfo = [];
        for ($i = $no; $i >= 1; $i--) {
            $date = $day->format('Y-m-d');
            $actualCount = $this->count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_5]);
            $expectCount = $this->count(['execution_date' => $date, 'status' => ['<>', BaseConstService::ORDER_STATUS_7]]);
            $countInfo[$i] = ['date' => $date, 'actual_count' => $actualCount, 'expect_count' => $expectCount];
            $day = $day->subDay();
        }
        $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        return $countInfo;
    }


    //时间段订单统计

    /**
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function periodCount($params)
    {
        $periodInfo = [];
        if (empty($params['begin_date'])) {
            throw new BusinessLogicException('请选择开始时间');
        }
        if (empty($params['end_date'])) {
            throw new BusinessLogicException('请选择结束时间');
        }
        $day = Carbon::create($params['begin_date']);
        $endDay = Carbon::create($params['end_date']);
        for ($i = 1; $day->lte($endDay); $i++) {
            $date = $day->format('Y-m-d');
            $ordercount = $this->count(['execution_date' => $date]);
            $periodInfo[$i] = ['date' => $date, 'ordercount' => $ordercount];
            $day = $day->addDay();
        }
        return array_values($periodInfo);
    }

}
