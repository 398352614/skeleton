<?php

namespace App\Listeners;

use App\Events\Interfaces\OrderTrailChanged;
use App\Models\OrderTrail;
use App\Services\BaseConstService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderTrail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderTrailChanged  $event
     * @return void
     */
    public function handle(OrderTrailChanged $event)
    {
        //根据不同的类型生成不同的content
        $content = '';

        switch ($event->gettype()) {
            case BaseConstService::ORDER_TRAIL_CREATED:  // 订单创建
                $content = '订单已创建';
                break;
            case BaseConstService::ORDER_TRAIL_ASSIGN_DRIVER:  // 已分配司机
                $content = '已分配司机';
                break;
            case BaseConstService::ORDER_TRAIL_REVENUE_OUTLETS: // 已收入网点
                $content = '包裹已收入网点';
                break;
            case BaseConstService::ORDER_TRAIL_DELIVERED: // 订单已投递
                $content = '派件成功';
                break;

            default:
                $content = '未定义的状态';
                break;
        }
        $order = $event->getOrder();

        OrderTrail::create([
            'company_id'    =>  $order->company_id,
            'order_no'      =>  $order->order_no,
            'content'    =>  $content,
        ]);
    }
}
