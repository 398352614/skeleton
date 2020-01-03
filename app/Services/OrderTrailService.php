<?php

use App\Models\Order;
use App\Models\OrderTrail;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class OrderTrailService extends BaseService
{
    public function __construct(OrderTrail $orderTrail)
    {
        $this->model = $orderTrail;
    }

    public static function OrderStatusChangeUseOrderIDs(array $orderIds, int $action)
    {
        $orders = Order::whereIn('id', $orderIds)->get();

        foreach ($orders as $key => $order) {
            self::OrderStatusChangeCreateTrail($order, $action);
        }
    }

    public static function OrderStatusChangeUseOrderCollection(Collection $orders, int $action)
    {
        foreach ($orders as $key => $order) {
            self::OrderStatusChangeCreateTrail($order, $action);
        }
    }

    public static function OrderStatusChangeCreateTrail(Order $order, int $action)
    {
        //根据不同的类型生成不同的content
        $content = '';

        switch ($action) {
            case BaseConstService::ORDER_TRAIL_CREATED:  // 订单创建
                $content = '订单已创建';
                break;
            case BaseConstService::ORDER_TRAIL_JOIN_BATCH:  // 加入站点
                $content = '已加入批次';
                break;
            case BaseConstService::ORDER_TRAIL_ASSIGN_DRIVER:  // 已分配司机
                $content = '已分配司机';
                break;
            case BaseConstService::ORDER_TRAIL_REVENUE_OUTLETS:  // 加入网点
                $content = '包裹已收入网点';
                break;
            case BaseConstService::ORDER_TRAIL_LOCK:  // 待出库
                $content = '订单装货中';
                break;
            case BaseConstService::ORDER_TRAIL_DELIVERING: // 派送中
                $content = '订单派送中';
                break;
            case BaseConstService::ORDER_TRAIL_DELIVERED: // 订单已投递
                $content = '派件成功';
                break;
            case BaseConstService::ORDER_TRAIL_CANNEL_DELIVER: // 订单已取消取派
                $content = '取消派件';
                break;

            default:
                $content = '未定义的状态';
                break;
        }

        OrderTrail::create([
            'company_id'    =>  $order->company_id,
            'order_no'      =>  $order->order_no,
            'content'    =>  $content,
        ]);
    }
}
