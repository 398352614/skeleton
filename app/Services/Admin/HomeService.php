<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Models\Tour;
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
        $noTakeOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_1]);//待分配
        $takingOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_2]);//取派中
        $signedOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_3]);//已完成
        $cancelOrder = parent::count(['execution_date' => $date, 'status' => BaseConstService::ORDER_STATUS_4]);//取消取派
        $exceptionOrder = parent::count(['execution_date' => $date, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);//异常
        //取件线路
        $tour = $this->getTourService()->count(['execution_date' => $date]);
        //司机及车辆
        $carSum = $this->getCarService()->count();//车辆总数
        $driverSum = $this->getDriverService()->count();//司机总数
        $assignCar = Tour::query()->whereNotNull('car_id')->where('execution_date', $date)->whereIn('status', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2])->count();
        $assignDriver = Tour::query()->where('execution_date', $date)->where('status', BaseConstService::TOUR_STATUS_2)->count();
        $waitOutCar = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_3]);//待出库
        $takingCar = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_4]);//配送中
        return [
            //订单统计
            'preparing_order' => $noTakeOrder,
            'taking_order' => $takingOrder,
            'signed_order' => $signedOrder,
            'cancel_order' => $cancelOrder,
            'exception_order' => $exceptionOrder,
            'total_order' => $noTakeOrder + $takingOrder + $signedOrder + $cancelOrder,
            //任务统计
            'tour' => $tour,
            //司机统计
            'total_driver' => $driverSum,
            'working_driver' => $assignDriver + $waitOutCar + $takingCar,
            'free_driver' => $driverSum - $assignDriver - $waitOutCar - $takingCar,
            //车辆统计
            'total_car' => $carSum,
            'working_car' => $assignCar + $waitOutCar + $takingCar,
            'free_car' => $carSum - $assignCar - $waitOutCar - $takingCar,
        ];

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
        return $this->orderCountByMerchant($day, $no);
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
        return $this->orderCountByMerchant($day, $no);
    }

    /**
     * 本月订单统计
     * @return array
     * @throws \Exception
     */
    public function thisMonthCount()
    {
        $day = \Illuminate\Support\Carbon::today();
        $no = $day->day;
        return $this->orderCountByMerchant($day, $no);
    }

    /**
     * 上月订单统计
     * @return array
     * @throws \Exception
     */
    public function lastMonthCount()
    {
        $day = \Illuminate\Support\Carbon::today()->subMonth()->endOfMonth();
        $no = $day->daysInMonth;
        return $this->orderCountByMerchant($day, $no);
    }

    /**
     * 分商户统计
     * @param Carbon $day
     * @param $no
     * @return array
     * @throws \Exception
     */
    public function orderCountByMerchant(Carbon $day, $no)
    {
        $data = [];
        $merchantList = $this->getMerchantService()->getList();
        foreach ($merchantList as $k => $v) {
            $info[$k] = new Carbon($day);
            $data[$k]['merchant_name'] = $v['name'];
            $data[$k]['graph'] = $this->orderCount($info[$k], $no, $v['id']);
        }
        return $data;
    }

    /**
     * 基础统计
     * @param Carbon $day
     * @param $no
     * @param $merchantId
     * @return array
     */
    public function orderCount(Carbon $day, $no, $merchantId)
    {
        $countInfo = [];
        for ($i = $no; $i >= 1; $i--) {
            $date = $day->format('Y-m-d');
            $expectCount = parent::count(['merchant_id' => $merchantId, 'status' => ['<>', BaseConstService::ORDER_STATUS_5], 'execution_date' => $date]);
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
     * @throws BusinessLogicException
     */
    public function periodCount($params)
    {
        $data = [];
        $merchantList = $this->getMerchantService()->getList();
        foreach ($merchantList as $k => $v) {
            $data[$k]['merchant_name'] = $v['name'];
            $data[$k]['graph'] = $this->periodCountByMerchant($params, $v['id']);
        }
        return $data;
    }

    //时间段订单统计

    /**
     * 分商户时间段统计
     * @param $params
     * @param $merchantId
     * @return array
     * @throws BusinessLogicException
     */
    public function periodCountByMerchant($params, $merchantId)
    {
        $periodInfo = [];
        if (empty($params['begin_date'])) {
            throw new BusinessLogicException('请选择开始时间');
        }
        if (empty($params['end_date'])) {
            throw new BusinessLogicException('请选择结束时间');
        }
        $orderList = parent::getList(['merchant_id' => $merchantId, 'status' => ['<>', BaseConstService::TRACKING_ORDER_STATUS_7]], ['*'], false);
        $day = Carbon::create($params['begin_date']);
        $endDay = Carbon::create($params['end_date']);
        for ($i = 1; $day->lte($endDay); $i++) {
            $date = $day->format('Y-m-d');
            $orderCount = collect($orderList)->where('execution_date', $date)->count();
            $periodInfo[$i] = ['date' => $date, 'order' => $orderCount];
            $day = $day->addDay();
        }
        return array_values($periodInfo);
    }

    /**
     * 商户订单统计
     * @return array
     */
    public function merchantCount()
    {
        $data = [];
        $total = ['merchant_name' => __('合计'), 'total_order' => 0, 'pickup_order' => 0, 'pie_order' => 0, 'pickup_pie_order' => 0, 'cancel_order' => 0, 'additional_package' => 0, 'total_recharge' => 0];
        $merchantList = $this->getMerchantService()->getList();
        foreach ($merchantList as $k => $v) {
            $data[$k]['merchant_name'] = $v['name'];
            $data[$k]['total_order'] = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'merchant_id' => $v['id']]);
            $data[$k]['pickup_order'] = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'merchant_id' => $v['id'], 'type' => BaseConstService::ORDER_TYPE_1]);
            $data[$k]['pie_order'] = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'merchant_id' => $v['id'], 'type' => BaseConstService::ORDER_TYPE_2]);
            $data[$k]['pickup_pie_order'] = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'merchant_id' => $v['id'], 'type' => BaseConstService::ORDER_TYPE_3]);
            $data[$k]['cancel_order'] = parent::count(['status' => BaseConstService::ORDER_STATUS_4, 'merchant_id' => $v['id']]);
            $data[$k]['additional_package'] = DB::table('additional_package')->where('company_id', auth()->user()->company_id)->where('merchant_id', $v['id'])->count();
            $data[$k]['total_recharge'] = $this->getRechargeStatisticsService()->sum('total_recharge_amount', ['merchant_id' => $v['id']]);
            $total['total_order'] += $data[$k]['total_order'];
            $total['pickup_order'] += $data[$k]['pickup_order'];
            $total['pie_order'] += $data[$k]['pie_order'];
            $total['pickup_pie_order'] += $data[$k]['pickup_pie_order'];
            $total['cancel_order'] += $data[$k]['cancel_order'];
            $total['additional_package'] += $data[$k]['additional_package'];
            $total['total_recharge'] += $data[$k]['total_recharge'];
            $data[$k]['total_recharge'] = number_format($data[$k]['total_recharge'], 2);
        }
        $total['total_recharge'] = number_format($total['total_recharge'], 2);
        $data[] = $total;
        return $data;
    }

    /**
     * 商户饼图统计
     * @return array
     */
    public function merchantTotalCount()
    {
        $data = [];
        $merchantList = $this->getMerchantService()->getList();
        $orderList = parent::getList(['execution_date' => Carbon::today()->format('Y-m-d'), 'status' => ['<>', BaseConstService::TRACKING_ORDER_STATUS_7]], ['*'], false);
        foreach ($merchantList as $k => $v) {
            $data[$k]['merchant_name'] = $v['name'];
            $data[$k]['order'] = collect($orderList)->where('merchant_id', $v['id'])->count();
            if ($data[$k]['order'] == 0) {
                unset($data[$k]);
            }
        }
        $data = array_values($data);
        return $data;
    }

    public function getShortCut()
    {
        $shortCutList = ConstTranslateTrait::formatList(ConstTranslateTrait::$shortCutList);
        $permissionList = auth()->user()->getAllPermissions()->filter(function ($permission, $key) {
            return ($permission['type'] == 2);
        })->keyBy('route_as')->toArray();
        if (empty($permissionList)) return $shortCutList;
        $shortCutList = array_filter($shortCutList, function ($shortCunt) use ($permissionList) {
            return !empty($permissionList[$shortCunt['id']]);
        });
        return $shortCutList;
    }
}
