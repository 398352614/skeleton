<?php

namespace App\Services;

use App\Http\Resources\OrderTrailResource;
use App\Models\Order;
use App\Models\OrderTrail;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use phpDocumentor\Reflection\Types\Parent_;

class OrderTrailService extends BaseService
{

    public static $selectFields = ['company_id', 'merchant_id', 'order_no'];

    public $filterRules = [
        'order_no' => ['=', 'order_no'],
    ];

    public function __construct(OrderTrail $orderTrail)
    {
        parent::__construct($orderTrail, OrderTrailResource::class, OrderTrailResource::class);
    }

    public static function storeByTourNo(string $tourNo, int $action)
    {
        $orderList = Order::query()->select(self::$selectFields)->where('tour_no', $tourNo)->get()->toArray();
        !empty($orderList) && self::storeAllByOrderList($orderList, $action);
    }

    public static function storeByBatchNo(string $batchNo, int $action)
    {
        $orderList = Order::query()->select(self::$selectFields)->where('batch_no', $batchNo)->get()->toArray();
        !empty($orderList) && self::storeAllByOrderList($orderList, $action);
    }


    public static function storeAllByOrderList(array $orderList, int $action)
    {
        foreach ($orderList as $key => $order) {
            self::OrderStatusChangeCreateTrail($order, $action);
        }
    }

    public static function OrderStatusChangeCreateTrail(array $order, int $action)
    {
        //根据不同的类型生成不同的content
        $content = '';

        switch ($action) {
            case BaseConstService::ORDER_TRAIL_CREATED:                 // 订单创建
                $content = '订单已创建';
                break;
            case BaseConstService::ORDER_TRAIL_JOIN_BATCH:               // 加入站点
                $content = '已加入站点';
                break;
            case BaseConstService::ORDER_TRAIL_REMOVE_BATCH:             //移除站点
                $content = '移除站点';
                break;
            case BaseConstService::ORDER_TRAIL_JOIN_TOUR:                //加入取件线路
                $content = '加入取件线路';
                break;
            case BaseConstService::ORDER_TRAIL_REMOVE_TOUR:              //加入取件线路
                $content = '移除取件线路';
                break;
            case BaseConstService::ORDER_TRAIL_ASSIGN_DRIVER:            // 已分配司机
                $content = '已分配司机';
                break;
            case BaseConstService::ORDER_TRAIL_CANCEL_ASSIGN_DRIVER:     // 已分配司机
                $content = '取消分配司机';
                break;
            case BaseConstService::ORDER_TRAIL_REVENUE_OUTLETS:          // 加入网点
                $content = '包裹已收入网点';
                break;
            case BaseConstService::ORDER_TRAIL_LOCK:                     // 待出库
                $content = '订单装货中';
                break;
            case BaseConstService::ORDER_TRAIL_UN_LOCK:                 // 取消待出库
                $content = '订单取消装货';
                break;
            case BaseConstService::ORDER_TRAIL_DELIVERING:               // 派送中
                $content = '订单派送中';
                break;
            case BaseConstService::ORDER_TRAIL_DELIVERED:                // 订单已投递
                $content = '派件成功';
                break;
            case BaseConstService::ORDER_TRAIL_CANCEL_DELIVER:           // 订单已取消取派
                $content = '取消派件';
                break;
            case BaseConstService::ORDER_TRAIL_DELETE:                   // 订单已被删除
                $content = '订单已被删除';
                break;

            default:
                $content = '未定义的状态';
                break;
        }
        $data = [
            'company_id' => $order['company_id'],
            'order_no' => $order['order_no'],
            'merchant_id' => $order['merchant_id'],
            'content' => $content,
        ];
        !empty($order['merchant_id']) && $data['merchant_id'] = $order['merchant_id'];
        OrderTrail::query()->create($data);
    }

    public function getNoPageList()
    {
        return parent::getList(['order_no' => $this->formData['order_no']]);
    }
}
