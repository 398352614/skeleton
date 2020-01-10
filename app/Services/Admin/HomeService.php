<?php


namespace App\Services\Admin;


use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeService extends BaseService
{
    public function __construct()
    {
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    private function getBatchExceptionService()
    {
        return self::getInstance(BatchExceptionService::class);
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


    public function home(){
        $info=[];
        $pickupcount =$this->getOrderService()->initPickupIndex();
        $piecount =$this->getOrderService()->initPieIndex();
        //订单统计
        $info['order_no_take']=$pickupcount['no_take']+$piecount['no_take'];
        $info['order_assign']=$pickupcount['assign']+$piecount['assign'];
        $info['order_wait_out']=$pickupcount['wait_out']+$piecount['wait_out'];
        $info['order_taking']=$pickupcount['taking']+$piecount['taking'];
        $info['order_singed']=$pickupcount['singed']+$piecount['singed'];
        $info['order_cancel']=$pickupcount['cancel_count']+$piecount['cancel_count'];
        $info['order_singed']=$pickupcount['singed']+$piecount['singed'];
        //汽车车辆统计
        $info['car_sum']=$this->getCarService()->count();
        $info['driver_sum']=$this->getDriverService()->count();
        $info['car_assign']=$this->getTourService()->count(['status'=>BaseConstService::TOUR_STATUS_2 ]);
        $info['car_wait_out']=$this->getTourService()->count(['status'=>BaseConstService::TOUR_STATUS_3 ]);
        $info['car_taking']=$this->getTourService()->count(['status'=>BaseConstService::TOUR_STATUS_4 ]);
        $info['car_signed']=$this->getTourService()->count(['status'=>BaseConstService::TOUR_STATUS_5 ]);
        return $info;
    }

    //周订单统计7
    public function weekCount(){
        $weekinfo =[];
        $day=Carbon::today();
        $week_no=$day->dayOfWeek;
        if($week_no===0){
            $week_no=$week_no+7;
        }
        for($i=$week_no;$i>=1;$i--){
            $ordercount=$this->getOrderService()->count(['execution_date'=>$day]);
            $weekinfo[$day->format('Y-m-d')]=$ordercount;
            $day =$day->subDay();
        }
        return $weekinfo;
    }

    //月订单统计30
    public function monthCount(){
        $monthinfo =[];
        $day=Carbon::today();
        $month_no=$day->day;
        for($i=$month_no;$i>=1;$i--){
            $ordercount=$this->getOrderService()->count(['execution_date'=>$day]);
            $monthinfo[$day->format('Y-m-d')]=$ordercount;
            $day =$day->subDay();
        }
        return $monthinfo;
    }

    //年订单统计12
    public function yearCount(){
        $yearinfo =[];
        $day=Carbon::today();
        for($i=$day->month;$i>=1;$i--){
            $month=$day->format('Y-m');
            $ordercount=$this->getOrderService()->count(['execution_date'=>['like',$month]]);
            $yearinfo[$month]=$ordercount;
            $day =$day->subMonth();
        }
        return $yearinfo;
    }
}
