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
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Jobs\AddOrderPush;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\CommonService;
use App\Services\OrderNoRuleService;
use App\Traits\BarcodeTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use App\Traits\PrintTrait;
use Illuminate\Support\Arr;
use App\Services\TrackingOrderTrailService;
use Illuminate\Support\Facades\Validator;

class OrderService extends BaseService
{
    use ImportTrait, LocationTrait, CountryTrait, ExportTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no,out_user_id' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'source' => ['=', 'source'],
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
        'receiver_post_code',
        'receiver_house_number',
        'execution_date',
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
    public function getMaterialService()
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
    public function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }


    /**
     * 发件人地址 服务
     * @return SenderAddressService
     */
    public function getSenderAddressService()
    {
        return self::getInstance(SenderAddressService::class);
    }

    /**
     * 收人地址 服务
     * @return ReceiverAddressService
     */
    public function getReceiverAddressService()
    {
        return self::getInstance(ReceiverAddressService::class);
    }

    /**
     * 线路 服务
     * @return LineService
     */
    public function getLineService()
    {
        return self::getInstance(LineService::class);
    }

    /**
     * 线路范围 服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 仓库 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 上传 服务
     * @return mixed
     */
    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 商户 服务
     * @return MerchantService
     */
    private function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * 打印模板 服务
     * @return PrintTemplateService
     */
    private function getPrintTemplateService()
    {
        return self::getInstance(PrintTemplateService::class);
    }

    /**
     * 区域 服务
     * @return LineAreaService
     */
    private function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);

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
        $dbOrder = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($dbOrder)) {
            throw new BusinessLogicException('订单不存在！');
        }
        $dbOrder['package_list'] = $this->getPackageService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
        $dbOrder['material_list'] = $this->getMaterialService()->getList(['order_no' => $dbOrder['order_no']], ['*'], false);
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
        return $this->getTrackingOrderService()->getList(['order_no' => $dbOrder->order_no], ['*'], true);
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
        $dbOrder = parent::getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_2], false);
        if (!$trackingOrderType = $this->getTrackingOrderType($dbOrder)) {
            throw new BusinessLogicException('当前订单不支持再次派送，请联系管理员');
        }
        $dbOrder['tracking_order_type'] = $trackingOrderType;
        return $dbOrder;
    }

    /**
     * @param $order
     * @return int|null
     */
    private function getTrackingOrderType($order)
    {
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], ['*'], false, ['created_at' => 'desc']);
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
        //6.当运单存在时，当运单为取派完成，当订单为取派件,若运单为取件类型，则表示不需要新增运单
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
        $trackingOrderType = $this->getTrackingOrderType($dbOrder);
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
                'receiver_fullname',
                'receiver_phone',
                'receiver_country',
                'receiver_post_code',
                'receiver_house_number',
                'receiver_address',
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
            $info = $this->getReceiverAddressService()->getInfo($list[$i]);
            $list[$i]['sender_fullname'] = $list[$i]['sender_phone'] = $list[$i]['sender_country'] = $list[$i]['sender_post_code']
                = $list[$i]['sender_house_number'] = $list[$i]['sender_address'] = $list[$i]['sender_city'] = $list[$i]['sender_street']
                = $list[$i]['receiver_city'] = $list[$i]['receiver_street'] = '';
            if (empty($info)) {
                $info = $this->getLocation($list[$i]['receiver_country'], $list[$i]['receiver_city'], $list[$i]['receiver_street'], $list[$i]['receiver_house_number'], $list[$i]['receiver_post_code']);
            }
            $list[$i]['lon'] = $info['lon'];
            $list[$i]['lat'] = $info['lat'];
            try {
                $this->store($list[$i], true);
            } catch (BusinessLogicException $e) {
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
        $itemList = array_flip(ConstTranslateTrait::$orderNatureList);
        //$countryNameList = array_unique(collect($data)->pluck('receiver_country_name')->toArray());
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
            empty($data[$i]['receiver_country']) && $data[$i]['receiver_country'] = CompanyTrait::getCountry();//填充收件人国家
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
     * 验证
     * @param $params
     * @param $orderNo
     * @throws BusinessLogicException
     */
    private function check(&$params, $orderNo = null)
    {
        $params['receiver_post_code'] = str_replace(' ', '', $params['receiver_post_code']);
        //获取经纬度
        $fields = ['receiver_house_number', 'receiver_city', 'receiver_street'];
        $params = array_merge(array_fill_keys($fields, ''), $params);
        //通过商户获取国家
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id'], 'status' => BaseConstService::MERCHANT_STATUS_1], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('商户不存在');
        }
        //若邮编是纯数字，则认为是比利时邮编
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
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['sender_address'])) {
            $params['sender_address'] = CommonService::addressFieldsSortCombine($params, ['sender_country', 'sender_city', 'sender_street', 'sender_house_number', 'sender_post_code']);
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
    }

    /**
     * 添加货物列表
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
        $dbOrder = $this->getInfoOfStatus(['id' => $id], true);
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
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        //获取信息
        $dbOrder = $this->getInfoOfStatus(['id' => $id], true);
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        $this->getTrackingOrderService()->destroyByOrderNo($dbOrder['order_no']);
    }

    /**
     * 批量删除
     * @param $idList
     * @throws BusinessLogicException
     */
    public function destroyAll($idList)
    {
        $idList = explode_id_string($idList);
        foreach ($idList as $id) {
            $this->destroy($id);
        }
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
     * 批量订单打印
     * @param $idList
     * @return array
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function orderPrintAll($idList)
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
            $order['receiver_address_short'] = $order['receiver_country_name'] . ' ' . $order['receiver_city'];
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
     * 订单导出
     * @return array
     * @throws BusinessLogicException
     */
    public function orderExport()
    {
        $orderList = $this->getPageList();
        if ($orderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderList->pluck('order_no')->toArray()]]);
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderList->pluck('order_no')->toArray()]]);
        $merchant = $this->getMerchantService()->getList(['id' => ['in', $orderList->pluck('merchant_id')->toArray()]]);
        if ($merchant->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $orderList = $orderList->toArray(request());
        foreach ($orderList as $k => $v) {
            $orderList[$k]['merchant_name'] = $v['merchant_id_name'];
            $orderList[$k]['status'] = $v['status_name'];
            $orderList[$k]['type'] = $v['type_name'];
            $orderList[$k]['sticker_amount'] = $v['sticker_amount'] ?? 0.00;
            $orderList[$k]['replace_amount'] = $v['replace_amount'] ?? 0.00;
            $orderList[$k]['settlement_amount'] = $v['settlement_amount'] ?? 0.00;
            if (!empty($packageList)) {
                $orderList[$k]['package_name'] = implode(',', collect($packageList)->where('order_no', $v['order_no'])->pluck('express_first_no')->toArray());
                $orderList[$k]['package_quantity'] = count(collect($packageList)->where('order_no', $v['order_no']));
            } else {
                $orderList[$k]['package_name'] = [];
                $orderList[$k]['package_quantity'] = 0;
            }
            if (!empty($materialList)) {
                $orderList[$k]['material_name'] = implode(',', collect($materialList)->where('order_no', $v['order_no'])->pluck('express_first_no')->toArray());
                $orderList[$k]['material_quantity'] = count(collect($materialList)->where('order_no', $v['order_no']));
            } else {
                $orderList[$k]['material_name'] = [];
                $orderList[$k]['material_quantity'] = 0;
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
     */
    public function synchronizeStatusList($idList)
    {
        //获取订单列表
        $idList = explode_id_string($idList);
        $orderList = parent::getList(['id' => ['in', $idList]], ['*'], false)->toArray();
        $orderNoList = array_column($orderList, 'order_no');
        //获取包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['name', 'order_no', 'express_first_no', 'express_second_no', 'out_order_no', 'expect_quantity', 'actual_quantity', 'status', 'sticker_no', 'sticker_amount', 'delivery_amount', 'is_auth', 'auth_fullname', 'auth_birth_date'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        //获取材料列表
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['order_no', 'name', 'code', 'out_order_no', 'expect_quantity', 'actual_quantity'], false)->toArray();
        $materialList = array_create_group_index($materialList, 'order_no');
        //获取站点列表
        $batchNoList = array_column($orderList, 'batch_no');
        $batchList = $this->getBatchService()->getList(['batch_no' => ['in', $batchNoList]], ['*'], false)->toArray();
        $batchList = array_create_index($batchList, 'batch_no');
        //获取取件线路列表
        $tourNoList = array_column($orderList, 'tour_no');
        $tourList = $this->getTourService()->getList(['tour_no' => ['in', $tourNoList]], ['*'], false)->toArray();
        $tourList = array_create_index($tourList, 'tour_no');
        //组合数据
        foreach ($orderList as &$order) {
            $orderNo = $order['order_no'];
            $order['package_list'] = $packageList[$orderNo] ?? [];
            $order['material_list'] = $materialList[$orderNo] ?? [];
            $order['delivery_count'] = (floatval($order['delivery_amount']) == 0) ? 0 : 1;
            $order['cancel_remark'] = $batchList[$order['batch_no']]['cancel_remark'] ?? '';
            $order['signature'] = $batchList[$order['batch_no']]['signature'] ?? '';
            $order['pay_type'] = $batchList[$order['batch_no']]['pay_type'] ?? null;
            $order['line_id'] = $tourList[$order['tour_no']]['line_id'] ?? null;
            $order['line_name'] = $tourList[$order['tour_no']]['line_name'] ?? '';
            $order['driver_id'] = $tourList[$order['tour_no']]['driver_id'] ?? null;
            $order['driver_name'] = $tourList[$order['tour_no']]['driver_name'] ?? '';
            $order['driver_phone'] = $tourList[$order['tour_no']]['driver_phone'] ?? '';
            $order['car_id'] = $tourList[$order['tour_no']]['car_id'] ?? null;
            $order['car_no'] = $tourList[$order['tour_no']]['car_no'] ?? '';
        }
        dispatch(new \App\Jobs\SyncOrderStatus($orderList));
    }
}
