<?php
/**
 * 订单服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:39
 */

namespace App\Services\Admin;

use App\Events\OrderCancel;
use App\Events\OrderExecutionDateUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\OrderAgainResource;
use App\Http\Resources\Api\Admin\OrderInfoResource;
use App\Http\Resources\Api\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Models\TrackingOrder;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Services\OrderTrailService;
use App\Traits\BarcodeTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use App\Traits\PrintTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class OrderService extends BaseService
{
    use ImportTrait, LocationTrait, CountryTrait, ExportTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'out_group_order_no,order_no,out_order_no,out_user_id' => ['like', 'keyword'],
//        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'source' => ['=', 'source'],
        'tour_no' => ['like', 'tour_no'],
        'out_user_id' => ['like', 'out_user_id'],
        'tracking_order_no' => ['like', 'tracking_order_no'],
        'out_order_no' => ['like', 'out_order_no'],
        'out_group_order_no' => ['like', 'out_group_order_no'],
        'order_no' => ['like', 'order_no'],
    ];

    public $headings = [
        'order_no',
        'merchant_id',
        'type',
        'merchant_name',
        'status',
        'out_user_id',
        'out_order_no',
        'sender_post_code',
        'sender_house_number',
        'sender_execution_date',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_execution_date',
        'package_name',
        'package_quantity',
        'material_name',
        'material_quantity',
        'replace_amount',
        'sticker_amount',
        'settlement_amount',
        'created_at'
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Order $order)
    {
        parent::__construct($order, OrderResource::class, OrderInfoResource::class);
    }

    /**
     * 查询初始化
     * @return array
     */
    public function initIndex()
    {
        $data = [];
        $data['source_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderSourceList);
        $data['status_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderStatusList);
        $data['type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderTypeList);
        return $data;
    }

    /**
     * 订单统计
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function orderCount($params)
    {
        $type = $params['type'] ?? 0;
        return [
            BaseConstService::ORDER_STATUS_0 => $this->singleOrderCount($type),
            BaseConstService::ORDER_STATUS_1 => $this->singleOrderCount($type, BaseConstService::ORDER_STATUS_1),
            BaseConstService::ORDER_STATUS_2 => $this->singleOrderCount($type, BaseConstService::ORDER_STATUS_2),
            BaseConstService::ORDER_STATUS_3 => $this->singleOrderCount($type, BaseConstService::ORDER_STATUS_3),
            BaseConstService::ORDER_STATUS_4 => $this->singleOrderCount($type, BaseConstService::ORDER_STATUS_4),
            BaseConstService::ORDER_STATUS_5 => $this->singleOrderCount($type, BaseConstService::ORDER_STATUS_5),
        ];
    }

    /**
     * 单项订单统计
     * @param $type
     * @param $status
     * @param null $exceptionType
     * @return int
     */
    public function singleOrderCount($type, $status = null, $exceptionType = null)
    {
        $where = [];
        if (!empty($status)) {
            $where = ['status' => $status];
        }
        if (!empty($type)) {
            $where = array_merge($where, ['type' => $type]);
        }
        if (!empty($exceptionType)) {
            $where = array_merge($where, ['exception_label' => $exceptionType]);
        }
        return parent::count($where);
    }

    public function getPageList()
    {
        if (!empty($this->formData['exception_label']) && $this->formData['exception_label'] == 2) {
            $cancelTrackingOrderList = $this->getTrackingOrderService()->getList(['status' => BaseConstService::TRACKING_ORDER_STATUS_6], ['*'], false);
            if (!empty($cancelTrackingOrderList)) {
                $cancelTrackingOrderList = $cancelTrackingOrderList->pluck('tracking_order_no')->toArray();
            }
            $cancelTrackingOrderList[] = '';
            $this->query->where('status', BaseConstService::ORDER_STATUS_2)->WhereIn('tracking_order_no', $cancelTrackingOrderList);
        }
        if (!empty($this->formData['post_code'])) {
            $trackingOrderList = $this->getTrackingOrderService()->getList(['place_post_code' => ['like', $this->formData['post_code']]]);
            if (!$trackingOrderList->isEmpty()) {
                $trackingOrderList = $trackingOrderList->pluck('order_no')->toArray();
                $this->query->whereIn('order_no', $trackingOrderList);
            }
        }
        $list = parent::getPageList();
        foreach ($list as $k => $v) {
            $list[$k]['tracking_order_count'] = $this->getTrackingOrderService()->count(['order_no' => $v['order_no']]);
            $list[$k]['exception_label'] = BaseConstService::BATCH_EXCEPTION_LABEL_1;
            $list[$k]['tracking_order_status'] = 0;
            $list[$k]['tracking_order_status_name'] = '';
            $trackingOrder = $this->getTrackingOrderService()->getList(['order_no' => $v['order_no']], ['id', 'type', 'status'], false, [], ['id' => 'desc']);
            if (!empty($trackingOrder) && !empty($trackingOrder[0])) {
                $list[$k]['tracking_order_status_name'] = __($trackingOrder[0]->type_name) . '-' . __($trackingOrder[0]->status_name);
                $list[$k]['tracking_order_status'] = $trackingOrder[0]['status'];
            } elseif ($list[$k]['status'] !== BaseConstService::ORDER_STATUS_5) {
                $list[$k]['exception_label'] = BaseConstService::BATCH_EXCEPTION_LABEL_2;
                $list[$k]['tracking_order_status_name'] = __('运单未创建');
            }
            if ($list[$k]['status'] == BaseConstService::ORDER_STATUS_2 && $trackingOrder[0]['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
                $list[$k]['exception_label'] = BaseConstService::BATCH_EXCEPTION_LABEL_2;
            }
        }
        return $list;
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $dbOrder = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('订单不存在！');
        }
        $dbOrder['package_list'] = $this->getPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['material_list'] = $this->getMaterialService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['amount_list'] = $this->getOrderAmountService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        return $dbOrder;
    }

    /**
     * 获取订单的运单列表
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getTrackingOrderList($id)
    {
        $dbOrder = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('订单不存在！');
        }
        $dbTrackingOrder = $this->getTrackingOrderService()->getList(['order_no' => $dbOrder->order_no], ['*'], false);
        foreach ($dbTrackingOrder as $k => $v) {
            if (!empty($v->tracking_order_no)) {
                $dbTrackingOrder[$k]['package_list'] = $this->getTrackingOrderPackageService()->getList(['tracking_order_no' => $v->tracking_order_no], ['*'], false)->toArray();
                $dbTrackingOrder[$k]['material_list'] = $this->getTrackingOrderMaterialService()->getList(['tracking_order_no' => $v->tracking_order_no], ['*'], false)->toArray();
                $batch[$k] = $this->getBatchService()->getInfo(['batch_no' => $v['batch_no']], ['*'], false);
                if (!empty($batch[$k])) {
                    $dbTrackingOrder[$k]['sign_time'] = $batch[$k]['sign_time'];
                } else {
                    $dbTrackingOrder[$k]['sign_time'] = null;
                }
                $tour[$k] = $this->getTourService()->getInfo(['tour_no' => $v['tour_no']], ['*'], false);
                if (!empty($tour[$k])) {
                    $dbTrackingOrder[$k]['begin_time'] = $tour[$k]['begin_time'];
                } else {
                    $dbTrackingOrder[$k]['begin_time'] = null;
                }
                $dbTrackingOrder[$k]['time_list'] = [['type' => __(BaseConstService::CREATED_TIME), 'time' => (string)$v['created_at']]];
                if (!empty($dbTrackingOrder[$k]['begin_time'])) {
                    $dbTrackingOrder[$k]['time_list'] = array_merge($v['time_list'], [['type' => __(BaseConstService::BEGIN_TIME), 'time' => $v['begin_time']]]);
                }
                if (!empty($dbTrackingOrder[$k]['sign_time'])) {
                    $dbTrackingOrder[$k]['time_list'] = array_merge($v['time_list'], [['type' => __(BaseConstService::SIGN_TIME), 'time' => $v['sign_time']]]);
                }
            }
        }
        return $dbTrackingOrder;
    }

    public function getTrackingOrderTrailList($id)
    {
        $data = [];
        $order = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($order)) {
            return [];
        }
        $trackingOrderList = $this->getTrackingOrderTrailService()->getList(['order_no' => $order['order_no']], ['*'], false);
        $trackingOrderTrailList = $trackingOrderList->groupBy('tracking_order_no')->sortByDesc('id');
        foreach ($trackingOrderTrailList as $k => $v) {
            $data[$k]['tracking_order_no'] = $k;
            $data[$k]['tracking_order_trail'] = $v;
        }
        $data = array_values($data);
        return $data;
    }

    public function initStore()
    {
        $data = [];
        $data['nature_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderNatureList);
        $data['settlement_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderSettlementTypeList);
        $data['type'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderTypeList);
        $data['feature_logo_list'] = ['常温', '雪花', '风扇', '预售', '打折村', '海鲜预售'];
        return $data;
    }

    /**
     * 通过地址获取可选日期
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getAbleDateListByAddress($params)
    {
        return $this->getTrackingOrderService()->getAbleDateListByAddress($params);
    }

    /**
     * 订单新增
     * @param $params
     * @param $orderSource
     * @return array
     * @throws BusinessLogicException
     */
    public function store($params, $orderSource = BaseConstService::ORDER_SOURCE_1)
    {
        //数据验证
        $this->check($params);
        //设置订单来源
        data_set($params, 'source', $orderSource);
        /*************************************************订单新增************************************************/
        $params['order_no'] = $this->getOrderNoRuleService()->createOrderNo();
        $order = parent::create($params);
        if ($order === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        //新增订单明细列表
        $this->addAllItemList($params);
        //新增订单费用列表
        $this->addAmountList($params);
        //生成运单
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id']], ['*'], false);
        if ($merchant['below_warehouse'] == BaseConstService::YES && $params['type'] == BaseConstService::ORDER_TYPE_2) {
            $this->getTrackingPackageService()->storeByOrder($order, $merchant['warehouse_id']);
        } else {
            $tour = $this->getTrackingOrderService()->storeByOrder($order);
        }
        return [
            'id' => $order['id'],
            'order_no' => $params['order_no'],
            'out_order_no' => $params['out_order_no'] ?? '',
            'batch_no' => '',
            'tour_no' => '',
            'line' => [
                'line_id' => $tour['line_id'] ?? null,
                'line_name' => $tour['line_name'] ?? '',
            ],
            'execution_date' => $order->execution_date,
            'second_execution_date' => $order->second_execution_date ?? null
        ];
    }

    /**
     * 获取网点
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getWareHouse($params)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id']], ['*'], false);
        if ($merchant['below_warehouse'] == BaseConstService::YES && $params['type'] == BaseConstService::ORDER_TYPE_2) {
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $merchant['warehouse_id']], ['*'], false);
        } else {
            //获取线路
            $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
            //获取网点
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        }
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        return $warehouse;
    }

    /**
     * 获取继续派送(再次取派)信息
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getAgainInfo($id)
    {
        $expired = BaseConstService::NO;
        $dbOrder = parent::getInfoOfStatus(['id' => $id], false, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2], false);
        $dbTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $dbOrder['order_no']], ['*'], false, ['created_at' => 'desc']);
        $packageList = $this->getPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        if (!empty($packageList)) {
            foreach ($packageList as $k => $v) {
                if ($v['expiration_status'] == BaseConstService::EXPIRATION_STATUS_2) {
                    $expired = BaseConstService::YES;
                    break;
                }
            }
        }
        if ($expired == BaseConstService::YES) {
            $dbTrackingOrder = null;
        } elseif (empty($dbTrackingOrder)) {
            $dbTrackingOrder = null;
        } elseif ($dbOrder['type'] == BaseConstService::ORDER_TYPE_3 && $dbTrackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1) {
            $dbTrackingOrder = null;
        }
        if (!$trackingOrderType = $this->getTrackingOrderType($dbOrder->toArray(), $dbTrackingOrder)) {
            throw new BusinessLogicException('当前订单不支持再次派送，请联系管理员');
        }
        $dbOrder['tracking_order_type'] = $trackingOrderType;
        $dbOrder['tracking_order_type_name'] = ConstTranslateTrait::trackingOrderTypeList($trackingOrderType);
        $dbOrder['tracking_order_id'] = empty($dbTrackingOrder) ? -1 * intval($dbOrder['id']) : $dbTrackingOrder->id;
        $resource = OrderAgainResource::make($dbOrder)->resolve();
        return $resource;
    }

    /**
     * @param $order
     * @param TrackingOrder|null $trackingOrder
     * @return int|null
     * @params $trackingOrder
     */
    public function getTrackingOrderType($order, TrackingOrder $trackingOrder = null)
    {
        (empty($trackingOrder)) && $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], ['*'], false, ['created_at' => 'desc']);
        //1.运单不存在,直接获取运单类型
        if (empty($trackingOrder)) {
            return $this->getTrackingOrderService()->getTypeByOrderType($order['type']);
        }
        $trackingOrder = $trackingOrder->toArray();
        //2.当运单存在时，若运单不是取派完成或者取派失败,则表示已存在取派的运单
        if (!in_array($trackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6])) {
            return null;
        }
        //3.当运单存在时，若运单为取派失败,则新增取派失败的运单
        if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6) {
            return $trackingOrder['type'];
        }
        //4.当运单存在时，当运单为取派完成，若订单为取件或派件,则表示不需要新增运单
        if (in_array($order['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_2])) {
            return null;
        }
        //5.当运单存在时，当运单为取派完成，当订单为取派件,若运单为派件类型，则表示不需要新增运单
        if ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
            return null;
        }
        //6.当运单存在时，当运单为取派完成，当订单为取派件,若运单为取件类型，则表示新增派件派件运单
        return BaseConstService::TRACKING_ORDER_TYPE_2;
    }


    /**
     * 继续派送(再次取派)
     * @param $id
     * @param $params
     * @return bool
     * @throws BusinessLogicException
     */
    public function again($id, $params)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $trackingOrderType = $this->getTrackingOrderType($dbOrder);
        if (empty($trackingOrderType)) {
            throw new BusinessLogicException('当前包裹已生成对应运单');
        }
        $params = array_merge($dbOrder, $params);
        $packageStageList = $this->getPackageService()->getList(['order_no', $dbOrder], ['*'], false)->pluck('stage')->toArray();
        if (in_array(BaseConstService::PACKAGE_STAGE_2, $packageStageList)) {
            throw new BusinessLogicException('订单处于中转过程，无法再次生成运单');
        }
        return $this->getTrackingOrderService()->storeAgain($dbOrder, $params, $trackingOrderType);
    }

    /**
     * 自动终止派送
     * @param $cancelTrackingOrderList
     * @throws BusinessLogicException
     */
    public function autoEnd($cancelTrackingOrderList)
    {
        $cancelOrderNoList = array_column($cancelTrackingOrderList, 'order_no');
        $cancelOrderList = $this->filterCancelOrderNoList($cancelOrderNoList, $cancelTrackingOrderList);
        foreach ($cancelOrderList as $cancelOrder) {
            $this->endByCancelBatch($cancelOrder['id']);
        }
    }

    /**
     * 站点取消取派，订单自动终止
     * @param $id
     * @throws BusinessLogicException
     */
    public function endByCancelBatch($id)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], ['status' => BaseConstService::PACKAGE_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        OrderTrailService::orderStatusChangeCreateTrail($dbOrder, BaseConstService::ORDER_TRAIL_CLOSED);
        //取消通知
        event(new OrderCancel($dbOrder['order_no'], $dbOrder['out_order_no']));
    }

    /**
     * 终止派送
     * @param $id
     * @throws BusinessLogicException
     */
    public function end($id)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $this->getTrackingOrderService()->end($dbOrder['tracking_order_no'] ?? '');
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_4]);
        $this->stockUpdate($dbOrder);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], ['status' => BaseConstService::PACKAGE_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        OrderTrailService::orderStatusChangeCreateTrail($dbOrder, BaseConstService::ORDER_TRAIL_CLOSED);
        //取消通知
        event(new OrderCancel($dbOrder['order_no'], $dbOrder['out_order_no']));
    }


    /**
     * 过滤取派失败订单
     * @param $cancelOrderNoList
     * @param $trackingOrderList
     * @return mixed
     */
    private function filterCancelOrderNoList($cancelOrderNoList, $trackingOrderList)
    {
        if (empty($cancelOrderNoList)) return [];
        $cancelOrderList = $this->getOrderService()->getList(['order_no' => ['in', $cancelOrderNoList]], ['id', 'order_no', 'merchant_id'], false)->toArray();
        $merchantIdList = array_unique(array_column($cancelOrderList, 'merchant_id'));
        $cancelOrderList = array_create_index($cancelOrderList, 'order_no');
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', $merchantIdList]], ['*'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        foreach ($cancelOrderNoList as $key => $cancelOrderNo) {
            $type = $trackingOrderList[$cancelOrderNo]['type'];
            $merchantId = $trackingOrderList[$cancelOrderNo]['merchant_id'];
            $count = $this->getTrackingOrderService()->count(['driver_id' => ['all', null], 'order_no' => $cancelOrderNo, 'type' => $type, 'status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
            $times = ($type == BaseConstService::TRACKING_ORDER_TYPE_1) ? $merchantList[$merchantId]['pickup_count'] : $merchantList[$merchantId]['pie_count'];
            $times = intval($times);
            if ($times == 0 || ($count < $times)) {
                unset($cancelOrderList[$cancelOrderNo]);
            }
        }
        return $cancelOrderList;
    }


    /**
     * 订单批量新增
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        $list = json_decode($params['list'], true);
        for ($i = 0; $i < count($list); $i++) {
            //处理格式
            $list[$i]['package_list'] = [];
            $list[$i]['material_list'] = [];
            for ($j = 0; $j < 5; $j++) {
                if ($list[$i]['item_type_' . ($j + 1)] === 1) {
                    $list[$i]['package_list'][$j]['name'] = $list[$i]['item_name_' . ($j + 1)];
                    $list[$i]['package_list'][$j]['express_first_no'] = $list[$i]['item_number_' . ($j + 1)];
                    $list[$i]['package_list'][$j]['weight'] = $list[$i]['item_weight_' . ($j + 1)] ?? 1;
                    $list[$i]['package_list'][$j]['quantity'] = $list[$i]['item_count_' . ($j + 1)] ?? 1;
                    $list[$i]['package_list'][$j]['express_second_no'] = '';
                    $list[$i]['package_list'][$j]['out_order_no'] = '';
                    $list[$i]['package_list'] = array_values($list[$i]['package_list']);
                } elseif ($list[$i]['item_type_' . ($j + 1)] === 2) {
                    $list[$i]['material_list'][$j]['name'] = $list[$i]['item_name_' . ($j + 1)];
                    $list[$i]['material_list'][$j]['code'] = $list[$i]['item_number_' . ($j + 1)];
                    $list[$i]['material_list'][$j]['remark'] = '';
                    $list[$i]['material_list'][$j]['quantity'] = $list[$i]['item_count_' . ($j + 1)] ?? 1;
                    $list[$i]['material_list'][$j]['out_order_no'] = '';
                    $list[$i]['material_list'] = array_values($list[$i]['material_list']);
                }
            }
            $list[$i] = Arr::only($list[$i], [
                'merchant_id',
                'type',
                'place_fullname',
                'place_phone',
                'place_country',
                'place_post_code',
                'place_house_number',
                'place_address',
                'execution_date',
                'settlement_type',
                'settlement_amount',
                'replace_amount',
                'out_order_no',
                'delivery',
                'remark',
                'package_list',
                'material_list']);
            //获取经纬度
            $info = $this->getAddressService()->getInfo($list[$i]);
            $list[$i]['second_place_fullname'] = $list[$i]['second_place_phone'] = $list[$i]['second_place_country'] = $list[$i]['second_place_post_code']
                = $list[$i]['second_place_house_number'] = $list[$i]['second_place_address'] = $list[$i]['second_place_city'] = $list[$i]['second_place_street']
                = $list[$i]['place_city'] = $list[$i]['place_street'] = '';
            if (empty($info)) {
                $info = $this->getLocation($list[$i]['place_country'], $list[$i]['place_city'], $list[$i]['place_street'], $list[$i]['place_house_number'], $list[$i]['place_post_code']);
            }
            $list[$i]['lon'] = $info['lon'];
            $list[$i]['lat'] = $info['lat'];
            try {
                $this->store($list[$i], true);
            } catch (businesslogicexception $e) {
                throw new BusinessLogicException(__('第:line行：', ['line' => $i + 1]) . __($e->getMessage()));
            } catch (\Exception $e) {
                throw new BusinessLogicException(__('第:line行：', ['line' => $i + 1]) . __($e->getMessage()));
            }
        }
        return;
    }

    /**
     * 订单导入
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function import($params)
    {
        //文件验证
        $this->orderImportValidate($params);
        //文件读取
        $params['dir'] = 'order';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace(env('APP_URL') . '/storage/', 'public//', $params['path']);
        $row = collect($this->orderExcelImport($params['path'])[0])->whereNotNull('0')->toArray();
        //表头验证
        $headings = array_values(__('excel.order'));
        if ($row[0] !== $headings) {
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
        //将表头和每条数据组合
        $headings = OrderImportService::$headings;
        $data = [];
        for ($i = 2; $i < count($row); $i++) {
            $data[$i - 2] = collect($headings)->combine($row[$i])->toArray();
        }
        //数量验证
        if (count($data) > 100) {
            throw new BusinessLogicException('导入订单数量不得超过100个');
        }
        //$id = $this->orderImportLog($params);
        //数据处理
        $typeList = array_flip(ConstTranslateTrait::$trackingOrderTypeList);
        $settlementList = array_flip(ConstTranslateTrait::$orderSettlementTypeList);
        $deliveryList = ['是' => 1, '否' => 2, 'Yes' => 1, 'No' => 2];
        $itemList = array_flip(ConstTranslateTrait::$orderConfigNatureList);
        //$countryNameList = array_unique(collect($data)->pluck('place_country_name')->toArray());
        //$countryShortList = CountryTrait::getShortListByName($countryNameList);
        for ($i = 0; $i < count($data); $i++) {
            //反向翻译
            $data[$i]['type'] = $typeList[$data[$i]['type']];
            $data[$i]['settlement_type'] = $settlementList[$data[$i]['settlement_type']];
            $data[$i]['delivery'] = $deliveryList[$data[$i]['delivery']] ?? 1;
            $data[$i]['delivery_name'] = $data[$i]['delivery'] ?? __('是');
            for ($j = 0; $j < 5; $j++) {
                $data[$i]['item_type_' . ($j + 1)] = $itemList[$data[$i]['item_type_' . ($j + 1)]] ?? 0;
            }
            //日期如果是excel时间格式，转换成短横连接格式
            if (is_numeric($data[$i]['execution_date'])) {
                $data[$i]['execution_date'] = date('Y-m-d', ($data[$i]['execution_date'] - 25569) * 24 * 3600);
            }
            $data[$i] = array_map('strval', $data[$i]);
            empty($data[$i]['place_country']) && $data[$i]['place_country'] = CompanyTrait::getCountry();//填充收件人国家
        }
        return $data;
    }

    public function orderImportLog($params)
    {
        $orderImport = [
            'company_id' => auth()->user()->company_id,
            'url' => $params['path'],
            'status' => 1,
            'success_order' => 0,//$info['success'],
            'fail_order' => 0,//$info['fail'],
            'log' => ''//json_encode($info['log']),
        ];
        return OrderImportLog::query()->create($orderImport)->id;
    }

    /**
     * 验证传入参数
     * @param $params
     * @throws BusinessLogicException
     */
    public function orderImportValidate($params)
    {
        //验证$params
        $checkfile = \Illuminate\Support\Facades\Validator::make($params,
            ['file' => 'required|file|mimes:txt,xls,xlsx'],
            ['file.file' => '必须是文件']);
        if ($checkfile->fails()) {
            $error = array_values($checkfile->errors()->getMessages())[0][0];
            throw new BusinessLogicException($error, 301);
        }
    }

    /**
     * 运价计算
     * @param $order
     * @return array|void
     * @throws BusinessLogicException
     */
    public function priceCount($order)
    {
        if (empty($order['order_no'])) {
            //新增不传订单号
            return $this->check($order);
        } else {
            //修改要传订单号
            return $this->check($order, $order['order_no']);
        }
    }

    /**
     * 验证
     * @param $params
     * @param $orderNo
     * @return array|void
     * @throws BusinessLogicException
     */
    private function check(&$params, $orderNo = null)
    {
        $params['place_post_code'] = str_replace(' ', '', $params['place_post_code']);
        $fields = ['place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'];
        foreach ($fields as $v) {
            array_key_exists($v,$params) && $params[$v] = trim($params[$v]);
        }
        //获取经纬度
        $fields = ['place_house_number', 'place_city', 'place_street'];
        $params = array_merge(array_fill_keys($fields, ''), $params);
        //检验货主
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id'], 'status' => BaseConstService::MERCHANT_STATUS_1], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        //若邮编是纯数字，则认为是比利时邮编
        $country = CompanyTrait::getCountry();
        if ($country == BaseConstService::POSTCODE_COUNTRY_NL && post_code_be($params['place_post_code'])) {
            $params['place_country'] = BaseConstService::POSTCODE_COUNTRY_BE;
        }
        if ($country == BaseConstService::POSTCODE_COUNTRY_NL && Str::length($params['place_post_code']) == 5) {
            $params['place_country'] = BaseConstService::POSTCODE_COUNTRY_DE;
        }
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        if (!empty($params['package_list'])) {
            $this->getPackageService()->check($params['package_list'], $orderNo);
            //有效日日期不得早于取派日期
            foreach ($params['package_list'] as $k => $v) {
                if (!empty($v['expiration_date']) && $v['expiration_date'] < $params['execution_date']) {
                    throw new BusinessLogicException('有效日期不得小于取派日期');
                }
            }
        }
        //验证材料列表
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //填充地址
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['place_address'])) {
            $params['place_address'] = CommonService::addressFieldsSortCombine($params, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['second_place_address'])) {
            $params['second_place_address'] = CommonService::addressFieldsSortCombine($params, ['second_place_country', 'second_place_city', 'second_place_street', 'second_place_house_number', 'second_place_post_code']);
        }
        //若存在货号,则判断是否存在已预约的订单号
        if (!empty($params['out_order_no'])) {
            $where = ['out_order_no' => $params['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = parent::getInfo($where, ['id', 'order_no', 'out_order_no', 'status'], false);
            if (!empty($dbOrder)) {
                throw new BusinessLogicException('货号已存在', 1005, [], ['order_no' => $dbOrder->order_no, 'out_order_no' => $dbOrder->out_order_no, 'status' => $dbOrder->status]);
            }
        }
        //运价计算
        $this->getTrackingOrderService()->fillWarehouseInfo($params, BaseConstService::NO);
        if (config('tms.true_app_env') == 'develop' || empty(config('tms.true_app_env'))) {
            $params['distance'] = 1000;
        } else {
            $params['distance'] = TourOptimizationService::getDistanceInstance(auth()->user()->company_id)->getDistanceByOrder($params);
        }
        $params = $this->getTransportPriceService()->priceCount($params);
        //验证取件网点及派件网点是否承接取件/派件
//        if ($merchant['below_warehouse'] == BaseConstService::YES) {
//            $belowWarehouse = $this->getWareHouseService()->getInfo(['id' => $merchant['warehouse_id']], ['*'], false);
//            $belowAcceptanceTypeList = explode(',', $belowWarehouse['acceptance_type']);
//            if ($params['type'] == BaseConstService::ORDER_TYPE_1 && !in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1, $belowAcceptanceTypeList)
//            ) {
//                throw new BusinessLogicException('货主所属网点不承接取件订单');
//            } elseif ($params['type'] == BaseConstService::ORDER_TYPE_2 && !in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2, $belowAcceptanceTypeList)) {
//                throw new BusinessLogicException('货主所属网点不承接派件订单');
//            } elseif ($params['type'] == BaseConstService::ORDER_TYPE_3 &&
//                !in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1, $belowAcceptanceTypeList) &&
//                !in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2, $belowAcceptanceTypeList)
//            ) {
//                throw new BusinessLogicException('货主所属网点不承接取派订单');
//            }
//        } else {
        if ($params['type'] == BaseConstService::ORDER_TYPE_1) {
            $pickupWarehouse = $this->getLineService()->getPickupWarehouseByOrder($params);
            $pickupAcceptanceTypeList = explode(',', $pickupWarehouse['acceptance_type']);
            if (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1, $pickupAcceptanceTypeList)
            ) {
                throw new BusinessLogicException('该发件人地址所属区域，网点不承接取件订单');
            }
        } elseif ($params['type'] == BaseConstService::ORDER_TYPE_2) {
            $pieWarehouse = $this->getLineService()->getPickupWarehouseByOrder($params);//特殊处理
            $pieAcceptanceTypeList = explode(',', $pieWarehouse['acceptance_type']);
            if (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2, $pieAcceptanceTypeList)) {
                throw new BusinessLogicException('该收件人地址所属区域，网点不承接派件订单');
            }
        } elseif ($params['type'] == BaseConstService::ORDER_TYPE_3) {
            $pickupWarehouse = $this->getLineService()->getPickupWarehouseByOrder($params);
            $pieWarehouse = $this->getLineService()->getPieWarehouseByOrder($params);
            $pickupAcceptanceTypeList = explode(',', $pickupWarehouse['acceptance_type']);
            $pieAcceptanceTypeList = explode(',', $pieWarehouse['acceptance_type']);
            if (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1, $pickupAcceptanceTypeList)) {
                throw new BusinessLogicException('该发件人地址所属区域，网点不承接取件订单');
            }
            if (!in_array(BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2, $pieAcceptanceTypeList)) {
                throw new BusinessLogicException('该收件人地址所属区域，网点不承接派件订单');
            }
        }
//        }
        return $params;
    }

    /**
     * 添加货物列表
     * @param $params
     * @param int $status
     * @throws BusinessLogicException
     */
    private
    function addAllItemList($params, $status = BaseConstService::ORDER_STATUS_1)
    {
        $relationship = ['雪花' => '冷冻', '风扇' => '风房'];
        //若存在包裹列表,则新增包裹列表
        if (!empty($params['package_list'])) {
            foreach ($params['package_list'] as $k => $v) {
                if (!empty($params['package_list'][$k]['feature_logo']) && in_array($params['package_list'][$k]['feature_logo'], array_keys($relationship))) {
                    $params['package_list'][$k]['feature_logo'] = $relationship[$params['package_list'][$k]['feature_logo']];
                }
                if (empty($params['package_list'][$k]['express_second_no'])) {
                    $params['package_list'][$k]['express_second_no'] = '';
                }
            }
            $packageList = collect($params['package_list'])->map(function ($item, $key) use ($params, $status) {
                $collectItem = collect($item)->only(['name', 'express_first_no', 'express_second_no', 'out_order_no', 'feature_logo', 'weight', 'actual_weight', 'settlement_amount', 'count_settlement_amount', 'expect_quantity', 'remark', 'is_auth', 'expiration_date']);
                return $collectItem
                    ->put('order_no', $params['order_no'])
                    ->put('merchant_id', $params['merchant_id'])
                    ->put('execution_date', $params['execution_date'])
                    ->put('second_execution_date', $params['second_execution_date'] ?? null)
                    ->put('status', $status)
                    ->put('expiration_status', BaseConstService::EXPIRATION_STATUS_1)
                    ->put('type', $params['type']);
            })->toArray();
            $rowCount = $this->getPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单包裹新增失败！');
            }
        }
        //若材料存在,则新增材料列表
        if (!empty($params['material_list'])) {
            $materialList = collect($params['material_list'])->map(function ($item, $key) use ($params) {
                $collectItem = collect($item)->only(['name', 'code', 'out_order_no', 'expect_quantity', 'remark']);
                return $collectItem
                    ->put('order_no', $params['order_no'])
                    ->put('merchant_id', $params['merchant_id'])
                    ->put('execution_date', $params['execution_date']);
            })->toArray();
            $rowCount = $this->getMaterialService()->insertAll($materialList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单材料新增失败！');
            }
        }

    }

    /**
     * 添加货物列表
     * @param $params
     * @throws BusinessLogicException
     */
    private
    function addAmountList($params)
    {
        $dataList = [];
        //若存在包裹列表,则新增包裹列表
        if (!empty($params['amount_list'])) {
            foreach ($params['amount_list'] as $k => $v) {
                $dataList[$k]['order_no'] = $params['order_no'];
                $dataList[$k]['expect_amount'] = $v['expect_amount'];
                $dataList[$k]['actual_amount'] = 0.00;
                $dataList[$k]['type'] = $v['type'];
                $dataList[$k]['remark'] = '';
                $dataList[$k]['status'] = BaseConstService::ORDER_AMOUNT_STATUS_2;
                if (!empty($v['in_total'])) {
                    $dataList[$k]['in_total'] = $v['in_total'];
                } else {
                    $dataList[$k]['in_total'] = BaseConstService::YES;
                }
            }
            $rowCount = $this->getOrderAmountService()->insertAll($dataList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单费用新增失败！');
            }
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public
    function updateById($id, $data)
    {
        unset($data['order_no'], $data['tour_no'], $data['batch_no']);
        //获取信息
        $dbOrder = $this->getInfoOfStatus(['id' => $id], true);
//        if ($this->updateBaseInfo($dbOrder, $data) == true) {
//            return '';
//        }
//        if (intval($dbOrder['source']) === BaseConstService::ORDER_SOURCE_3) {
//            throw new BusinessLogicException('第三方订单不能修改');
//        }
        if ($dbOrder['type'] != $data['type']) {
            throw new BusinessLogicException('订单类型不能修改');
        }
        //验证
        $this->check($data, $dbOrder['order_no']);
        /*************************************************订单修改******************************************************/
        $data = Arr::add($data, 'order_no', $dbOrder['order_no']);
        $data = Arr::add($data, 'status', $dbOrder['status']);
        $rowCount = parent::updateById($dbOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        /*********************************************更换清单列表***************************************************/
        //删除包裹列表
        $rowCount = $this->getPackageService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //删除材料列表
        $rowCount = $this->getMaterialService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //新增包裹列表和材料列表
        $this->addAllItemList($data);
        //删除费用
        $rowCount = $this->getOrderAmountService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //新增费用
        $this->addAmountList($data);
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $this->getTrackingOrderService()->updateByOrder($data);
    }

    /**
     * 待出库，取派中的订单修改特殊事项
     * @param $dbInfo
     * @param $data
     * @return bool
     * @throws BusinessLogicException
     */
    public
    function updateBaseInfo($dbInfo, $data)
    {
        $newData = Arr::only($data, array_keys($dbInfo));
        $columns = ['special_remark'];
        foreach ($newData as $k => $v) {
            if (!in_array($k, $columns) && $v != $dbInfo[$k]) {
                return false;
            }
        }
        if (!empty($data['package_list'])) {
            $dbPackageList = $this->getPackageService()->getList(['order_no' => $dbInfo['order_no']], ['*'], false);
            if (!empty($dbPackageList)) {
                $dbPackageList = $dbPackageList->toArray();
                $data['package_list'] = Arr::only($data['package_list'], array_keys($dbPackageList));
                foreach ($data['package_list'] as $k => $v) {
                    foreach ($v as $x => $y) {
                        $package = collect($dbPackageList)->where('express_first_no', $v['express_first_no'])->first();
                        if (empty($package) || $y != $package[$x]) {
                            return false;
                        }
                    }
                }
            }
        }
        if (!empty($data['material_list'])) {
            $dbMaterialList = $this->getMaterialService()->getList(['order_no' => $dbInfo['order_no']], ['*'], false);
            if (!empty($dbMaterialList)) {
                $dbMaterialList = $dbMaterialList->toArray();
                $data['material_list'] = Arr::only($data['material_list'], array_keys($dbMaterialList));
                foreach ($data['material_list'] as $k => $v) {
                    foreach ($v as $x => $y) {
                        $material = collect($dbMaterialList)->where('code', $v['code'])->first();
                        if (empty($material) || $y != $material[$x]) {
                            return false;
                        }
                    }
                }
            }
        }
        $rowCount = parent::updateById($data['id'], Arr::only($data, $columns));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        return true;
    }

    /**
     * 反写运单信息至订单
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public
    function updateByTrackingOrder($trackingOrder)
    {
        $dbOrder = $this->getInfoOfStatus(['order_no' => $trackingOrder['order_no']], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('订单[:order_no]不存在', 1000, ['order_no' => $trackingOrder['order_no']]);
        }
        //若是取派中的派件,修改第二日期;否则，修改第一日期
        if (($dbOrder['type'] == BaseConstService::ORDER_TYPE_3) && ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2)) {
            $data = ['second_execution_date' => $trackingOrder['execution_date']];
        } else {
            $data = ['execution_date' => $trackingOrder['execution_date']];
        }
        //若运单为取派中，则订单修改为取派中;其他，不处理
        if ($trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_4) {
            $data['status'] = BaseConstService::ORDER_STATUS_2;
        }
        $rowCount = parent::updateById($dbOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $executionDate = !empty($data['execution_date']) ? $data['execution_date'] : $dbOrder['execution_date'];
        $secondExecutionDate = !empty($data['second_execution_date']) ? $data['second_execution_date'] : $dbOrder['second_execution_date'];
        $status = !empty($data['status']) ? $data['status'] : $dbOrder['status'];
        event(new OrderExecutionDateUpdated($dbOrder['order_no'], $dbOrder['out_order_no'] ?? '', $executionDate, $secondExecutionDate, $status, '', ['tour_no' => '', 'line_id' => $trackingOrder['line_id'] ?? '', 'line_name' => $trackingOrder['line_name'] ?? '']));
    }

    /**
     * 删除
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public
    function destroy($id)
    {
        //获取信息
        $dbOrder = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_5]);
        if ($dbOrder['status'] == BaseConstService::ORDER_STATUS_5) {
            return 'true';
        }
        if ($dbOrder['source'] == BaseConstService::ORDER_SOURCE_3) {
            throw new BusinessLogicException('第三方订单不允许手动删除');
        }
        $this->getTrackingOrderService()->destroyByOrderNo($dbOrder['order_no']);
        $rowCount = parent::updateById($id, ['tracking_order_no' => '', 'status' => BaseConstService::ORDER_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //材料清除运单信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $dbOrder['order_no']], ['tracking_order_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //包裹清除运单信息
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], ['tracking_order_no' => '', 'status' => BaseConstService::PACKAGE_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        return 'true';
    }

    /**
     * 批量删除
     * @param $idList
     * @throws BusinessLogicException
     */
    public
    function destroyAll($idList)
    {
        $idList = explode_id_string($idList);
        foreach ($idList as $id) {
            $this->destroy($id);
        }
    }

    /**
     * 批量订单打印
     * @param $idList
     * @return array
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public
    function orderPrintAll($idList)
    {
        $printTemplate = $this->getPrintTemplateService()->getInfo([], ['id', 'type'], false);
        if (empty($printTemplate)) {
            throw new BusinessLogicException('未设置打印模板，请联系管理员设置打印模板');
        }
        $company = CompanyTrait::getCompany();
        $orderList = parent::getList(['id' => ['in', explode_id_string($idList)]], ['*'], false)->toArray();
        $orderNoList = array_column($orderList, 'order_no');
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'expect_quantity', 'express_first_no'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'expect_quantity', 'code'], false)->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        $orderList = collect($orderList)->map(function ($order) use ($packageList, $materialList, $company) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            $order['material_list'] = $materialList[$order['order_no']] ?? [];
            $order['count'] = count($order['package_list']) + count($order['material_list']);
            $order['company_name'] = $company['name'];
            $order['place_address_short'] = $order['place_country_name'] . ' ' . $order['place_city'];
            return collect($order);
        })->toArray();
        //若是通用打印模板,则需要将快递号转为条码
        if ($printTemplate->type == BaseConstService::PRINT_TEMPLATE_GENERAL) {
            $orderView = 'order.order-2';
            foreach ($orderList as $key => $order) {
                if (!empty($order['package_list'])) {
                    $orderList[$key]['package_list'] = collect($order['package_list'])->map(function ($package) {
                        $package['express_first_no'] = BarcodeTrait::generateOne($package['express_first_no']);
                        return collect($package);
                    })->toArray();
                }
            }
        } else {
            $orderView = 'order.order';
        }
        foreach ($orderList as $key => $order) {
            $orderList[$key]['barcode'] = BarcodeTrait::generateOne($order['order_no']);
        }
        $url = PrintTrait::tPrintAll($orderList, $orderView, 'order', null);
        return $url;
    }

    /**
     * 批量订单面单打印
     * @param $idList
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public
    function orderBillPrint($idList)
    {
        $data = [];
        $orderList = $this->printData($idList);
        $orderList = $this->printBarcode($orderList);
        foreach ($orderList as $k => $v) {
            $data[] = $this->printForm($v);
        }
        return $data;
    }

    /**
     * 获取打印数据
     * @param $idList
     * @return array
     * @throws BusinessLogicException
     */
    public
    function printData($idList)
    {
        $newOrderList = [];
        $orderList = parent::getList(['id' => ['in', explode_id_string($idList)]], ['*'], false)->toArray();
        if (empty($orderList)) {
            throw new BusinessLogicException('订单不存在');
        }
        $orderNoList = array_column($orderList, 'order_no');
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'expect_quantity', 'express_first_no'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'expect_quantity', 'code'], false)->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        foreach ($orderList as $k => $v) {
            $newOrderList[$k]['order_no'] = $v['order_no'];
            $newOrderList[$k]['mask_code'] = $v['mask_code'];
            $newOrderList[$k]['sender']['fullname'] = $v['place_fullname'];
            $newOrderList[$k]['sender']['phone'] = $v['place_phone'];
            $newOrderList[$k]['sender']['country'] = $v['place_country'];
            $newOrderList[$k]['sender']['province'] = $v['place_province'];
            $newOrderList[$k]['sender']['city'] = $v['place_city'];
            $newOrderList[$k]['sender']['district'] = $v['place_district'];
            $newOrderList[$k]['sender']['post_code'] = $v['place_post_code'];
            $newOrderList[$k]['sender']['street'] = $v['place_street'];
            $newOrderList[$k]['sender']['house_number'] = $v['place_house_number'];
            $newOrderList[$k]['sender']['address'] = $v['place_address'];

            $newOrderList[$k]['receiver']['fullname'] = $v['second_place_fullname'];
            $newOrderList[$k]['receiver']['phone'] = $v['second_place_phone'];
            $newOrderList[$k]['receiver']['country'] = $v['second_place_country'];
            $newOrderList[$k]['receiver']['province'] = $v['second_place_province'];
            $newOrderList[$k]['receiver']['city'] = $v['second_place_city'];
            $newOrderList[$k]['receiver']['district'] = $v['second_place_district'];
            $newOrderList[$k]['receiver']['post_code'] = $v['second_place_post_code'];
            $newOrderList[$k]['receiver']['street'] = $v['second_place_street'];
            $newOrderList[$k]['receiver']['house_number'] = $v['second_place_house_number'];
            $newOrderList[$k]['receiver']['address'] = $v['second_place_address'];

            if ($v['type'] !== BaseConstService::ORDER_TYPE_3) {
                $newOrderList[$k]['destination']['country'] = $v['place_country'];
                $newOrderList[$k]['destination']['province'] = $v['place_province'];
                $newOrderList[$k]['destination']['city'] = $v['place_city'];
                $newOrderList[$k]['destination']['district'] = $v['place_district'];
                $newOrderList[$k]['destination']['post_code'] = $v['place_post_code'];
                $newOrderList[$k]['destination']['street'] = $v['place_street'];
                $newOrderList[$k]['destination']['house_number'] = $v['place_house_number'];
                $newOrderList[$k]['destination']['address'] = $v['place_address'];
            } else {
                $newOrderList[$k]['destination']['country'] = $v['second_place_country'];
                $newOrderList[$k]['destination']['province'] = $v['second_place_province'];
                $newOrderList[$k]['destination']['city'] = $v['second_place_city'];
                $newOrderList[$k]['destination']['district'] = $v['second_place_district'];
                $newOrderList[$k]['destination']['post_code'] = $v['second_place_post_code'];
                $newOrderList[$k]['destination']['street'] = $v['second_place_street'];
                $newOrderList[$k]['destination']['house_number'] = $v['second_place_house_number'];
                $newOrderList[$k]['destination']['address'] = $v['second_place_address'];
            }
            $newOrderList[$k]['tracking_order'] = $this->getTrackingOrderService()->getInfo(['order_no' => $v['order_no']], ['*'], false, ['created_at' => 'desc']);
            if (empty($newOrderList)) {
                throw new BusinessLogicException('订单[:order_no]未生成运单，无法打印面单', 1000, ['order_no' => $v['order_no']]);
            }
            $newOrderList[$k]['tracking_order'] = $newOrderList[$k]['tracking_order']->toArray();
            $newOrderList[$k]['warehouse']['country'] = $newOrderList[$k]['tracking_order']['warehouse_country'];
            $newOrderList[$k]['warehouse']['province'] = $newOrderList[$k]['tracking_order']['warehouse_province'];
            $newOrderList[$k]['warehouse']['city'] = $newOrderList[$k]['tracking_order']['warehouse_city'];
            $newOrderList[$k]['warehouse']['district'] = $newOrderList[$k]['tracking_order']['warehouse_district'];
            $newOrderList[$k]['warehouse']['post_code'] = $newOrderList[$k]['tracking_order']['warehouse_post_code'];
            $newOrderList[$k]['warehouse']['street'] = $newOrderList[$k]['tracking_order']['warehouse_street'];
            $newOrderList[$k]['warehouse']['house_number'] = $newOrderList[$k]['tracking_order']['warehouse_house_number'];
            $newOrderList[$k]['warehouse']['address'] = $newOrderList[$k]['tracking_order']['warehouse_address'];
            //第三方填充仓库
            if ($v['type'] == BaseConstService::ORDER_TYPE_1 && empty($newOrderList[$k]['receiver']['fullname'])) {
                $newOrderList[$k]['receiver'] = $newOrderList[$k]['warehouse'];
            } elseif ($v['type'] == BaseConstService::ORDER_TYPE_2 && empty($newOrderList[$k]['sender']['fullname'])) {
                $newOrderList[$k]['sender'] = $newOrderList[$k]['warehouse'];
            }
            if ($v['type'] == BaseConstService::ORDER_TYPE_2) {
                $a = $newOrderList[$k]['sender'];
                $newOrderList[$k]['sender'] = $newOrderList[$k]['receiver'];
                $newOrderList[$k]['receiver'] = $a;
            }
            $newOrderList[$k]['mask_code'] = $v['mask_code'];
            $newOrderList[$k]['replace_amount'] = $v['replace_amount'];
            $newOrderList[$k]['settlement_amount'] = $v['settlement_amount'];
            $newOrderList[$k]['package_count'] = !empty($packageList[$v['order_no']]) ? collect($packageList[$v['order_no']])->sum('expect_quantity') : 0;
            $newOrderList[$k]['material_count'] = !empty($materialList[$v['order_no']]) ? collect($materialList[$v['order_no']])->sum('expect_quantity') : 0;
            $newOrderList[$k]['package_list'] = !empty($packageList[$v['order_no']]) ? $packageList[$v['order_no']] : [];
        }
        return $newOrderList;
    }

    /**
     * 按模板打印
     * @param $orderList
     * @return mixed
     * @throws \Throwable
     */
    public
    function printBarcode($orderList)
    {
        //若是通用打印模板,则需要将快递号转为条码
        foreach ($orderList as $key => $order) {
            $orderList[$key]['order_barcode'] = BarcodeTrait::generateOne($order['order_no']);
            if (!empty($order['package_list'])) {
                $orderList[$key]['first_package_barcode'] = BarcodeTrait::generateOne($order['package_list'][0]['express_first_no']);
                $orderList[$key]['first_package_no'] = $order['package_list'][0]['express_first_no'];
            } else {
                $orderList[$key]['first_package_barcode'] = '';
            }
        }
        return $orderList;
    }

    /**
     * 打印序列化
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public
    function printForm($params)
    {
        $data = [];
        $fields = ['order_no', 'package_count', 'material_count', 'replace_amount', 'settlement_amount', 'order_barcode', 'first_package_barcode', 'first_package_no', 'mask_code',
            'sender',
            'receiver',
            'warehouse',
            'destination'
        ];
        $orderTemplate = $this->getOrderTemplateService()->getInfo(['company_id' => auth()->user()->company_id, 'is_default' => BaseConstService::YES], ['*'], false);
        if (empty($orderTemplate)) {
            throw new BusinessLogicException('未设置打印模板，请联系管理员设置打印模板');
        }
        $orderTemplate = $orderTemplate->toArray();
        if ($orderTemplate['type'] == BaseConstService::ORDER_TEMPLATE_TYPE_1) {
            $data['template_name'] = 'PrintStandard';
        } else {
            $data['template_name'] = 'PrintStandard2';
        }
        if ($orderTemplate['destination_mode'] == BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1) {
            $params['destination']['all'] = $params['destination']['province'] . $params['destination']['city'] . $params['destination']['district'];
        } elseif ($orderTemplate['destination_mode'] == BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_2) {
            $params['destination']['all'] = $params['destination']['province'] . $params['destination']['city'];
        } elseif ($orderTemplate['destination_mode'] == BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_3) {
            $params['destination']['all'] = $params['destination']['city'] . $params['destination']['district'];
        } elseif ($orderTemplate['destination_mode'] == BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_4) {
            $params['destination']['all'] = $params['destination']['post_code'];
        }
        $data['template'] = Arr::except($orderTemplate, ['company_id', 'destination_mode', 'type', 'created_at', 'updated_at']);
        $data['template']['logo'] = $this->imageToBase64($data['template']['logo']);
        $params = Arr::only($params, $fields);
        $data['api'] = $params;
        return $data;
    }

    /**
     * 获取Logo的base64版本
     * @param $url
     * @return string
     * @throws BusinessLogicException
     */
    public
    function imageToBase64($url)
    {
        try {
            $image_info = getimagesize($url);
            $image_data = file_get_contents($url);
            $url = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        } catch (\Exception $e) {
        }
        return $url;
    }

    /**
     * 订单导出
     * @param $ids
     * @return array
     * @throws BusinessLogicException
     */
    public
    function orderExport()
    {
        $orderList = $this->setFilter()->getList();
        //特殊处理
        if ($orderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderList->pluck('order_no')->toArray()]]);
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderList->pluck('order_no')->toArray()]]);
        $orderList = $orderList->toArray(request());
        $packageIsExist = !empty($packageList);
        $materialIsExist = !empty($materialList);
        unset($packageList);
        unset($materialList);
        foreach ($orderList as $k => $v) {
            $orderList[$k]['merchant_name'] = $v['merchant_id_name'];
            $orderList[$k]['status'] = $v['status_name'];
            $orderList[$k]['type'] = $v['type_name'];
            $orderList[$k]['sticker_amount'] = $v['sticker_amount'] ?? 0.00;
            $orderList[$k]['replace_amount'] = $v['replace_amount'] ?? 0.00;
            $orderList[$k]['settlement_amount'] = $v['settlement_amount'] ?? 0.00;
            if ($packageIsExist) {
                $list = $this->getPackageService()->query->where('order_no', $v['order_no'])->pluck('express_first_no')->toArray();
                $orderList[$k]['package_name'] = implode(',', $list);
                $orderList[$k]['package_quantity'] = count($list);
            } else {
                $orderList[$k]['package_name'] = [];
                $orderList[$k]['package_quantity'] = 0;
            }
            if ($materialIsExist) {
                $list = $this->getMaterialService()->query->where('order_no', $v['order_no'])->get();
                $orderList[$k]['material_name'] = implode(',', collect($list)->pluck('code')->toArray());
                $orderList[$k]['material_quantity'] = collect($list)->sum('expect_quantity');
            } else {
                $orderList[$k]['material_name'] = [];
                $orderList[$k]['material_quantity'] = 0;
            }
            if ($v['type'] == BaseConstService::ORDER_TYPE_2) {
                $orderList[$k]['receiver_post_code'] = $orderList[$k]['place_post_code'];
                $orderList[$k]['receiver_house_number'] = $orderList[$k]['place_house_number'];
                $orderList[$k]['receiver_execution_date'] = $orderList[$k]['execution_date'];
                $orderList[$k]['sender_post_code'] = $orderList[$k]['sender_house_number'] = $orderList[$k]['sender_execution_date'] = '';
            } elseif ($v['type'] == BaseConstService::ORDER_TYPE_1) {
                $orderList[$k]['sender_post_code'] = $orderList[$k]['place_post_code'];
                $orderList[$k]['sender_house_number'] = $orderList[$k]['place_house_number'];
                $orderList[$k]['sender_execution_date'] = $orderList[$k]['execution_date'];
                $orderList[$k]['receiver_post_code'] = $orderList[$k]['receiver_house_number'] = $orderList[$k]['receiver_execution_date'] = '';
            } elseif ($v['type'] == BaseConstService::ORDER_TYPE_3) {
                $orderList[$k]['receiver_post_code'] = $orderList[$k]['second_place_post_code'];
                $orderList[$k]['receiver_house_number'] = $orderList[$k]['second_place_house_number'];
                $orderList[$k]['receiver_execution_date'] = $orderList[$k]['second_execution_date'];

                $orderList[$k]['sender_post_code'] = $orderList[$k]['place_post_code'];
                $orderList[$k]['sender_house_number'] = $orderList[$k]['place_house_number'];
                $orderList[$k]['sender_execution_date'] = $orderList[$k]['execution_date'];
            }
        }
        $cellData = [];
        foreach ($orderList as $v) {
            $cellData[] = array_only_fields_sort($v, $this->headings);
        }
        if (empty($cellData)) {
            throw new BusinessLogicException('数据不存在');
        }
        $dir = 'orderOut';
        $name = date('YmdHis') . auth()->user()->id;
        return $this->excelExport($name, $this->headings, $cellData, $dir);
    }

    /**
     * 同步订单状态列表
     * @param $idList
     * @param bool $stockException
     */
    public
    function synchronizeStatusList($idList, $stockException = false)
    {
        //获取订单列表
        $idList = explode_id_string($idList);
        $orderList = parent::getList(['id' => ['in', $idList]], ['*'], false)->toArray();
        $orderNoList = array_column($orderList, 'order_no');
        //获取运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['order_no' => ['in', $orderNoList]], ['id', 'order_no', 'out_order_no', 'batch_no', 'tour_no', 'type', 'status'], false, [], ['id' => 'asc'])->toArray();
        //这里只会得到订单的最新运单
        $trackingOrderList = array_create_index($trackingOrderList, 'order_no');
        //获取包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount', 'is_auth', 'auth_fullname', 'auth_birth_date'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        //获取材料列表
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'], false)->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        //获取站点列表
        $batchNoList = array_column($trackingOrderList, 'batch_no');
        $batchList = $this->getBatchService()->getList(['batch_no' => ['in', $batchNoList]], ['*'], false)->toArray();
        $batchList = array_create_index($batchList, 'batch_no');
        //获取取件线路列表
        $tourNoList = array_column($trackingOrderList, 'tour_no');
        $tourList = $this->getTourService()->getList(['tour_no' => ['in', $tourNoList]], ['*'], false)->toArray();
        $tourList = array_create_index($tourList, 'tour_no');
        //组合数据
        foreach ($orderList as &$order) {
            $orderNo = $order['order_no'];
            $order['package_list'] = $packageList[$orderNo] ?? [];
            $order['material_list'] = $materialList[$orderNo] ?? [];
            $order['delivery_count'] = (floatval($order['delivery_amount']) == 0) ? 0 : 1;
            if (empty($trackingOrderList[$orderNo])) {
                $order['cancel_remark'] = $order['signature'] = $order['line_name'] = $order['driver_name'] = $order['driver_phone'] = $order['car_no'] = '';
                $order['tracking_order_type'] = $order['tracking_order_status'] = $order['pay_type'] = $order['line_id'] = $order['driver_id'] = $order['car_id'] = $order['tracking_order_type_name'] = null;
                continue;
            }
            $order['tracking_type'] = $order['tracking_order_type'] = $trackingOrderList[$orderNo]['type'];
            $order['tracking_order_status'] = $trackingOrderList[$orderNo]['status'];
            if ($stockException == true) {
                $order['tracking_type'] = $order['tracking_order_type'] = BaseConstService::TRACKING_ORDER_TYPE_1;
                $order['tracking_order_status'] = BaseConstService::TRACKING_ORDER_STATUS_5;
            }
            $order['tracking_order_type_name'] = ConstTranslateTrait::trackingOrderTypeList($order['tracking_order_type']);
            $order['tracking_order_status_name'] = ConstTranslateTrait::trackingOrderStatusList($order['tracking_order_status']);
            $order['cancel_remark'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['cancel_remark'] ?? '';
            $order['signature'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['signature'] ?? '';
            $order['pay_type'] = $batchList[$trackingOrderList[$orderNo]['batch_no']]['pay_type'] ?? null;
            $order['line_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['line_id'] ?? null;
            $order['line_name'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['line_name'] ?? '';
            $order['driver_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_id'] ?? null;
            $order['driver_name'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_name'] ?? '';
            $order['driver_phone'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['driver_phone'] ?? '';
            $order['car_id'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['car_id'] ?? null;
            $order['car_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['car_no'] ?? '';
            $order['batch_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['batch_no'] ?? '';
            $order['tour_no'] = $tourList[$trackingOrderList[$orderNo]['tour_no']]['tour_no'] ?? '';
        }
        dispatch(new \App\Jobs\SyncOrderStatus($orderList));
    }

    /**
     * 无效化已完成订单
     * @param $id
     * @throws BusinessLogicException
     */
    public
    function neutralize($id)
    {
        $order = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($order['status'] !== BaseConstService::ORDER_STATUS_3) {
            throw new BusinessLogicException('只有已完成的订单才能无效化');
        }
        $row = parent::updateById($id, ['out_order_no' => $order['out_order_no'] !== '' ? $order['out_order_no'] . 'OLD' : '']);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        $packageList = $this->getPackageService()->getList(['order_no' => $order['order_no']], ['*'], false);
        if (empty($packageList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $packageList = $packageList->toArray();
        foreach ($packageList as $k => $v) {
            $row = $this->getPackageService()->updateById($v['id'],
                ['out_order_no' => $v['out_order_no'] !== '' ? $v['express_first_no'] . 'OLD' : '',
                    'express_first_no' => $v['express_first_no'] !== '' ? $v['express_first_no'] . 'OLD' : '',
                    'express_second_no' => $v['express_second_no'] !== '' ? $v['express_second_no'] . 'OLD' : '',
                ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        return;
    }


    /**
     * 库存更新
     * @param $order
     * @throws BusinessLogicException
     */
    public
    function stockUpdate($order)
    {
        $expiredStockList = $this->getStockService()->getList(['order_no' => $order['order_no'], 'expiration_status' => BaseConstService::EXPIRATION_STATUS_2], ['*'], false);
        if (!empty($expiredStockList)) {
            $order = $this->getInfo(['order_no' => $order['order_no']], ['*'], false, ['id' => 'desc']);
            if (empty($order)) {
                throw new BusinessLogicException('订单不存在');
            }
            $this->getStockService()->update(['order_no' => $order['order_no'], 'expiration_status' => BaseConstService::EXPIRATION_STATUS_2], ['expiration_status' => BaseConstService::EXPIRATION_STATUS_3]);
        }
    }
}
