<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }


    //当日数据
    public function home(){
        $graph=$this->thisWeekCount();
        return[
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
        $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        return $countInfo;
    }


    /**
     * 时间段订单统计
     *
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function periodCount($params){
        $periodInfo =[];
        if(empty($params['begin_date'])){
            throw new BusinessLogicException('请选择开始时间');
        }
        if(empty($params['end_date'])){
            throw new BusinessLogicException('请选择结束时间');
        }
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
