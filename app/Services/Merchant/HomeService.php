<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Services\BaseConstService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class HomeService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * 本周订单统计
     * @return array
     * @throws \Exception
     */
    public function thisWeekCount()
    {
        $day = Carbon::today();
        $no = $day->dayOfWeek;
        if ($no === 0) {
            $no = $no + 7;
        }
        return $this->orderCount($day, $no);
    }

    /**
     * 上周订单统计
     * @return array
     * @throws \Exception
     */
    public function lastWeekCount()
    {
        $day = Carbon::today()->subWeek()->endOfWeek();
        $no = $day->dayOfWeek + 7;
        return $this->orderCount($day, $no);
    }

    /**
     * 本月订单统计
     * @return array
     * @throws \Exception
     */
    public function thisMonthCount()
    {
        $day = Carbon::today();
        $no = $day->day;
        return $this->orderCount($day, $no);
    }

    /**
     * 上月订单统计
     * @return array
     * @throws \Exception
     */
    public function lastMonthCount()
    {
        $day = Carbon::today()->subMonth()->endOfMonth();
        $no = $day->daysInMonth;
        return $this->orderCount($day, $no);
    }

    /**
     * 基础统计
     * @param Carbon $day
     * @param $no
     * @return array
     * @throws \Exception
     */
    public function orderCount(Carbon $day, $no)
    {
        $countInfo = [];
        for ($i = $no; $i >= 1; $i--) {
            $date = $day->format('Y-m-d');
            $expectCount = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'execution_date' => $date]);
            $countInfo[$i] = ['date' => $date, 'order' => $expectCount];
            $day = $day->subDay();
        }
        $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        return $countInfo;
    }

    /**
     * 时间段统计
     * @param $params
     * @return array
     */
    public function periodCount($params)
    {
        $countInfo = [];
        $orderList = parent::getList(['status' => ['in', [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3]]], ['execution_date'], false);
        //总计
        $day = \Illuminate\Support\Carbon::create($params['begin_date']);
        $endDay = Carbon::create($params['end_date']);
        for ($i = 1; $day->lte($endDay); $i++) {
            $date = $day->format('Y-m-d');
            $orderCount = collect($orderList)->where('execution_date', $date)->count();
            $countInfo[$i] = ['date' => $date, 'order' => $orderCount];
            $day = $day->addDay();
        }
        $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        return $countInfo;
    }

    /**
     * 今日概览
     * @return array
     */
    public function todayOverview()
    {
        $info = $this->query->where('execution_date', date('Y-m-d'))->get();
        $doingOrder = count(collect($info)->where('status', '=', BaseConstService::ORDER_STATUS_2));
        $signedOrder = count(collect($info)->where('status', '=', BaseConstService::ORDER_STATUS_3));
        $cancelOrder = count(collect($info)->whereIn('status', [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]));
        return [
            'doing' => $doingOrder,
            'done' => $signedOrder,
            'cancel' => $cancelOrder
        ];
    }

    /**
     * 订单动态
     * @return Collection
     */
    public function trail()
    {
        return $this->getOrderTrailService()->merchantHome();
    }
}
