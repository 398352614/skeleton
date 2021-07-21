<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use WebSocket\Base;

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
        $preparingTrackingOrder = $this->getTrackingOrderService()->count(['execution_date' => $date, 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3]]]);//待分配
        $preparingBatch = $this->getBatchService()->count(['execution_date' => $date, 'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT]]]);
        $preparingTour = $this->getTourService()->count(['execution_date' => $date, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]]]);
        $tour = $this->getTourService()->count(['execution_date' => $date]);
        $takingTour = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_4]);
        $doneTour = $this->getTourService()->count(['execution_date' => $date, 'status' => BaseConstService::TOUR_STATUS_5]);
        $exceptionBatch = $this->getBatchService()->count(['execution_date' => $date, 'exception_label' => BaseConstService::BATCH_EXCEPTION_2]);
        $batch = $this->getBatchService()->count(['execution_date' => $date]);
        $exceptionTrackingOrder = $this->getTrackingOrderService()->count(['execution_date' => $date, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        $trackingOrder = $this->getTrackingOrderService()->count(['execution_date' => $date]);
        $exceptionPackage = $this->getStockExceptionService()->count(['created_at' => ['between', [today()->format('Y-m-d H:i:s'), today()->addDay()->format('Y-m-d h:i:s')]]]);
        $package = $this->getPackageService()->count(['execution_date' => $date]);

        $monthOrder = parent::count(['execution_date' => ['between', [Carbon::today()->startOfMonth()->format('Y-m-d H:i:s'), Carbon::today()->endOfMonth()->format('Y-m-d H:i:s')]], 'status' => BaseConstService::ORDER_STATUS_3]);
        $totalOrder = parent::count(['created_at' => ['between', [Carbon::today()->startOfDay()->format('Y-m-d H:i:s'), Carbon::today()->endOfDay()->format('Y-m-d H:i:s')]]]);
        $pickupOrder = parent::count(['execution_date' => $date, 'status' => ['<>', [BaseConstService::ORDER_STATUS_5]], 'type' => BaseConstService::ORDER_TYPE_1]);
        $pieOrder = parent::count(['execution_date' => $date, 'status' => ['<>', [BaseConstService::ORDER_STATUS_5]], 'type' => BaseConstService::ORDER_TYPE_2]);
        $pickupPieOrder = parent::count(['execution_date' => $date, 'status' => ['<>', [BaseConstService::ORDER_STATUS_5]], 'type' => BaseConstService::ORDER_TYPE_3]);
        return [
            //订单统计
            'preparing_tracking_order' => $preparingTrackingOrder,
            'preparing_batch' => $preparingBatch,
            'preparing_tour' => $preparingTour,

            'tour' => $tour,
            'taking_tour' => $takingTour,
            'done_tour' => $doneTour,

            'exception_batch' => $exceptionBatch,
            'batch' => $batch,
            'exception_tracking_order' => $exceptionTrackingOrder,
            'tracking_order' => $trackingOrder,
            'exception_package' => $exceptionPackage,
            'package' => $package,

            'month_order' => $monthOrder,
            'order' => $totalOrder,
            'pickup_order' => $pickupOrder,
            'pie_order' => $pieOrder,
            'pickup_pie_order' => $pickupPieOrder
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
     * 分货主统计
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
        $countInfo = [];
        for ($i = $no; $i >= 1; $i--) {
            $date = $day->format('Y-m-d');
            $expectCount = parent::count(['status' => ['<>', BaseConstService::ORDER_STATUS_5], 'execution_date' => $date]);
            $countInfo[$i] = ['date' => $date, 'order' => $expectCount];
            $day = $day->subDay();
        }
        $countInfo = array_values(collect(array_values($countInfo))->sortBy('date')->toArray());
        $data[] = ['merchant_name' => '总计', 'graph' => $countInfo];
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
            $expectCount = parent::count(['merchant_id' => $merchantId, 'status' => ['in', [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3]], 'execution_date' => $date]);
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
        $periodInfo = [];
        $merchantList = $this->getMerchantService()->getList([], ['name'], false);
        foreach ($merchantList as $k => $v) {
            $data[$k]['merchant_name'] = $v['name'];
            $data[$k]['graph'] = $this->periodCountByMerchant($params, $v['id']);
        }
        $orderList = parent::getList(['status' => ['in', [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3]]], ['execution_date'], false);
        //总计
        $day = Carbon::create($params['begin_date']);
        $endDay = Carbon::create($params['end_date']);
        for ($i = 1; $day->lte($endDay); $i++) {
            $date = $day->format('Y-m-d');
            $orderCount = collect($orderList)->where('execution_date', $date)->count();
            $periodInfo[$i] = ['date' => $date, 'order' => $orderCount];
            $day = $day->addDay();
        }
        $data[] = [
            'merchant_name' => '总计',
            'graph' => array_values($periodInfo),
        ];
        unset($day, $endDay);
        return $data;
    }

    //时间段订单统计

    /**
     * 分货主时间段统计
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
        $orderList = parent::getList([
            'merchant_id' => $merchantId,
            'status' => ['in', [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3]],
            'execution_date' => ['between', [$params['begin_date'], $params['end_date']]],
        ], ['execution_date'], false)->groupBy('execution_date');
        $day = Carbon::create($params['begin_date']);
        $endDay = Carbon::create($params['end_date']);
        for ($i = 1; $day->lte($endDay); $i++) {
            $date = $day->format('Y-m-d');
            $orderCount = count($orderList[$date] ?? []);
            $periodInfo[$i] = ['date' => $date, 'order' => $orderCount];
            $day = $day->addDay();
        }
        return array_values($periodInfo);
    }

    /**
     * 货主订单统计
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
            $data[$k]['total_recharge'] = number_format_simple($data[$k]['total_recharge'], 2);
        }
        $total['total_recharge'] = number_format_simple($total['total_recharge'], 2);
        $data[] = $total;
        return $data;
    }

    /**
     * 货主饼图统计
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

    /**
     * 今日概览
     * @return array
     */
    public function todayOverview()
    {
        $now = date('Y-m-d');
        $trackingOrderCount = $this->getTrackingOrderService()->count(['execution_date' => $now, 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3]]]);
        $noOutTrackingOrderCount = $this->getTrackingOrderService()->count(['execution_date' => $now, 'out_status' => 2, 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3]]]);
        $batchCount = $this->getBatchService()->count(['execution_date' => $now, 'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT]]]);
        $tourCount = $this->getBatchService()->count(['execution_date' => $now, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]]]);
        return [
            'tracking_order_count' => $trackingOrderCount,
            'no_out_tracking_order_count' => $noOutTrackingOrderCount,
            'batch_count' => $batchCount,
            'tour_count' => $tourCount
        ];
    }

    /**
     * 预约任务
     * @return array
     */
    public function reservation()
    {
        $data = [];
        $now = date('Y-m-d');
        $dateList = $this->getTrackingOrderService()->getList(['execution_date' => ['>', $now]], ['*'], false)->pluck('execution_date')->toArray();
        $dateList = array_unique($dateList);
        if (empty($dateList)) {
            return [];
        }
        foreach ($dateList as $k => $v) {
            $data[$k]['date'] = $v;
            $data[$k]['tour'] = $this->getTourService()->count(['execution_date' => $v]);
            $data[$k]['batch'] = $this->getBatchService()->count(['execution_date' => $v, 'status' => ['<>', BaseConstService::BATCH_CANCEL]]);
            $data[$k]['tracking_order'] = $this->getTrackingOrderService()->count(['execution_date' => $v, 'status' => ['in',
                [
                    BaseConstService::TRACKING_ORDER_STATUS_1,
                    BaseConstService::TRACKING_ORDER_STATUS_2,
                    BaseConstService::TRACKING_ORDER_STATUS_3,
                    BaseConstService::TRACKING_ORDER_STATUS_4,
                    BaseConstService::TRACKING_ORDER_STATUS_5,
                ]]]);
            if ($data[$k]['tour'] == 0 && $data[$k]['batch'] == 0 && $data[$k]['tracking_order'] == 0) {
                unset($data[$k]);
            }
        }
        $data = array_values($data);
        return $data;
    }

    public function flow()
    {
        $flowList = ConstTranslateTrait::formatList(ConstTranslateTrait::$flowList);
        $permissionList = auth()->user()->getAllPermissions()->filter(function ($permission, $key) {
            return ($permission['type'] == 2);
        })->keyBy('route_as')->toArray();
        if (empty($permissionList)) return $flowList;
        foreach ($flowList as $k => $v) {
            if (!empty($permissionList[$v['id']])) {
                $flowList[$k]['permission'] = BaseConstService::YES;
            }
        }
        $flowList = array_create_index($flowList, 'id');
        $flowRouteList = ConstTranslateTrait::$flowRouteList;
        foreach ($flowList as &$shortCut) {
            $shortCut['route'] = $flowRouteList[$shortCut['id']] ?? '';
        }
        return array_values($flowList);
    }

    /**
     * 获取快捷方式列表
     * @return array
     */
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
        $shortCutList = array_create_index($shortCutList, 'id');
        $shortCutRouteList = ConstTranslateTrait::$shortCutRouteList;
        $shortCutIconList = ConstTranslateTrait::$shortCutIconList;
        foreach ($shortCutList as &$shortCut) {
            $shortCut['route'] = $shortCutRouteList[$shortCut['id']] ?? '';
            $shortCut['icon'] = $shortCutIconList[$shortCut['id']] ?? '';
        }
        return array_values($shortCutList);
    }

    /**
     * 任务结果概览
     * @param $executionDate
     * @return array
     */
    public function resultOverview($executionDate)
    {
        $successWhere = ['status' => BaseConstService::TRACKING_ORDER_STATUS_5];
        $cancelWhere = ['status' => BaseConstService::TRACKING_ORDER_STATUS_6];
        if (!empty($executionDate)) {
            $successWhere['execution_date'] = $cancelWhere['execution_date'] = $executionDate;
        }
        $trackingOrderSuccessCount = $this->getTrackingOrderService()->count($successWhere);
        $trackingOrderCancelCount = $this->getTrackingOrderService()->count($cancelWhere);
        $batchSuccessCount = $this->getBatchService()->count($successWhere);
        $batchCancelCount = $this->getBatchService()->count($cancelWhere);
        $packageSuccessCount = $this->getTrackingOrderPackageService()->count($successWhere);
        $packageCancelCount = $this->getTrackingOrderPackageService()->count($cancelWhere);
        return [
            'tracking_order_success_count' => $trackingOrderSuccessCount,
            'tracking_order_cancel_count' => $trackingOrderCancelCount,
            'batch_success_count' => $batchSuccessCount,
            'batch_cancel_count' => $batchCancelCount,
            'package_success_count' => $packageSuccessCount,
            'package_cancel_count' => $packageCancelCount
        ];
    }

    /**
     * 订单分析
     * @return array
     */
    public function orderAnalysis()
    {
        $todayOrderCount = $this->getOrderService()->count(['execution_date' => date('Y-m-d')]);
        $orderCount = $this->getOrderService()->count();
        $orderSuccessCount = $this->getOrderService()->count(['status' => BaseConstService::ORDER_STATUS_3]);
        $orderCancelCount = $this->getOrderService()->count(['status' => BaseConstService::ORDER_STATUS_4]);
        return [
            'today_order_count' => $todayOrderCount,
            'order_count' => $orderCount,
            'order_success_count' => $orderSuccessCount,
            'order_cancel_count' => $orderCancelCount,
        ];
    }
}
