<?php


namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Carbon\Carbon;
class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 默认今日总计和本周数据
     * @return array
     * @throws BusinessLogicException
     * @throws \Exception
     */
    public function home(){
        $end=Carbon::today();
        $begin=Carbon::today()->startOfWeek();
        $data=$this->data($end,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    public function all(){
        $end=Carbon::today();
        $order=$this->query->whereNotNull('execution_date')->pluck('execution_date')->toArray();
        if(empty($order)){
            $begin=$end;
        }else{
            $begin=Carbon::create($order[0]);
        }
        $data=$this->data($begin,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    /**
     * 本周订单统计
     * @return array
     * @throws \Exception
     */
    public function thisWeekCount(){
        $end=Carbon::today();
        $begin=Carbon::today()->startOfWeek();
        $data=$this->data($begin,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    /**
     * 上周订单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function lastWeekCount(){
        $end=Carbon::today()->subWeek()->endOfWeek();
        $begin=Carbon::today()->subWeek()->startOfWeek();
        $data=$this->data($begin,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    /**
     * 本月订单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function thisMonthCount(){
        $end=Carbon::today();
        $begin=Carbon::today()->startOfMonth();
        $data=$this->data($begin,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    /**
     * 上月订单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function lastMonthCount(){
        $end=Carbon::today()->subMonth()->endOfMonth();
        $begin=Carbon::today()->subMonth()->startOfMonth();
        $data=$this->data($begin,$end);
        $data['graph']=$this->orderCount($begin,$end);
        return $data;
    }

    /**
     * 时间段订单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function periodCount($params){
        if(empty($params['begin_date'])){
            throw new BusinessLogicException('请选择开始时间');
        }
        if(empty($params['end_date'])){
            throw new BusinessLogicException('请选择结束时间');
        }
        $begin=Carbon::create($params['begin_date']);
        $end=Carbon::create($params['end_date']);
        return $this->data($begin,$end);
    }

    /**
     * 订单总数据统计
     * @param \Carbon\Carbon $begin
     * @param \Carbon\Carbon $end
     * @return array
     */
    public function data(Carbon $begin,Carbon $end){
        $info=$this->query->whereBetween('execution_date',[$begin->format('Y-m-d'),$end->format('Y-m-d')])->get();
        $allOrder=count($info);
        $pickupOrder=count(collect($info)->where('type','=',BaseConstService::ORDER_TYPE_1));
        $peiOrder=count(collect($info)->where('type','=',BaseConstService::ORDER_TYPE_2));
        $noTakeOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_1));
        $assignOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_2));
        $waitOutOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_3));
        $takingOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_4));
        $signedOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_5));
        $cancelOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_STATUS_6));
        $exceptionOrder=count(collect($info)->where('status','=',BaseConstService::ORDER_EXCEPTION_LABEL_2));
        return[
            'all' => $allOrder,
            'pickup'=>$pickupOrder,
            'pie'=>$peiOrder,
            'prepare'=>$noTakeOrder+$assignOrder,
            'doing'=>$waitOutOrder+$takingOrder,
            'done'=>$signedOrder,
            'exception'=>$exceptionOrder,
            'cancel'=>$cancelOrder,
            ];
    }

    /**
     * 订单每日数据表
     * @param Carbon $begin
     * @param Carbon $end
     * @return array
     */
    public function orderCount(Carbon $begin,Carbon $end){
        $countInfo=[];
        if ($begin === $end){
            $date =$end->format('Y-m-d');
            $orderCount=$this->count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_5]);
            $countInfo[0]=['date'=>$date,'orderCount'=>$orderCount];
        }else{
            for($i=0,$j=$end->diffInDays($begin);$i<=$j;$i++){
                $date =$begin->format('Y-m-d');
                $orderCount=$this->count(['execution_date'=>$date,'status' => BaseConstService::ORDER_STATUS_5]);
                $countInfo[$i]=['date'=>$date,'orderCount'=>$orderCount];
                $begin =$begin->addDay();
            }
            $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        }
        return $countInfo;
    }
}
