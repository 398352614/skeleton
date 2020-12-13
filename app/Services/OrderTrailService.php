<?php
/**
 * 订单轨迹
 * User: long
 * Date: 2020/11/2
 * Time: 14:53
 */

namespace App\Services;

use App\Http\Resources\Api\OrderTrailResource;
use App\Jobs\AddData;
use App\Jobs\AddTrail;
use App\Models\Order;
use App\Models\OrderTrail;
use App\Models\TrackingOrder;

class OrderTrailService extends BaseService
{
    public static $selectFields = ['type', 'company_id', 'merchant_id', 'execution_date', 'order_no', 'tracking_order_no'];

    public $filterRules = [
        'order_no' => ['=', 'order_no'],
    ];

    public function __construct(OrderTrail $orderTrail)
    {
        parent::__construct($orderTrail, OrderTrailResource::class, OrderTrailResource::class);
    }

    /**
     * 轨迹查询
     * @param $orderNo
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($orderNo)
    {
        return parent::getList(['order_no' => $orderNo], ['*'], false);
    }

    /**
     * 按取件线路批量新增
     * @param $tour
     * @param int $action
     * @param null $params
     */
    public static function storeByTour($tour, int $action, $params = null)
    {
        $trackingOrderList = TrackingOrder::query()->select(self::$selectFields)->where('tour_no', $tour['tour_no'])->get()->toArray();
        !empty($trackingOrderList) && self::storeAllByOrderList($trackingOrderList, $action, $params = null);
    }

    /**
     * 按站点批量新增
     * @param $batch
     * @param int $action
     * @param null $params
     */
    public static function storeByBatch($batch, int $action, $params = null)
    {
        $trackingOrderList = TrackingOrder::query()->select(self::$selectFields)->where('batch_no', $batch['batch_no'])->get()->toArray();
        !empty($trackingOrderList) && self::storeAllByOrderList($trackingOrderList, $action, $params);
    }

    /**
     * 批量新增
     * @param array $trackingOrderList
     * @param int $action
     * @param null $params
     */
    public static function storeAllByOrderList(array $trackingOrderList, int $action, $params = null)
    {
        foreach ($trackingOrderList as $key => $trackingOrder) {
            self::OrderStatusChangeCreateTrail($trackingOrder, $action, $params ?? $trackingOrder);
        }
    }

    public static function OrderStatusChangeCreateTrail(array $trackingOrder, int $action, $params = [])
    {
        $type = [1 => '取件', 2 => '派件'];
        //根据不同的类型生成不同的content
        switch ($action) {
            case BaseConstService::ORDER_TRAIL_CREATED:                 // 订单创建
                $content = sprintf("订单创建成功，订单号[%s]，生成运单号[%s]", $trackingOrder['order_no'], $trackingOrder['tracking_order_no']);
                break;
            case BaseConstService::ORDER_TRAIL_LOCK:               // 订单锁定
                $content = sprintf("订单[%s]准备中，货品已锁定", $type[$trackingOrder['type']]);
                break;
            case BaseConstService::ORDER_TRAIL_UNLOCK:               // 订单解锁
                $content = sprintf("订单[%s]货品解锁", $type[$trackingOrder['type']]);
                break;
            case BaseConstService::ORDER_TRAIL_START:             //订单开始
                $content = sprintf("订单[%s]开始", $type[$trackingOrder['type']]);
                break;
            case BaseConstService::ORDER_TRAIL_FINISH:                //订单完成
                $content = sprintf("订单[%s]完成", $type[$trackingOrder['type']]);
                break;
            case BaseConstService::ORDER_TRAIL_FAIL:            // 订单失败
                $content = sprintf("订单[%s]失败", $type[$trackingOrder['type']]);
                break;
            case BaseConstService::ORDER_TRAIL_RESTART:     // 订单重启
                $content = sprintf("订单[%s]运单创建，生成运单号[%s]，日期[%s]", $type[$trackingOrder['type']], $trackingOrder['tracking_order_no'], $trackingOrder['execution_date']);
                break;
            case BaseConstService::ORDER_TRAIL_UPDATE:          // 订单修改
                $content = sprintf("订单[%s]日期修改，日期从[%s]更变为[%s]", $type[$trackingOrder['type']], $params['execution_date'], $trackingOrder['execution_date']);
                break;
            case BaseConstService::ORDER_TRAIL_CLOSED:                     // 订单关闭
                $content = '订单关闭';
                break;
            case BaseConstService::ORDER_TRAIL_DELETE:                     // 订单删除
                $content = '订单删除';
                break;
            default:
                $content = '未定义的状态';
                break;
        }
        $now = now();
        $data = [
            'company_id' => $trackingOrder['company_id'],
            'order_no' => $trackingOrder['order_no'],
            'merchant_id' => $trackingOrder['merchant_id'],
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now
        ];
        !empty($trackingOrder['merchant_id']) && $data['merchant_id'] = $trackingOrder['merchant_id'];
        dispatch(new AddData($data, Order::query()));
    }
}
