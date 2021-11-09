<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\TrackingOrderTrailResource;
use App\Jobs\AddData;
use App\Models\TrackingOrder;
use App\Models\TrackingOrderTrail;

class TrackingOrderTrailService extends \App\Services\Admin\BaseService
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

    /**
     * 手动新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['operator'] = auth()->user()->fullname;
        $row = parent::create($params);
        if ($row == false) {
            throw new BusinessLogicException('新增失败');
        }

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


    /**
     * 批量新增
     * @param array $trackingOrderList
     * @param int $action
     * @param null $params
     */
    public static function storeAllByTrackingOrderList(array $trackingOrderList, int $action, $params = null)
    {
        $data = [];
        foreach ($trackingOrderList as $key => $trackingOrder) {
            $data[] = self::trackingOrderStatusChangeCreateTrail($trackingOrder, $action, $params ?? $trackingOrder, true);
        }
        dispatch(new AddData('tracking-order-trail', $data));
    }

    public static function trackingOrderStatusChangeCreateTrail(array $trackingOrder, int $action, $params = [], $list = false)
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
            case BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR:                //加入线路任务
                $content = sprintf("运单加入线路任务[%s]", $params['tour_no']);
                break;
            case BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR:              //加入线路任务
                $content = sprintf("运单从线路任务[%s]移除", $params['tour_no']);
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
            'type' => $action,
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now
        ];
        if ($list == false) {
            dispatch(new AddData('tracking-order-trail', $data));
        } else {
            return $data;
        }
    }

    /**
     * 物流信息追踪
     * @param $trackingOrderNo
     * @return array
     * @throws BusinessLogicException
     */
    public function index($trackingOrderNo)
    {
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['tracking_order_no' => $trackingOrderNo], ['*'], false);
        if (empty($trackingOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingOrder['tracking_order_trail_list'] = parent::getList(['tracking_order_no' => $trackingOrderNo], ['*'], false);
        $trackingOrder['package_list'] = $this->getPackageService()->getList(['tracking_order_no' => $trackingOrderNo], ['*'], false);
        $trackingOrder['material_list'] = $this->getMaterialService()->getList(['tracking_order_no' => $trackingOrderNo], ['*'], false);
        return $trackingOrder;
    }

    public function create($data)
    {
        return parent::create($data);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }
}
