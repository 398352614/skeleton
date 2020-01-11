<?php


namespace App\Services\Admin;


use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
        $this->model=$order;
        $this->query = $this->model::query();
        $this->request = request();

    }

    private function getDriverService()
    {
        return self::getInstance(DriverService::class);
    }

    private function getCarService()
    {
        return self::getInstance(CarService::class);
    }

    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }


    //当日数据
    public function home(){
        $date =Carbon::today()->addDay();
        //当日订单
        $noTakeOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_1]);//未分配
        $assignOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_2]);//已分配
        $waitOutOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_3]);//待出库
        $takingOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_4]);//取派中
        $signedOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_5]);//已完成
        //异常订单
        $cancelOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_6]);//取消取派
        $exceptionOrder = parent::count(['execution_date'=>$date,'status' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);//异常
        //司机及车辆统计
        $carSum=$this->getCarService()->count();//车辆总数
        $driverSum=$this->getDriverService()->count();//司机总数
        $assignCar=$this->getTourService()->count(['execution_date'=>$date,'status'=>BaseConstService::TOUR_STATUS_2 ]);//已分配
        $waitOutCar=$this->getTourService()->count(['execution_date'=>$date,'status'=>BaseConstService::TOUR_STATUS_3 ]);//待出库
        $takingCar=$this->getTourService()->count(['execution_date'=>$date,'status'=>BaseConstService::TOUR_STATUS_4 ]);//配送中
        $signedCar=$this->getTourService()->count(['execution_date'=>$date,'status'=>BaseConstService::TOUR_STATUS_5 ]);//配送完成
        $graph=$this->thisWeekCount();

        return[
            //'no_take_order' => $noTakeOrder,
            'assign_order'=>$assignOrder,
            'wait_out_order'=>$waitOutOrder,
            'taking_order'=>$takingOrder,
            'signed_order'=>$signedOrder,
            'cancel_order'=>$cancelOrder,
            'exception_order'=>$exceptionOrder,
            'car_sum'=>$carSum,
            'driver_sum'=>$driverSum,
            'outing_car'=>$assignCar+$waitOutCar,
            'taking_car'=>$takingCar,
            'signed_car'=>$signedCar,
            'graph'=>$graph,
        ];

    }

    //本周订单统计
    public function thisWeekCount(){
        $day=Carbon::today();
        $no=$day->dayOfWeek;
        if($no===0){
            $no=$no+7;
        }
        return $this->ordercount($day,$no);
    }

    //上周订单统计
    public function lastWeekCount(){
        $day=Carbon::today()->subWeek()->endOfWeek();
        $no=$day->dayOfWeek+7;
        return $this->ordercount($day,$no);
    }

    //本月订单统计
    public function thisMonthCount(){
        $day=Carbon::today();
        $no=$day->day;
        return $this->ordercount($day,$no);
    }

    //上月订单统计
    public function lastMonthCount(){
        $day=Carbon::today()->subMonth()->endOfMonth();
        $no=$day->daysInMonth;
        return $this->ordercount($day,$no);
    }

    //订单统计
    public function ordercount(Carbon $day,$no){
        $countInfo =[];
        for($i=$no;$i>=1;$i--){
            $date =$day->format('Y-m-d');
            $ordercount=$this->count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_5]);
            $countInfo[$i]=['date'=>$date,'ordercount'=>$ordercount];
            $day =$day->subDay();
        }
        return array_values($countInfo);
}


    //时间段订单统计
    public function periodCount($params){
        $periodInfo =[];
        $day=Carbon::create($params['begin_date']);
        $endDay=Carbon::create($params['end_date']);
        for($i=1;$day->lte($endDay);$i++){
            $date =$day->format('Y-m-d');
            $ordercount=$this->count(['execution_date'=>$date]);
            $periodInfo[$i]=['date'=>$date,'ordercount'=>$ordercount];
            $day =$day->addDay();
        }
        return array_values($periodInfo);
    }

}
