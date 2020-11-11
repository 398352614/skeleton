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
use App\Events\OrderExecutionDateUpdated;
use App\Events\TourNotify\CancelBatch;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\OrderAgainResource;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Http\Validate\Api\Merchant\OrderImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Models\TourMaterial;
use App\Models\TrackingOrder;
use App\Services\CommonService;
use App\Services\Merchant\RouteTrackingService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Services\ThirdPartyLogService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use App\Services\TrackingOrderTrailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrderService
 * @package App\Services\Merchant
 * @property TourMaterial $tourMaterialModel
 */
class OrderService extends BaseService
{
    use ImportTrait, LocationTrait, CountryTrait;

    public $tourMaterialModel;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'source' => ['=', 'source'],
        'tour_no' => ['=', 'tour_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Order $order, TourMaterial $tourMaterial)
    {
        parent::__construct($order, OrderResource::class, OrderInfoResource::class);
        $this->tourMaterialModel = $tourMaterial;
    }


    /**
     * 包裹 服务
     * @return PackageService
     */
    private function getPackageService()
    {
        return self::getInstance(PackageService::class);
    }

    /**
     * 材料服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }


    /**
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    private function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    /**
     * 站点异常 服务
     * @return BatchExceptionService
     */
    private function getBatchExceptionService()
    {
        return self::getInstance(BatchExceptionService::class);
    }

    /**
     * 站点(取件批次) 服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
    }


    /**
     * 发件人地址 服务
     * @return SenderAddressService
     */
    private function getSenderAddressService()
    {
        return self::getInstance(SenderAddressService::class);
    }

    /**
     * 收人地址 服务
     * @return ReceiverAddressService
     */
    private function getReceiverAddressService()
    {
        return self::getInstance(ReceiverAddressService::class);
    }

    /**
     * 线路 服务
     * @return LineService
     */
    private function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 线路范围 服务
     * @return LineRangeService
     */
    private function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 线路区域 服务
     * @return LineAreaService
     */
    private function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    private function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 上传 服务
     * @return mixed
     */
    private function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 线路追踪 服务
     * @return RouteTrackingService
     */
    private function getRouteTrackingService()
    {
        return self::getInstance(RouteTrackingService::class);
    }

    /**
     * 运单 服务
     * @return TrackingOrderService
     */
    private function getTrackingOrderService()
    {
        return self::getInstance(TrackingOrderService::class);
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
        return parent::getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('订单不存在！');
        }
        $info['package_list'] = $this->getPackageService()->getList(['order_no' => $info['order_no']], ['*'], false);
        $info['material_list'] = $this->getMaterialService()->getList(['order_no' => $info['order_no']], ['*'], false);
        return $info;
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
        return $this->getTrackingOrderService()->getList(['order_no' => $dbOrder->order_no], ['*'], true);
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
        $this->query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_4, BaseConstService::PACKAGE_STATUS_5]);
        $info = $this->getPageList()->toArray(request());
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = parent::getInfo(['order_no' => $info[0]['order_no']], ['merchant_id', 'order_no', 'batch_no', 'tour_no', 'status'], false);
        $data['package_list'] = $this->getPackageService()->getList(['order_no' => $info[0]['order_no']], ['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount'], false);
        $data['material_list'] = $this->getMaterialService()->getList(['order_no' => $info[0]['order_no']], ['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'], false);
        $data = array_only_fields_sort($data, ['merchant_id', 'tour_no', 'batch_no', 'order_no', 'status', 'package_list', 'material_list']);
        return $data;
    }

    public function initStore()
    {
        $data = [];
        $data['nature_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderNatureList);
        $data['settlement_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderSettlementTypeList);
        $data['type'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$trackingOrderTypeList);
        $data['feature_logo_list'] = ['常温', '雪花', '风扇'];
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
        //生成运单
        $this->getTrackingOrderService()->storeByOrder($order);
        //新增订单明细列表
        $this->addAllItemList($params);
        return ['order_no' => $params['order_no']];
    }


    /**
     * 获取再次取派信息
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getAgainInfo($id)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], false, [BaseConstService::ORDER_STATUS_2], false);
        $dbTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $dbOrder['order_no']], ['*'], false, ['created_at' => 'desc']);
        if (!$trackingOrderType = $this->getTrackingOrderType($dbOrder, $dbTrackingOrder)) {
            throw new BusinessLogicException('当前订单不支持再次派送，请联系管理员');
        }
        $dbOrder['tracking_order_type'] = $trackingOrderType;
        $dbOrder['tracking_order_type_name'] = ConstTranslateTrait::trackingOrderTypeList($trackingOrderType);
        $dbOrder['tracking_order_id'] = $dbTrackingOrder->id;
        $resource = OrderAgainResource::make($dbOrder)->resolve();
        return $resource;
    }


    /**
     * @param $order
     * @param TrackingOrder
     * @return int|null
     */
    private function getTrackingOrderType($order, TrackingOrder $trackingOrder = null)
    {
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
     * 再次取派
     * @param $id
     * @param $params
     * @return bool
     * @throws BusinessLogicException
     */
    public function again($id, $params)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_2]);
        $dbTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $dbOrder['order_no']], ['*'], false, ['created_at' => 'desc']);
        $trackingOrderType = $this->getTrackingOrderType($dbOrder, $dbTrackingOrder);
        if ($trackingOrderType != $params['tracking_order_type']) {
            throw new BusinessLogicException('当前订单不支持再次派送，请刷新后再操作');
        }
        return $this->getTrackingOrderService()->storeAgain($dbOrder, $params, $trackingOrderType);
    }


    /**
     * 终止派送
     * @param $id
     * @throws BusinessLogicException
     */
    public function end($id)
    {
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_2]);
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $dbOrder['order_no']], ['tracking_order_no', 'order_no'], false, ['created_at' => 'desc']);
        if (!empty($trackingOrder) && in_array($trackingOrder->status, [BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4])) {
            throw new BusinessLogicException('当前订单正在[:status_name]', 1000, ['status_name' => $trackingOrder->status_name]);
        }
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
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
            $list[$i] = $this->form($list[$i]);
            empty($list[$i]['receiver_country']) && $list[$i]['receiver_country'] = CompanyTrait::getCountry();
            try {
                $this->store($list[$i], BaseConstService::ORDER_SOURCE_2);
            } catch (BusinessLogicException $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
            }
            /*            catch (\Exception $e) {
                            throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
                        }*/
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
        Log::info('begin-path', $params);
        $params['path'] = str_replace(config('app.url') . '/storage/merchant/file', storage_path('app/public/merchant/file'), $params['path']);
        Log::info('end-path', $params);
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
        $itemList = array_flip(ConstTranslateTrait::$orderNatureList);
        //$countryNameList = array_unique(collect($data)->pluck('receiver_country_name')->toArray());
        //$countryShortList = CountryTrait::getShortListByName($countryNameList);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = array_map('strval', $data[$i]);
            //反向翻译
            $data[$i]['type'] = $typeList[$data[$i]['type']];
            $data[$i]['settlement_type'] = $settlementList[$data[$i]['settlement_type']];
            $data[$i]['delivery'] = $deliveryList[$data[$i]['delivery']] ?? 2;
            $data[$i]['delivery'] = $data[$i]['delivery'] ?? __('是');
            for ($j = 1; $j <= 5; $j++) {
                $data[$i]['item_type_' . ($j)] = $itemList[$data[$i]['item_type_' . ($j)]] ?? null;
            }
            //日期如果是excel时间格式，转换成短横连接格式
            if (is_numeric($data[$i]['execution_date'])) {
                $data[$i]['execution_date'] = date('Y-m-d', ($data[$i]['execution_date'] - 25569) * 24 * 3600);
            }
            empty($data[$i]['receiver_country']) && $data[$i]['receiver_country'] = CompanyTrait::getCountry();//填充收件人国家
        }
        return $data;
    }

    /**
     * 订单格式转换
     * @param $data
     * @return mixed
     */
    public function form($data)
    {
        $data['package_list'] = [];
        $data['material_list'] = [];
        for ($j = 0; $j < 5; $j++) {
            if ($data['item_type_' . ($j + 1)] === 1 || $data['item_type_' . ($j + 1)] === '1') {
                $data['package_list'][$j]['name'] = $data['item_name_' . ($j + 1)];
                $data['package_list'][$j]['express_first_no'] = $data['item_number_' . ($j + 1)];
                $data['package_list'][$j]['weight'] = $data['item_weight_' . ($j + 1)] ?? 1;
                $data['package_list'][$j]['quantity'] = $data['item_count_' . ($j + 1)] ?? 1;
                $data['package_list'][$j]['express_second_no'] = '';
                $data['package_list'][$j]['out_order_no'] = '';
                $data['package_list'] = array_values($data['package_list']);
            } elseif ($data['item_type_' . ($j + 1)] === 2 || $data['item_type_' . ($j + 1)] === '2') {
                $data['material_list'][$j]['name'] = $data['item_name_' . ($j + 1)];
                $data['material_list'][$j]['code'] = $data['item_number_' . ($j + 1)];
                $data['material_list'][$j]['remark'] = '';
                $data['material_list'][$j]['quantity'] = $data['item_count_' . ($j + 1)] ?? 1;
                $data['material_list'][$j]['out_order_no'] = '';
                $data['material_list'] = array_values($data['material_list']);
            }
        }
        $data = Arr::only($data, [
            'type',
            'receiver_fullname',
            'receiver_phone',
            'receiver_country',
            'receiver_post_code',
            'receiver_house_number',
            'receiver_city',
            'receiver_street',
            'execution_date',
            'settlement_type',
            'settlement_amount',
            'replace_amount',
            'out_order_no',
            'delivery',
            'remark',
            'package_list',
            'material_list',
            'lon',
            'lat']);
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
            ['file' => 'required|file'],
            ['file.file' => '必须是文件']);
        if ($checkfile->fails()) {
            $error = array_values($checkfile->errors()->getMessages())[0][0];
            throw new BusinessLogicException($error, 301);
        }
    }

    /**
     * 验证
     * @param $params
     * @param $orderNo
     * @throws BusinessLogicException
     */
    private function check(&$params, $orderNo = null)
    {
        $params['receiver_post_code'] = str_replace(' ', '', $params['receiver_post_code']);
        //若是新增,则填充商户ID及国家
        if (empty($orderNo)) {
            $params['merchant_id'] = auth()->user()->id;
            //若邮编是纯数字，则认为是比利时邮编
            $params['receiver_country'] = post_code_be($params['receiver_post_code']) ? BaseConstService::POSTCODE_COUNTRY_BE : CompanyTrait::getCountry();
            if (empty($params['lat']) || empty($params['lon'])) {
                $location = LocationTrait::getLocation($params['receiver_country'], $params['receiver_city'], $params['receiver_street'], $params['receiver_house_number'], $params['receiver_post_code']);
                $params['lat'] = $location['lat'];
                $params['lon'] = $location['lon'];
            }
        }
        if (empty($params['lat']) || empty($params['lon'])) {
            throw new BusinessLogicException('地址数据不正确，请重新选择地址');
        }
        //获取经纬度
        $fields = ['receiver_house_number', 'receiver_city', 'receiver_street'];
        $params = array_merge(array_fill_keys($fields, ''), $params);
        $params['receiver_country'] = post_code_be($params['receiver_post_code']) ? BaseConstService::POSTCODE_COUNTRY_BE : CompanyTrait::getCountry();
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        !empty($params['package_list']) && $this->getPackageService()->check($params['package_list'], $orderNo);
        //验证材料列表
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //填充地址
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['receiver_address'])) {
            $params['receiver_address'] = CommonService::addressFieldsSortCombine($params, ['receiver_country', 'receiver_city', 'receiver_street', 'receiver_house_number', 'receiver_post_code']);
        }
        //若存在外部订单号,则判断是否存在已预约的订单号
        if (!empty($params['out_order_no'])) {
            $where = ['out_order_no' => $params['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_4, BaseConstService::TRACKING_ORDER_STATUS_5]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = parent::getInfo($where, ['id', 'order_no',], false);
            if (!empty($dbOrder)) {
                throw new BusinessLogicException('外部订单号已存在', 1002);
            }
        }
        $params['out_order_no'] = $params['out_order_no'] ?? '';
    }

    /**
     * 填充发件人信息
     * @param $params
     * @param $merchantAlone
     * @return array
     * @throws BusinessLogicException
     */
    private function fillSender(&$params, $merchantAlone = BaseConstService::NO)
    {
        //获取线路
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::TRACKING_ORDER_OR_BATCH_1, $merchantAlone);
        //获取仓库
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在');
        }
        //填充发件人信息
        $params = array_merge($params, [
            'sender_fullname' => $warehouse['fullname'],
            'sender_phone' => $warehouse['phone'],
            'sender_country' => $warehouse['country'],
            'sender_post_code' => $warehouse['post_code'],
            'sender_house_number' => $warehouse['house_number'],
            'sender_city' => $warehouse['city'],
            'sender_street' => $warehouse['street'],
            'sender_address' => $warehouse['address'],
        ]);
        return $line;
    }

    /**
     * 批量导入验证
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function importCheckByList($params)
    {
        $info = [];
        $package = [];
        $material = [];
        $list = json_decode($params['list'], true);
        for ($i = 0, $j = count($list); $i < $j; $i++) {
            for ($k = 0; $k < 5; $k++) {
                if ($list[$i]['item_type_' . ($k + 1)]) {
                    if ($list[$i]['item_type_' . ($k + 1)] === 1) {
                        if (in_array($list[$i]['item_number_' . ($k + 1)], $package)) {
                            $info[$i]['item_number_' . ($k + 1)] = __('物品') . ($k + 1) . __('编号有重复');
                        }
                        $package[] = $list[$i]['item_number_' . ($k + 1)];
                    }
                    if ($list[$i]['item_type_' . ($k + 1)] === 2) {
                        if (in_array($list[$i]['item_number_' . ($k + 1)], $material)) {
                            $info[$i]['item_number_' . ($k + 1)] = __('物品') . ($k + 1) . __('编号有重复');
                        }
                        $material[] = $list[$i]['item_number_' . ($k + 1)];
                    }
                }
            }
        }
        for ($i = 0, $j = count($list); $i < $j; $i++) {
            if (isset($info[$i])) {
                $list[$i] = array_merge($this->importCheck($list[$i]), $info[$i]);
            } else {
                $list[$i] = $this->importCheck($list[$i]);
            }
            if (isset($list[$i]['log']) && $list[$i]['log'] === __('当前订单没有合适的线路，请先联系管理员')) {
                $list[$i]['receiver_house_number'] = __('请检查输入');
                $list[$i]['receiver_post_code'] = __('请检查输入');
                $list[$i]['execution_date'] = __('请检查输入');
            }
            if (count($list[$i]) > 4) {
                $list[$i]['status'] = 0;
            } else {
                $list[$i]['status'] = 1;
            }
        }
        return $list;
    }

    /**
     * 单条导入验证
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function importCheck($data)
    {
        $list = [];
        $validate = new OrderImportValidate;
        $validator = Validator::make($data, $validate->rules, array_merge(BaseValidate::$baseMessage, $validate->message));
        if ($validator->fails()) {
            $key = $validator->errors()->keys();
            foreach ($key as $v) {
                $list[$v] = $validator->errors()->first($v);
            }
        }
        //如果没传经纬度，就去地址库拉经纬度
        if (empty($data['lon']) || empty($data['lat'])) {
            $address = $this->getReceiverAddressService()->getInfoByUnique($data);
            $list['lon'] = $address['lon'] ?? null;
            $list['lat'] = $address['lat'] ?? null;
            $list['receiver_city'] = $address['receiver_city'] ?? null;
            $list['receiver_street'] = $address['receiver_street'] ?? null;
            //如果地址库没有，就通过第三方API获取经纬度
            $fields = ['receiver_city', 'receiver_street'];
            $data = array_merge(array_fill_keys($fields, ''), $data);
            if (empty($data['lon']) || empty($data['lat'])) {
                try {
                    $info = LocationTrait::getLocation($data['receiver_country'], $data['receiver_city'], $data['receiver_street'], $data['receiver_house_number'], $data['receiver_post_code']);
                } catch (BusinessLogicException $e) {
                    $list['log'] = __($e->getMessage(), $e->replace);
                    $list['receiver_house_number'] = __('请检查输入');
                    $list['receiver_post_code'] = __('请检查输入');
                } catch (\Exception $e) {
                }
                $list['lon'] = $info['lon'] ?? '';
                $list['lat'] = $info['lat'] ?? '';
                $list['receiver_city'] = $info['city'] ?? '';
                $list['receiver_street'] = $info['street'] ?? '';
            }
        } else {
            $list['lon'] = $data['lon'] ?? null;
            $list['lat'] = $data['lat'] ?? null;
            $list['receiver_city'] = $data['receiver_city'] ?? null;
            $list['receiver_street'] = $data['receiver_street'] ?? null;
        }
        $package = [];
        $material = [];
        for ($j = 0; $j < 5; $j++) {
            if (!empty($data['item_type_' . ($j + 1)])) {
                if ($data['item_type_' . ($j + 1)] === 1) {
                    $package[$j] = $data['item_number_' . ($j + 1)];
                    $result[$j] = DB::table('package')->where('express_first_no', $data['item_number_' . ($j + 1)])->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_4, BaseConstService::PACKAGE_STATUS_5])->first();
                    if (!empty($result[$j])) {
                        $list['item_number_' . ($j + 1)] = __('物品') . ($j + 1) . __('编号有重复');
                    }
                } elseif ($data['item_type_' . ($j + 1)] === 2) {
                    if (in_array($data['item_number_' . ($j + 1)], $material)) {
                        $list['item_number_' . ($j + 1)] = __('物品') . ($j + 1) . __('编号有重复');
                    }
                    $material[$j] = $data['item_number_' . ($j + 1)];
                }
            }
        }
        //检查仓库
        try {
            $line = $this->getLineService()->getInfoByRule($data, BaseConstService::TRACKING_ORDER_OR_BATCH_1);
            $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        } catch (BusinessLogicException $e) {
            $list['log'] = __($e->getMessage(), $e->replace);
        } catch (\Exception $e) {
        }
        return $list;
    }

    /**
     * 添加货物列表
     * @param $params
     * @param $batch
     * @param $tour
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
            }
            $packageList = collect($params['package_list'])->map(function ($item, $key) use ($params, $status) {
                $collectItem = collect($item)->only(['name', 'express_first_no', 'express_second_no', 'out_order_no', 'feature_logo', 'weight', 'expect_quantity', 'remark', 'is_auth']);
                return $collectItem
                    ->put('order_no', $params['order_no'])
                    ->put('merchant_id', $params['merchant_id'])
                    ->put('execution_date', $params['execution_date'])
                    ->put('status', $status)
                    ->put('type', $params['type']);
            })->toArray();
            $rowCount = $this->getPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单包裹新增失败！');
            }
        }
        //若材料存在,则新增材料列表
        if (!empty($params['material_list'])) {
            $materialList = collect($params['material_list'])->map(function ($item, $key) use ($params, $batch, $tour) {
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
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        unset($data['order_no'], $data['tour_no'], $data['batch_no']);
        //获取信息
        $dbOrder = $this->getInfoByIdOfStatus(['id' => $id], true);
        if (intval($dbOrder['source']) === BaseConstService::ORDER_SOURCE_3) {
            throw new BusinessLogicException('第三方订单不能修改');
        }
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
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $this->getTrackingOrderService()->updateByOrder($data);
    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePhoneDateByApi($id, $data)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2]);
        if (!empty($data['receiver_phone']) && empty($data['execution_date'])) {
            $data = Arr::only($data, ['receiver_phone']);
        } elseif (empty($data['receiver_phone']) && !empty($data['execution_date'])) {
            $data = Arr::only($data, ['execution_date']);
        } elseif (empty($data['receiver_phone']) && empty($data['execution_date'])) {
            throw new BusinessLogicException('电话或取派日期必填其一');
        }
        //分类
        if ($info['status'] < BaseConstService::TRACKING_ORDER_STATUS_3) {
            return $this->updateDatePhone($info, $data);
        } elseif (in_array($info['status'], [BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4]) && empty($data['execution_date'])) {
            return $this->updatePhone($info, $data);
        } else {
            throw new BusinessLogicException('该状态无法进行此操作');
        }
    }

    /**
     * 通过API修改（电话，取派日期）
     * @param $dbInfo
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function updateDatePhone($dbInfo, $data)
    {
        $newData = array_merge($dbInfo, $data);
        /*************************************************订单修改******************************************************/
        //获取信息
        $dbInfo['package_list'] = $this->getPackageService()->getList(['order_no' => $newData['order_no']], ['*'], false)->toArray();
        $dbInfo['material_list'] = $this->getMaterialService()->getList(['order_no' => $newData['order_no']], ['*'], false)->toArray();
        //验证
        unset($newData['tour_no'], $newData['batch_no'], $data['order_no'], $data['tour_no'], $data['batch_no']);
        /******************************更换站点***************************************/
        $line = $this->fillSender($newData);
        list($batch, $tour) = $this->changeBatch($dbInfo, $newData, $line, true);

        //修改
        $rowCount = parent::updateById($dbInfo['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);

        $order = parent::getInfo(['order_no' => $dbInfo['order_no']], ['*'], false)->toArray();
        if (!empty($order['tour_no'])) {
            $tour = $this->getTourService()->getInfo(['tour_no' => $order['tour_no']], ['*'], false);
        }
        return [
            'order_no' => $dbInfo['order_no'],
            'batch_no' => $order['batch_no'] ?? '',
            'tour_no' => $order['tour_no'] ?? '',
            'line' => [
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
            ]
        ];
    }

    /**
     * @param $info
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePhone($info, $data)
    {
        $row = parent::update(['batch_no' => $info['batch_no']], $data);
        if ($row === false) {
            throw new BusinessLogicException('操作失败');
        }
        $batch = $this->getBatchService()->update(['batch_no' => $info['batch_no']], $data);
        if ($batch === false) {
            throw new BusinessLogicException('操作失败');
        }
        $tour = $this->getTourService()->getInfo(['tour_no' => $info['tour_no']], ['*'], false);
        return [
            'order_no' => $info['order_no'],
            'batch_no' => $info['batch_no'] ?? '',
            'tour_no' => $info['tour_no'] ?? '',
            'line' => [
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
            ]
        ];
    }

    /**
     * 订单更换站点
     * @param $dbInfo
     * @param $data
     * @param $line
     * @param bool $isFillItem
     * @return array
     * @throws BusinessLogicException
     */
    private function changeBatch($dbInfo, $data, $line, $isFillItem = false)
    {
        //站点移除订单,添加新的订单
        if (!empty($dbInfo['batch_no'])) {
            $this->getBatchService()->removeOrder($dbInfo);
            //重新统计站点金额
            $this->getBatchService()->reCountAmountByNo($dbInfo['batch_no']);
            //重新统计取件线路金额
            !empty($dbInfo['tour_no']) && $this->getTourService()->reCountAmountByNo($dbInfo['tour_no']);
        }
        list($batch, $tour) = $this->getBatchService()->join($data, $line);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($dbInfo, $batch, $tour, $isFillItem);
        return [$batch, $tour];
    }

    /**
     * 获取可选日期验证
     * @param $info
     * @throws BusinessLogicException
     */
    public function validate($info)
    {
        if (CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_AREA) {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'lon' => 'required|string|max:50', 'lat' => 'required|string|max:50']);
        } else {
            $validator = Validator::make($info, ['type' => 'required|integer|in:1,2', 'receiver_post_code' => 'required|string|max:50']);
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
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_6, BaseConstService::TRACKING_ORDER_STATUS_7]);
        //若当前订单已取消取派了,在直接返回成功，不再删除
        if (in_array(intval($info['status']), [BaseConstService::TRACKING_ORDER_STATUS_6, BaseConstService::TRACKING_ORDER_STATUS_7])) {
            return 'true';
        }
        $rowCount = parent::updateById($info['id'], ['status' => BaseConstService::TRACKING_ORDER_STATUS_7, 'remark' => $params['remark'] ?? '', 'execution_date' => null, 'batch_no' => '', 'tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单删除失败，请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '', 'execution_date' => null, 'status' => BaseConstService::PACKAGE_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点和取件线路信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '', 'execution_date' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //站点移除订单
        if (!empty($info['batch_no'])) {
            $this->getBatchService()->removeOrder($info);
        }
        //重新统计站点金额
        !empty($info['batch_no']) && $this->getBatchService()->reCountAmountByNo($info['batch_no']);
        //重新统计取件线路金额
        !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);

        TrackingOrderTrailService::TrackingOrderStatusChangeCreateTrail($info, BaseConstService::TRACKING_ORDER_TRAIL_DELETE);
        if ((!empty($params['no_push']) && $params['no_push'] == 0) || empty($params['no_push'])) {
            //以取消取派方式推送商城
            event(new OrderCancel($info['order_no'], $info['out_order_no']));
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
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3]);
        $rowCount = parent::updateById($info['id'], ['out_status' => $params['out_status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
    }

    /**
     * 修改订单明细列表
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateItemList($id, $params)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4]);
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        !empty($params['package_list']) && $this->getPackageService()->check($params['package_list'], $info['order_no']);
        //验证材料列表
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //获取包裹
        $dbMaterialList = $this->getMaterialService()->getList(['order_no' => $info['order_no']], ['*'], false)->toArray();
        //删除包裹和材料
        $rowCount = $this->getPackageService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        $rowCount = $this->getMaterialService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //新增包裹和材料
        $info['package_list'] = $params['package_list'] ?? [];
        $info['material_list'] = $params['material_list'] ?? [];
        $this->addAllItemList($info);
        //若司机已出库,则处理取件线路中对应材料数量
        if (intval($info['status']) === BaseConstService::TRACKING_ORDER_STATUS_4) {
            foreach ($dbMaterialList as $dbMaterial) {
                $dbTourMaterial = $this->tourMaterialModel->newQuery()->where('tour_no', $info['tour_no'])->where('code', $dbMaterial['code'])->first();
                if (!empty($dbTourMaterial)) {
                    $diffExpectQuantity = $dbTourMaterial->expect_quantity - $dbMaterial['expect_quantity'];
                    $rowCount = $this->tourMaterialModel->newQuery()->where('id', $dbTourMaterial->id)->update(['expect_quantity' => $diffExpectQuantity]);
                    if ($rowCount === false) {
                        throw new BusinessLogicException('材料处理失败');
                    }
                }
            }
            $materialList = $params['material_list'] ?? [];
            foreach ($materialList as $material) {
                $dbTourMaterial = $this->tourMaterialModel->newQuery()->where('tour_no', $info['tour_no'])->where('code', $material['code'])->first();
                if (empty($dbTourMaterial)) {
                    $rowCount = $this->tourMaterialModel->newQuery()->create([
                        'tour_no' => $info['tour_no'],
                        'name' => $material['name'],
                        'code' => $material['code'],
                        'expect_quantity' => $material['expect_quantity'],
                    ]);
                } else {
                    $rowCount = $this->tourMaterialModel->newQuery()->where('id', $dbTourMaterial->id)->update(['expect_quantity' => $dbTourMaterial->expect_quantity + $material['expect_quantity']]);
                }
                if ($rowCount === false) {
                    throw new BusinessLogicException('材料处理失败');
                }
            }
            //删除预计数量为0的取件材料
            $rowCount = $this->tourMaterialModel->newQuery()->where('tour_no', $info['tour_no'])->where('expect_quantity', 0)->delete();
            if ($rowCount === false) {
                throw new BusinessLogicException('材料处理失败');
            }
        }
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
        $info = parent::getInfo($where, ['batch_no'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batch = $this->getBatchService()->getInfo(['batch_no' => $info->batch_no], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchList = $this->getBatchService()->getList(['tour_no' => $batch['tour_no']], ['*'], false);
        $count = $batchList->where('status', '=', BaseConstService::BATCH_DELIVERING)->where('sort_id', '<', $batch['sort_id'])->count();
        //处理预计耗时
        if (!empty($batch->expect_arrive_time)) {
            $expectTime = strtotime($batch->expect_arrive_time) - time();
        } else {
            $expectTime = 0;
        }
        $routeTracking = $this->getRouteTrackingService()->getInfo(['tour_no' => $batch->tour_no], ['lon', 'lat'], false, ['id' => 'desc']) ?? '';
        if (empty($routeTracking)) {
            $routeTracking = $this->getTourService()->getInfo(['tour_no' => $batch->tour_no], ['*'], false);
            $routeTracking['lon'] = $routeTracking['warehouse_lon'];
            $routeTracking['lat'] = $routeTracking['warehouse_lat'];
        }
        return [
            'expect_distance' => $batch['expect_distance'] ?? 0,
            'actual_distance' => $batch['actual_distance'] ?? 0,
            'expect_time' => ($expectTime >= 0) ? $expectTime : 0,
            'actual_time' => $batch['actual_time'] ?? 0,
            'expect_arrive_time' => $batch['expect_arrive_time'] ?? '',
            'actual_arrive_time' => $batch['actual_arrive_time'] ?? '',
            'receiver_lon' => $batch['receiver_lon'] ?? '',
            'receiver_lat' => $batch['receiver_lat'] ?? '',
            'driver_lon' => $routeTracking['lon'] ?? '',
            'driver_lat' => $routeTracking['lat'] ?? '',
            'rest_batch' => $count,
            'out_order_no' => $info['out_order_no'] ?? ''
        ];
    }

}
