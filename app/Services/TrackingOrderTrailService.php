<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\TrackingOrderTrailResource;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderTrail;
use App\Jobs\AddData;

class TrackingOrderTrailService extends BaseService
{

    public static $selectFields = ['company_id', 'merchant_id', 'order_no', 'tracking_order_no'];

    public $filterRules = [
        'tracking_order_no' => ['=', 'tracking_order_no'],
        'order_no' => ['=', 'order_no'],
    ];

    public function __construct(TrackingOrderTrail $trackingOrderTrail)
    {
        parent::__construct($trackingOrderTrail, TrackingOrderTrailResource::class, TrackingOrderTrailResource::class);
    }

    public static function storeByTour($tour, int $action)
    {
        $trackingOrderList = TrackingOrder::query()->select(self::$selectFields)->where('tour_no', $tour['tour_no'])->get()->toArray();
        !empty($trackingOrderList) && self::storeAllByTrackingOrderList($trackingOrderList, $action, $tour);
    }

    public static function storeByBatch($batch, int $action)
    {
        $trackingOrderList = TrackingOrder::query()->select(self::$selectFields)->where('batch_no', $batch['batch_no'])->get()->toArray();
        !empty($trackingOrderList) && self::storeAllByTrackingOrderList($trackingOrderList, $action, $batch);
    }


    public static function storeAllByTrackingOrderList(array $trackingOrderList, int $action, $params = null)
    {
        foreach ($trackingOrderList as $key => $trackingOrder) {
            self::TrackingOrderStatusChangeCreateTrail($trackingOrder, $action, $params ?? $trackingOrder);
        }
    }

    public static function TrackingOrderStatusChangeCreateTrail(array $trackingOrder, int $action, $params = [])
    {
        //根据不同的类型生成不同的content
        $content = '';
        switch ($action) {
            case BaseConstService::TRACKING_ORDER_TRAIL_CREATED:                 // 运单创建
                $content = sprintf("运单创建成功,运单号[%s]", $trackingOrder['tracking_order_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH:               // 加入站点
                $content = sprintf("运单已加入站点[%s]", $params['batch_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_BATCH:             //移除站点
                $content = sprintf("运单从站点[%s]中移除", $params['batch_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR:                //加入取件线路
                $content = sprintf("运单加入取件线路[%s]", $params['tour_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR:              //加入取件线路
                $content = sprintf("运单从取件线路[%s]移除", $params['tour_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_ASSIGN_DRIVER:            // 已分配司机
                $content = sprintf("运单分配司机，司机姓名[%s]，联系方式[%s]", $params['driver_name'], $params['driver_phone']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_ASSIGN_DRIVER:     // 已分配司机
                $content = '取消分配司机';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_REVENUE_OUTLETS:          // 加入网点
                $content = '包裹已收入网点';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_LOCK:                     // 待出库
                $content = '运单装货中';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_UN_LOCK:                 // 取消待出库
                $content = '运单取消装货';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_DELIVERING:               // 派送中
                $content = '运单派送中';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_DELIVERED:                // 运单已投递
                $content = '派件成功';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER:           // 运单已取消取派
                $content = '取消派件';
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_DELETE:                   // 运单已被删除
                $content = '运单已被删除';
                break;

            default:
                $content = '未定义的状态';
                break;
        }
        $now = now();
        $data = [
            'company_id' => $trackingOrder['company_id'],
            'tracking_order_no' => $trackingOrder['tracking_order_no'],
            'order_no' => $trackingOrder['order_no'],
            'merchant_id' => $trackingOrder['merchant_id'],
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now
        ];
        !empty($trackingOrder['merchant_id']) && $data['merchant_id'] = $trackingOrder['merchant_id'];
        dispatch(new AddData('tracking-order-trail', $data));
    }

    /**
     * 物流信息追踪
     * @param $trackingOrderNo
     * @return array
     * @throws BusinessLogicException
     */
    public function index($trackingOrderNo)
    {
        return parent::getList(['tracking_order_no' => $trackingOrderNo], ['*'], false);
    }

    public function create($data)
    {
        return parent::create($data);
    }
}
