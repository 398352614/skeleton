<?php
/**
 * 订单轨迹
 * User: long
 * Date: 2020/11/2
 * Time: 14:53
 */

namespace App\Services\Merchant;

use App\Http\Resources\Api\OrderTrailResource;
use App\Models\OrderTrail;
use App\Services\Admin\BaseService;
use App\Services\BaseConstService;

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


    public function merchantHome()
    {
        $this->formData['per_page'] = 5;
        $this->orderBy = ['id' => 'desc'];
        $this->query->whereIn('type', [BaseConstService::ORDER_TRAIL_START, BaseConstService::ORDER_TRAIL_FINISH]);
        $data = parent::getPageList();
        if (app('request')->header('language') == 'en') {
            foreach ($data as $x => $y) {
                $content = $y['content'];
                $content = str_replace('取件', 'Pick-up', $content);
                $content = str_replace('派件', 'Delivery', $content);
                $data[$x]['content'] = $content;
            }
        }
        return $data;

    }

    public function show($id)
    {
        $order = $this->getOrderService()->getInfo(['id' => $id], ['*'], true);
        if (!empty($order)) {
            if ($order['type'] == BaseConstService::ORDER_TYPE_1) {
                $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_1], ['*'], ['id' => 'desc']);
                $order['pickup_warehouse_lon'] = $trackingOrder['warehouse_lon'] ?? '';
                $order['pickup_warehouse_lat'] = $trackingOrder['warehouse_lat'] ?? '';
            } elseif ($order['type'] == BaseConstService::ORDER_TYPE_2) {
                $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2], ['*'], ['id' => 'desc']);
                $order['pie_warehouse_lon'] = $trackingOrder['warehouse_lon'] ?? '';
                $order['pie_warehouse_lat'] = $trackingOrder['warehouse_lat'] ?? '';
            } elseif ($order['type'] == BaseConstService::ORDER_TYPE_3) {
                $pickupTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_1], ['*'], ['id' => 'desc']);
                $order['pickup_warehouse_lon'] = $pickupTrackingOrder['warehouse_lon'] ?? '';
                $order['pickup_warehouse_lat'] = $pickupTrackingOrder['warehouse_lat'] ?? '';
                $pieTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2], ['*'], ['id' => 'desc']);
                $order['pie_warehouse_lon'] = $pieTrackingOrder['warehouse_lon'] ?? '';
                $order['pie_warehouse_lat'] = $pieTrackingOrder['warehouse_lat'] ?? '';
            }
            $list = parent::getList(['order_no' => $order['order_no']], ['*'], true, [], ['id' => 'asc']);
            $a = 0;
            foreach ($list as $k => $v) {
                if ($list[$k]['type'] == BaseConstService::ORDER_TRAIL_FINISH) {
                    $a = $a + 1;
                }
                if ($a == 2) {
                    $list[$k]['type'] = BaseConstService::ORDER_TRAIL_ALL_FINISH;
                    $a = 0;
                }
            }
            $order['trail_list'] = array_reverse(collect($list)->toArray());
            return $order;
        }

    }
}
