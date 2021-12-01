<?php
/**
 * 订单服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:39
 */

namespace App\Services\Merchant;

use App\Events\OrderCancel;
use App\Events\OrderDelete;
use App\Events\OrderExecutionDateUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\OrderAgainResource;
use App\Http\Resources\Api\Merchant\OrderInfoResource;
use App\Http\Resources\Api\Merchant\OrderResource;
use App\Models\Order;
use App\Models\TrackingOrder;
use App\Services\ApiServices\TourOptimizationService;
use App\Services\BaseConstService;
use App\Services\CommonService;
use App\Services\OrderTrailService;
use App\Traits\AddressTrait;
use App\Traits\BarcodeTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class OrderService
 * @package App\Services\Merchant
 */
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
        'pay_status' => ['=', 'pay_status']
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
        if (!empty($this->formData['post_code'])) {
            $trackingOrderList = $this->getTrackingOrderService()->getList(['place_post_code' => ['like', $this->formData['post_code']]]);
            if (!$trackingOrderList->isEmpty()) {
                $trackingOrderList = $trackingOrderList->pluck('order_no')->toArray();
                $this->query->whereIn('order_no', $trackingOrderList);
            } else {
                return [];
            }
        }
        if (!empty($this->formData['keyword'])) {
            $trackingOrderList = $this->getTrackingOrderService()->getList(['order_no' => intval($this->formData['keyword'])]);
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
            throw new BusinessLogicException('订单不存在');
        }
        $dbOrder['package_list'] = $this->getPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['material_list'] = $this->getMaterialService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['amount_list'] = $this->getOrderAmountService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['tracking_order_list'] = $this->getTrackingOrderService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['bill_list'] = $this->getBillService()->getList(['object_no' => $dbOrder['order_no'], 'type' => BaseConstService::BILL_TYPE_2, 'object_type' => BaseConstService::BILL_OBJECT_TYPE_1], ['*'], false);
        $merchant = $this->getMerchantService()->getInfo(['id' => $dbOrder['merchant_id']], ['*'], false);
        if (!empty($merchant)) {
            $dbOrder['merchant_id_name'] = $merchant['name'];
            $dbOrder['merchant_id_code'] = $merchant['code'];
        }
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
            throw new BusinessLogicException('订单不存在');
        }
        return $this->getTrackingOrderService()->getList(['order_no' => $dbOrder->order_no], ['*'], true);
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
     * 查询包裹信息
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function showByApi($params)
    {
        if (empty($params['order_no']) && empty($params['out_order_no'])) {
            throw new BusinessLogicException('查询字段至少一个不为空');
        }
        if (!empty($params['order_no'])) {
            $this->query->where('order_no', '=', $params['order_no']);
        }
        if (!empty($params['out_order_no'])) {
            $this->query->where('out_order_no', '=', $params['out_order_no']);
        }
        //$this->query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_4, BaseConstService::PACKAGE_STATUS_5]);
        $info = $this->getPageList()->toArray(request());
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = parent::getInfo(['order_no' => $info[0]['order_no']], ['merchant_id', 'order_no', 'status'], false);
        $data['package_list'] = $this->getPackageService()->getList(['order_no' => $info[0]['order_no']], ['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount'], false);
        $data['material_list'] = $this->getMaterialService()->getList(['order_no' => $info[0]['order_no']], ['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'], false);
        $data = array_only_fields_sort($data, ['merchant_id', 'order_no', 'status', 'package_list', 'material_list']);
        return $data;
    }

    public function initStore()
    {
        $data['default_config_list'] = $this->getOrderDefaultConfigService()->getInfo([], ['*'], false);
        return $data;
    }

    /**
     * 判断是id的值是id还是order_no
     *
     * @param $id
     * @return string
     */
    private function getIdKeyName($id)
    {
        return is_numeric($id) ? 'id' : 'order_no';
    }

    /**
     * 获取待分配订单信息
     * @param $id
     * @param $isToArray
     * @param $status
     * @param $isLock
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    private function getInfoByIdOfStatus($id, $isToArray = true, $status = BaseConstService::TRACKING_ORDER_STATUS_1, $isLock = true)
    {
        $where = [$this->getIdKeyName($id) => $id];
        $info = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!in_array(intval($info['status']), Arr::wrap($status))) {
            throw new BusinessLogicException('当前订单状态是[:status_name]，不能操作', 1000, ['status_name' => $info['status_name']]);
        }
        return $isToArray ? $info->toArray() : $info;
    }


    /**
     * 订单新增
     * @param $params
     * @param $orderSource
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
        if (!empty($params['execution_date'])) {
            $tour = $this->getTrackingOrderService()->storeByOrder($order);
        }
        //自动记录
        $this->record($params);
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
     * 记录地址
     * @param $params
     * @throws BusinessLogicException
     */
    public function record($params)
    {
        try {
            if (in_array($params['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_2])) {
                $this->getAddressService()->store($params);
            } elseif ($params['type'] == BaseConstService::ORDER_TYPE_3) {
                $params['type'] = BaseConstService::ORDER_TYPE_1;
                $this->getAddressService()->store($params);
                $address = $this->pieAddress($params);
                $this->getAddressService()->store($address);
            }
        } catch (BusinessLogicException $e) {
            Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException', ['message' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 反转地址
     * @param $data
     * @return array
     */
    public function pieAddress($data)
    {

        $data = [
            'type' => BaseConstService::TRACKING_ORDER_TYPE_2,
            'place_fullname' => $data['second_place_fullname'],
            'place_phone' => $data['second_place_phone'],
            'place_country' => $data['second_place_country'] ?? '',
            'place_province' => $data['second_place_province'] ?? '',
            'place_post_code' => $data['second_place_post_code'],
            'place_house_number' => $data['second_place_house_number'],
            'place_city' => $data['second_place_city'],
            'place_district' => $data['second_place_district'] ?? '',
            'place_street' => $data['second_place_street'],
            'place_address' => $data['second_place_address'],
            'place_lat' => $data['second_place_lat'] ?? '',
            'place_lon' => $data['second_place_lon'] ?? '',
            'execution_date' => $data['second_execution_date']
        ];
        return $data;
    }

    /**
     * 填充收发件人地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillAddress($data)
    {
        $place = AddressTrait::$place;
        $secondPlace = AddressTrait::$secondPlace;
        if ($data['type'] == BaseConstService::ORDER_TYPE_1) {
            foreach ($place as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillPlaceAddress($data);
                    break;
                }
            }
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            foreach ($place as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillPlaceAddress($data);
                    break;
                }
            }
        } elseif ($data['type'] == BaseConstService::ORDER_TYPE_3) {
            foreach ($place as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillPlaceAddress($data);
                    break;
                }
            }
            foreach ($secondPlace as $k => $v) {
                if (empty($data[$v])) {
                    $data = $this->fillSecondPlaceAddress($data);
                    break;
                }
            }
        }
        return $data;
    }

    /**
     * 填充取件地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillPlaceAddress($data)
    {
        $address = $this->getAddressService()->getInfoByUnique($data);
        if (empty($address)) {
            $info = LocationTrait::getLocation(
                $data['place_country'],
                $data['place_city'] ?? '',
                $data['place_street'] ?? '',
                $data['place_house_number'] ?? '',
                $data['place_post_code'] ?? ''
            );
            $data = AddressTrait::addressToPlace($info, $data);
        } else {
            $data = array_merge($data, $address->toArray());
        }
        return $data;
    }

    /**
     * 填充派件地址
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fillSecondPlaceAddress($data)
    {
        $newData = AddressTrait::secondPlaceToPlace($data);
        $newData = $this->fillPlaceAddress($newData);
        $data = AddressTrait::placeToSecondPlace($newData, $data);
        return $data;
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
        $packageStageList = $this->getPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false)->pluck('stage')->toArray();
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
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_2]);
        //$dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
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
        //event(new OrderCancel($dbOrder['order_no'], $dbOrder['out_order_no']));
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
     * 运价计算
     * @param $order
     * @return array|void
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function check(&$params, $orderNo = null)
    {
        //验证国家
        $countryList = $this->getCountryService()->getList([], ['*'], false)->pluck('short')->toArray();
        if (empty($countryList)) {
            throw new BusinessLogicException('请先配置国家');
        }
        if ((!empty($params['place_country']) && !in_array($params['place_country'], $countryList))
            || (!empty($params['second_place_country']) && !in_array($params['second_place_country'], $countryList))) {
            throw new BusinessLogicException('国家不存在');
        }
        //兼容
        if ($params['place_country'] == 'NL' && post_code_be($params['place_post_code'])) {
            $params['place_country'] = 'BE';
        } elseif ($params['place_country'] == 'NL' && Str::length($params['place_post_code']) == 5) {
            $params['place_country'] = 'DE';
        }
        //屏蔽非法参数
        unset($params['created_at'], $params['updated_at'], $params['status'], $params['id']);
        //填充开单日期
        if (auth()->user()->is_api == true) {
            $params['create_date'] = today()->format('Y-m-d');
        }
        //检验货主
        $params['merchant_id'] = auth()->user()->id;
        $merchant = $this->getMerchantService()->getInfo(['id' => auth()->user()->id, 'status' => BaseConstService::MERCHANT_STATUS_1], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        //去除空格
        $params = $this->trimAddress($params);
        //包裹材料验证
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //包裹验证
        $this->packageCheck($params, $orderNo);
        //材料验证
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //填充地址
        $params = $this->fillCombineAddress($params);
        //外部订单号验证
        $this->outOrderNoCheck($params);
        //运价计算
        $params = $this->fillAnotherAddressByApi($params);
        $params = $this->fillAddress($params);
        $this->getTrackingOrderService()->fillWarehouseInfo($params, BaseConstService::NO);
        if (config('tms.true_app_env') == 'develop' || empty(config('tms.true_app_env'))) {
            $params['distance'] = 1000;
        } else {
            $params['distance'] = TourOptimizationService::getDistanceInstance(auth()->user()->company_id)->getDistanceByOrder($params);
        }
        $params = $this->getTransportPriceService()->priceCount($params);
        $params['expect_total_amount'] = 0;
        if (!empty($params['bill_list'])) {
            foreach ($params['bill_list'] as $v) {
                $params['expect_total_amount'] += floatval($v['expect_amount']);
            }
        }
        return $params;
    }

    /**
     * 拼接地址
     * @param $params
     * @return
     */
    public function fillCombineAddress($params)
    {
        //填充地址
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['place_address'])) {
            $params['place_address'] = CommonService::addressFieldsSortCombine($params, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['second_place_address'])) {
            $params['second_place_address'] = CommonService::addressFieldsSortCombine($params, ['second_place_country', 'second_place_city', 'second_place_street', 'second_place_house_number', 'second_place_post_code']);
        }
        return $params;
    }

    /**
     * 地址参数去除空格
     * @param $params
     * @return array
     */
    public function trimAddress($params)
    {
        !empty($params['place_post_code']) && $params['place_post_code'] = str_replace(' ', '', $params['place_post_code']);
        !empty($params['second_place_post_code']) && $params['second_place_post_code'] = str_replace(' ', '', $params['second_place_post_code']);
        $fields = ['place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'];
        foreach ($fields as $v) {
            array_key_exists($v, $params) && $params[$v] = trim($params[$v]);
        }
        //获取经纬度
        $fields = ['place_house_number', 'place_city', 'place_street'];
        return array_merge(array_fill_keys($fields, ''), $params);
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function outOrderNoCheck($params)
    {
        //若存在货号,则判断是否存在已预约的订单号
        if (!empty($params['out_order_no'])) {
            $where = ['out_order_no' => $params['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = parent::getInfo($where, ['id', 'order_no', 'out_order_no', 'status'], false);
            if (!empty($dbOrder)) {
                if (auth()->user()->is_api == true) {
                    throw new BusinessLogicException('外部订单号已存在', 1005, [], [
                        'order_no' => $dbOrder['order_no'],
                        'out_order_no' => $dbOrder['out_order_no'] ?? '',
                        'batch_no' => '',
                        'tour_no' => '',
                        'line' => ['line_id' => null, 'line_name' => ''],
                        'execution_date' => $dbOrder->execution_date,
                        'second_execution_date' => $dbOrder->second_execution_date ?? null
                    ]);
                } else {
                    throw new BusinessLogicException('外部订单号已存在');
                }
            }
        }
    }

    /**
     * 包裹验证
     * @param $params
     * @param $orderNo
     * @throws BusinessLogicException
     */
    public function packageCheck($params, $orderNo)
    {
        if (!empty($params['package_list'])) {
            $this->getPackageService()->check($params['package_list'], $orderNo);
            //有效日日期不得早于取派日期
            foreach ($params['package_list'] as $k => $v) {
                if (!empty($v['expiration_date']) && $v['expiration_date'] < $params['execution_date']) {
                    throw new BusinessLogicException('有效日期不得小于取派日期');
                }
            }
        }
    }


    /**
     * 填充另一个地址
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function fillAnotherAddressByApi($params)
    {
        if ($params['type'] != BaseConstService::ORDER_TYPE_3) {
            if (!empty($params['source']) && $params['source'] == BaseConstService::ORDER_SOURCE_3) {
                if ($params['type'] == BaseConstService::ORDER_TYPE_2) {
                    $params = AddressTrait::changePlaceAndSecondPlace($params);
                    $params['execution_date'] = $params['second_execution_date'];
                    unset($params['second_execution_date']);
                }
                $newData = $params;
                $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
                $params = AddressTrait::warehouseToSecondPlace($newData, $params);
            } else {
                $newData = $params;
                $this->getTrackingOrderService()->fillWarehouseInfo($newData, BaseConstService::NO);
                $params = AddressTrait::warehouseToSecondPlace($newData, $params);
            }
        }
        return $params;
    }

    /**
     * 添加货物列表
     * @param $params
     * @param int $status
     * @throws BusinessLogicException
     */
    private function addAllItemList($params, $status = BaseConstService::ORDER_STATUS_1)
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
            $array = [
                BaseConstService::ORDER_TYPE_1 => BaseConstService::PACKAGE_STAGE_1,
                BaseConstService::ORDER_TYPE_2 => BaseConstService::PACKAGE_STAGE_3,
                BaseConstService::ORDER_TYPE_3 => BaseConstService::PACKAGE_STAGE_1,
                BaseConstService::ORDER_TYPE_4 => BaseConstService::PACKAGE_STAGE_1,
            ];
            $packageList = $params['package_list'];
            foreach ($packageList as $k => $v) {
                $packageList[$k] = Arr::except($v, ['created_at', 'updated_at', 'merchant_id', 'order_no', 'status', 'stage', 'type', 'id']);
                $packageList[$k]['order_no'] = $params['order_no'];
                $packageList[$k]['merchant_id'] = auth()->user()->id;
                $packageList[$k]['execution_date'] = $params['execution_date'];
                $packageList[$k]['second_execution_date'] = $params['second_execution_date'] ?? null;
                $packageList[$k]['status'] = $status;
                $packageList[$k]['stage'] = $array[$params['type']];
                $packageList[$k]['expiration_status'] = BaseConstService::EXPIRATION_STATUS_1;
                $packageList[$k]['type'] = $params['type'];
            }
            $rowCount = $this->getPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单包裹新增失败！');
            }
        }
        //若材料存在,则新增材料列表
        if (!empty($params['material_list'])) {
            $materialList = $params['material_list'];
            foreach ($materialList as $k => $v) {
                $materialList[$k] = Arr::except($v, ['price', 'created_at', 'updated_at', 'merchant_id', 'tracking_order_no', 'id']);
                $materialList[$k]['order_no'] = $params['order_no'];
                $materialList[$k]['merchant_id'] = auth()->user()->id;
                $materialList[$k]['execution_date'] = $params['execution_date'];
            }
            $rowCount = $this->getMaterialService()->insertAll($materialList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单材料新增失败！');
            }
        }

    }

    /**
     * 添加费用列表
     * @param $params
     * @throws BusinessLogicException
     */
    private function addAmountList($params)
    {
        if (!empty($params['settlement_amount']) && $params['settlement_amount'] !== 0) {
            $transportPrice = $this->getTransportPriceService()->getInfo(['id' => $params['transport_price_id']], ['*'], false);
            if (empty($transportPrice)) {
                throw new BusinessLogicException('费用不存在');
            } elseif ($transportPrice['status'] == BaseConstService::NO) {
                throw new BusinessLogicException('费用已禁用');
            } else {
                $transportPrice = $transportPrice->toArray();
                if ($transportPrice['pay_timing'] == BaseConstService::BILL_PAY_TIMING_1) {
                    $this->getBillService()->storeByTransportPrice($params, $transportPrice, BaseConstService::BILL_VERIFY_STATUS_2);
                } elseif (in_array($transportPrice['pay_timing'], [BaseConstService::BILL_PAY_TIMING_2, BaseConstService::BILL_PAY_TIMING_3])) {
                    $this->getBillService()->storeByTransportPrice($params, $transportPrice);
                }
            }
        }
        if (!empty($params['bill_list'])) {
            $feeList = $this->getFeeService()->getList(['id' => ['in', collect($params['bill_list'])->pluck('fee_id')->toArray()]], ['*'], false);
            foreach ($params['bill_list'] as $k => $v) {
                $fee = $feeList->where('id', $v['fee_id'] ?? 0)->first();
                if (empty($fee)) {
                    throw new BusinessLogicException('费用不存在');
                } elseif ($fee['status'] == BaseConstService::NO) {
                    throw new BusinessLogicException('费用已禁用');
                } else {
                    $fee = $fee->toArray();
                    $v['number'] = $k;
                    if ($v['expect_amount'] != 0 || $v['expect_amount'] != '') {
                        if ($fee['pay_timing'] == BaseConstService::BILL_PAY_TIMING_1) {
                            $this->getBillService()->storeByFee($v, $fee, $params, BaseConstService::BILL_VERIFY_STATUS_2);
                        } elseif (in_array($fee['pay_timing'], [BaseConstService::BILL_PAY_TIMING_2, BaseConstService::BILL_PAY_TIMING_3])) {
                            $this->getBillService()->storeByFee($v, $fee, $params);
                        }
                    }
                }
            }
        }
        $merchant = $this->getMerchantService()->getInfo(['id' => auth()->user()->id], ['*'], false);
        if (!empty($merchant) && $merchant['auto_settlement'] == BaseConstService::YES && $merchant['settlement_type'] == BaseConstService::MERCHANT_SETTLEMENT_TYPE_1) {
            $billList = $this->getBillService()->getList(['object_no' => $params['order_no'], 'payer_type' => BaseConstService::FEE_PAYER_TYPE_4], ['*'], false);
            if (!empty($billList)) {
                $this->getBillVerifyService()->store(['bill_list' => $billList]);
            }
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateById($id, $data)
    {
        unset($data['order_no'], $data['tour_no'], $data['batch_no']);
        //获取信息
        $dbOrder = $this->getInfoByIdOfStatus($id, true);

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
        $rowCount = $this->getBillService()->delete(['object_no' => $dbOrder['order_no'], 'object_type' => BaseConstService::BILL_OBJECT_TYPE_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //新增费用
        $this->addAmountList($data);
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $this->getTrackingOrderService()->updateByOrder($data);
    }

    /**
     * 修改地址日期
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateAddressDate($id, $params)
    {
        $result = ['line' => []];
        unset($params['order_no'], $params['tour_no'], $params['batch_no']);
        $dbOrder = $this->getInfoByIdOfStatus($id, true);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        $dbTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $dbOrder['order_no']], ['status'], false, ['id' => 'desc']);
        if (!empty($dbTrackingOrder)) {
            $dbTrackingOrder = $dbTrackingOrder->toArray();
            if (intval($dbOrder['source']) === BaseConstService::ORDER_SOURCE_3 && !in_array($dbTrackingOrder['status'], [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2])) {
                throw new BusinessLogicException('第三方订单不能修改');
            }
            $columns = [
                'place_fullname',
                'place_phone',
                'place_country',
                'place_province',
                'place_post_code',
                'place_house_number',
                'place_city',
                'place_district',
                'place_street',
            ];
            //取经纬度
            if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['place_address'])) {
                $params['place_address'] = CommonService::addressFieldsSortCombine($params, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
            }
            $address = LocationTrait::getLocation($params['place_country'], $params['place_city'], $params['place_street'], $params['place_house_number'], $params['place_post_code']);
            if (empty($address)) {
                throw new BusinessLogicException('邮编或门牌号码不正确，请仔细检查输入或联系客服');
            }
            $params['place_lon'] = $address['lon'];
            $params['place_lat'] = $address['lat'];
            $columns = array_merge($columns, ['place_lon', 'place_lat', 'place_address', 'execution_date']);
            //更新订单
            $rowCount = parent::updateById($dbOrder['id'], Arr::only($params, $columns));
            if ($rowCount === false) {
                throw new BusinessLogicException('修改失败，请重新操作');
            }
            $data = array_merge($dbOrder, Arr::only($params, $columns));
            /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
            $trackingOrder = $this->getTrackingOrderService()->updateByOrder($data, BaseConstService::YES);
            $result = [
                'line' => [
                    'line_id' => $trackingOrder['line_id'],
                    'line_name' => $trackingOrder['line_name']
                ]
            ];
        }
        return $result;
    }

    /**
     * 反写运单信息至订单
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function updateByTrackingOrder($trackingOrder)
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
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $executionDate = !empty($data['execution_date']) ? $data['execution_date'] : $dbOrder['execution_date'];
        $secondExecutionDate = !empty($data['second_execution_date']) ? $data['second_execution_date'] : $dbOrder['second_execution_date'];
        $status = !empty($data['status']) ? $data['status'] : $dbOrder['status'];
        event(new OrderExecutionDateUpdated($dbOrder['order_no'], $dbOrder['out_order_no'] ?? '', $executionDate, $secondExecutionDate, $status, '', ['tour_no' => '', 'line_id' => $trackingOrder['line_id'] ?? '', 'line_name' => $trackingOrder['line_name'] ?? '']));
    }

    /**
     * 修改派送日期
     * @param $id
     * @param $secondExecutionDate
     * @throws BusinessLogicException
     */
    public function updateSecondDate($id, $secondExecutionDate)
    {
        $dbOrder = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $data = ($dbOrder['type'] == BaseConstService::ORDER_TYPE_2) ? ['execution_date' => $secondExecutionDate] : ['second_execution_date' => $secondExecutionDate];
        $rowCount = parent::updateById($dbOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        return $this->getTrackingOrderService()->updateSecondDate($dbOrder, $secondExecutionDate);
    }


    /**
     * 修改电话和日期
     * @param $id
     * @param $data
     * @return boolean|array
     * @throws BusinessLogicException
     */
    public function updatePhoneDateByApi($id, $data)
    {
        $this->request->validate([
            'place_phone' => 'required_without:execution_date|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
            'execution_date' => 'required_without:place_phone|date|after_or_equal:today'
        ]);
        $dbOrder = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $data = array_filter(Arr::only($data, ['place_phone', 'execution_date']));
        $rowCount = parent::updateById($dbOrder['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //修改包裹取派日期
        if (!empty($data['execution_date'])) {
            $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], $data);
            if ($rowCount === false) {
                throw new BusinessLogicException('操作失败，请重新操作');
            }
        }
        return $this->getTrackingOrderService()->updateDateAndPhone($dbOrder, $data);
    }

    /**
     * 批量修改电话和日期
     * @param $params
     * @return boolean|array
     * @throws BusinessLogicException
     */
    public function updatePhoneDateByApiList($params)
    {
        $params['order_no_list'] = explode(',', $params['order_no_list']);
        $this->request->validate([
            'order_no_list' => 'required',
            'place_phone' => 'required_without:execution_date|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
            'execution_date' => 'required_without:place_phone|date|after_or_equal:today'
        ]);
        $dbOrderList = parent::getList(['order_no' => ['in', $params['order_no_list']]], ['*'], false);
        if ($dbOrderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $dbOrderList = $dbOrderList->toArray();
        if (count(array_unique(collect($dbOrderList)->pluck('place_phone')->toArray())) > 1 || count(array_unique(collect($dbOrderList)->pluck('execution_date')->toArray())) > 1) {
            throw new BusinessLogicException('所选多个订单电话或取件日期不一致，无法统一修改');
        }
        $data = array_filter(Arr::only($params, ['place_phone', 'execution_date']));
        $rowCount = parent::update(['order_no' => ['in', $params['order_no_list']]], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getTrackingOrderService()->update(['order_no' => ['in', $params['order_no_list']]], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        foreach ($params['order_no_list'] as $k => $v) {
            $this->getTrackingOrderService()->updateDateAndPhone(['order_no' => $v], $data);
        }
        return '';
    }

    /**
     * 获取可选日期验证
     * @param $info
     * @throws BusinessLogicException
     */
    public function validate($info)
    {
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_AREA) {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'place_lon' => 'required|string|max:50', 'place_lat' => 'required|string|max:50']);
        } else {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'place_post_code' => 'required|string|max:50']);
        }
        if ($validator->fails()) {
            throw new BusinessLogicException('地址数据不正确，无法拉取可选日期', 3001);
        }
    }

    /**
     * 删除
     * @param $id
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function destroy($id, $params)
    {
        //获取信息
        $dbOrder = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_5]);
        if ($dbOrder['status'] == BaseConstService::ORDER_STATUS_5) {
            return 'true';
        }
        if (($dbOrder['source'] == BaseConstService::ORDER_SOURCE_3) && (auth()->user()->getAttribute('is_api') != true)) {
            throw new BusinessLogicException('第三方订单不允许手动删除');
        }
        $this->getTrackingOrderService()->destroyByOrderNo($dbOrder['order_no']);
        $rowCount = parent::updateById($dbOrder['id'], ['tracking_order_no' => '', 'status' => BaseConstService::ORDER_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //材料清除运单信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $dbOrder['order_no']], ['tracking_order_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //包裹清除运单信息
        $rowCount = $this->getPackageService()->update(['order_no' => $dbOrder['order_no']], ['tracking_order_no' => '', 'status' => BaseConstService::PACKAGE_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = $this->getBillService()->update(['object_no' => $dbOrder['order_no'], 'object_type' => BaseConstService::BILL_OBJECT_TYPE_1], ['object_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        if ((!empty($params['no_push']) && $params['no_push'] == 0) || empty($params['no_push'])) {
            //以取消取派方式推送商城
            event(new OrderDelete($dbOrder['order_no'], $dbOrder['out_order_no']));
        }
        return 'true';
    }

    /**
     * 批量删除
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function destroyAll($params)
    {
        $orderNoList = array_filter(explode(',', $params['order_no_list']));
        foreach ($orderNoList as $orderNo) {
            try {
                $this->destroy($orderNo, $params);
            } catch (BusinessLogicException $exception) {
                throw new BusinessLogicException('批量删除失败，订单[:order_no]删除失败，原因-[:exception_info]', 1000, array_merge(['order_no' => $orderNo, 'exception_info' => $exception->getMessage()], $exception->replace));
            }
        }
        return 'true';
    }


    /**
     * 修改订单的出库状态
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateOutStatus($id, $params)
    {
        $dbOrder = $this->getInfoByIdOfStatus($id, true);
        $rowCount = parent::updateById($dbOrder['id'], ['out_status' => $params['out_status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        $this->getTrackingOrderService()->updateOutStatusByOrderNo($dbOrder['order_no'], $params['out_status']);
    }

    /**
     * 修改订单明细列表
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateItemList($id, $params)
    {
        $dbOrder = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        !empty($params['package_list']) && $this->getPackageService()->check($params['package_list'], $dbOrder['order_no']);
        //验证材料列表
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //删除包裹
        $rowCount = $this->getPackageService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //删除材料
        $rowCount = $this->getMaterialService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //删除费用
        $rowCount = $this->getOrderAmountService()->delete(['order_no' => $dbOrder['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //新增费用
        $this->addAmountList($params);
        //新增包裹和材料
        $dbOrder['package_list'] = $params['package_list'] ?? [];
        $dbOrder['material_list'] = $params['material_list'] ?? [];
        $this->addAllItemList($dbOrder);
        $this->getTrackingOrderService()->updateItemListByOrderNo($dbOrder['order_no'], $params);
    }

    /**
     * 批量订单面单打印
     * @param $idList
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function orderBillPrint($idList)
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
    public function printData($idList)
    {
        $newOrderList = [];
        $orderList = parent::getList(['id' => ['in', explode_id_string($idList)], 'merchant_id' => ['<>', 0]], ['*'], false)->toArray();
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
    public function printBarcode($orderList)
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
    public function printForm($params)
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
    public function imageToBase64($url)
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
     * 获取订单派送信息
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getOrderDispatchInfo($id)
    {
        $where = [$this->getIdKeyName($id) => $id];
        $dbOrder = parent::getInfo($where, ['*'], false);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $this->getTrackingOrderService()->getDispatchInfoByOrderNo($dbOrder->tracking_order_no);
    }

    /**
     * 地图追踪
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function track($id)
    {
        $order = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!$order['status'] == BaseConstService::ORDER_STATUS_3) {
            throw new BusinessLogicException('运输未开始，暂无物流信息');
        }
        $trackingOrder = $this->getTrackingOrderService()->getTrackingOrderByOrderNo($order['order_no']);
        if (empty($trackingOrder)) {
            throw new BusinessLogicException('暂无物流信息');
        }
        $tour = $this->getTrackingOrderService()->getInfo(['tour_no' => $trackingOrder['tour_no']], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $routeTracking = $this->getRouteTrackingService()->getInfo(['tour_no' => $tour['tour_no']], ['lon', 'lat'], false, ['id' => 'desc']) ?? [];
        $batch = $this->getBatchService()->getInfo(['batch_no' => $trackingOrder['batch_no']], ['*'], false) ?? [];
        $batchList = $this->getBatchService()->getList(['tour_no' => $batch['tour_no'], 'merchant_id' => ['<>', -1], 'status' => BaseConstService::BATCH_DELIVERING, 'sort_id' => ['<', $batch['sort_id']]], ['*'], false);
        $count = count($batchList);
        $expectDistance = $batchList->sum('expect_distance') + $batch['expect_distance'];
        $status = '';
        if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
            if ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_2) {
                $status = BaseConstService::TRACK_STATUS_1;
            } elseif ($trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1 && $trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_5) {
                $status = BaseConstService::TRACK_STATUS_2;
            } else {
                $status = BaseConstService::TRACK_STATUS_3;
            }
        }
        return [
            'order_no' => $order['order_no'],

            'place_fullname' => $trackingOrder['place_fullname'],
            'place_address' => $trackingOrder['place_address'],
            'place_lon' => $trackingOrder['place_lon'],
            'place_lat' => $trackingOrder['place_lat'],

            'second_place_fullname' => $order['second_place_fullname'],
            'second_place_address' => $order['second_place_address'],
            'second_place_lon' => $order['second_place_lon'],
            'second_place_lat' => $order['second_place_lat'],
            'type' => $order['type'],
            'type_name' => $order['type_name'],

            'warehouse_address' => $trackingOrder['warehouse_address'],
            'warehouse_lon' => $trackingOrder['warehouse_lon'] ?? '',
            'warehouse_lat' => $trackingOrder['warehouse_lat'] ?? '',

            'driver_lon' => $routeTracking['lon'] ?? '',
            'driver_lat' => $routeTracking['lat'] ?? '',
            'driver_name' => $tour['driver_name'],
            'car_no' => $tour['car_no'],
            'tour_no' => $tour['tour_no'],

            'expect_distance' => $expectDistance,
            'expect_arrive_time' => $batch['expect_arrive_time'] ?? '',
            'actual_arrive_time' => $batch['actual_arrive_time'] ?? '',
            'rest_batch' => $count,
            'status' => $status,
            'status_name' => empty($status) ? null : ConstTranslateTrait::trackStatusList($status)
        ];
    }

    /**
     * 通过订单ID获得运单信息
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTrackingOrderByOrderId($id)
    {
        $order = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['tracking_order_no' => $order['tracking_order_no']], ['*'], false);
        return $trackingOrder;
    }

    /**
     * 取消预约
     * @param $id
     * @return void
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $trackingOrder = $this->getTrackingOrderByOrderId($id);
        if (empty($trackingOrder)) {
            return;
        } else {
            return $this->getTrackingOrderService()->removeFromBatch($trackingOrder['id']);
        }
    }

    /**
     * 重新预约
     * @param $id
     * @param $params
     * @return string|void
     * @throws BusinessLogicException
     */
    public function assignToBatch($id, $params)
    {
        $trackingOrder = $this->getTrackingOrderByOrderId($id);
        if (empty($trackingOrder)) {
            return;
        }
        return $this->getTrackingOrderService()->assignToBatch($trackingOrder['id'], $params);
    }

    /**
     * 根据订单号获取可选日期
     * @param $id
     * @return mixed|void
     * @throws BusinessLogicException
     */
    public function getAbleDateList($id)
    {
        $trackingOrder = $this->getTrackingOrderByOrderId($id);
        if (empty($trackingOrder)) {
            return;
        }
        return $this->getTrackingOrderService()->getAbleDateList($trackingOrder['id']);
    }

    /**
     * 库存更新
     * @param $order
     * @throws BusinessLogicException
     */
    public function stockUpdate($order)
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

    /**
     * 订单导出
     * @param $id_list
     * @return array
     * @throws BusinessLogicException
     */
    public function orderExcelExport()
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
     * @param $data
     * @throws BusinessLogicException
     */
    public function pay($data)
    {
        if(empty($data['order_no'])){
            throw new BusinessLogicException('参数错误');
        }
        $order = parent::getInfo(['order_no' => $data['order_no']], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        //更改订单支付状态
        parent::update(['order_no' => $data['order_no']], [
            'pay_status' => BaseConstService::ORDER_PAY_STATUS_2
        ]);
        //更改对账单支付状态
        $billList = $this->getBillService()->getList(['object_no' => $data['order_no'], 'object_type' => BaseConstService::BILL_OBJECT_TYPE_1], ['*'], false);
        if (!empty($billList)) {
            foreach ($billList as $v) {
                $v=collect($v)->toArray();
                $this->getBillService()->update(['object_no' => $data['order_no'], 'object_type' => BaseConstService::BILL_OBJECT_TYPE_1], [
                    'status' => BaseConstService::BILL_STATUS_2,
                    'actual_amount' => $v['expect_amount']
                ]);
                //扣除余额
                $this->getLedgerService()->deduct($v['payer_type'], $v['payer_id'], $v['expect_amount']);
                //记录流水
                $this->getJournalService()->record($v);
            }
        }
    }

}
