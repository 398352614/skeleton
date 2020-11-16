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
use App\Http\Resources\Api\Admin\OrderInfoResource;
use App\Http\Resources\Api\Admin\OrderResource;
use App\Jobs\AddOrderPush;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Services\BaseConstService;
use App\Services\Admin\BaseService;
use App\Services\CommonService;
use App\Services\OrderNoRuleService;
use App\Traits\BarcodeTrait;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ExportTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use App\Traits\OrderStatisticsTrait;
use App\Traits\PrintTrait;
use http\Exception\BadConversionException;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderService extends BaseService
{
    use ImportTrait, LocationTrait, CountryTrait, ExportTrait, OrderStatisticsTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no,out_user_id' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'merchant_id' => ['=', 'merchant_id'],
        'source' => ['=', 'source'],
        'tour_no' => ['like', 'tour_no'],
        'batch_no' => ['like', 'batch_no'],
        'receiver_post_code' => ['like', 'receiver_post_code']
    ];

    public $headings = [
        'order_no',
        'merchant_name',
        'status',
        'out_order_no',
        'receiver_post_code',
        'receiver_house_number',
        'execution_date',
        'driver_name',
        'batch_no',
        'tour_no',
        'line_name',
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
        if (!array_key_exists('type', $params)) {
            throw new BusinessLogicException('订单取派类型有误，无法获取统计数据');
        }
        if (!empty($params['type']) && !in_array($params['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_NATURE_2])) {
            throw new BusinessLogicException('订单取派类型有误，无法获取统计数据');
        }
        return [
            'all_count' => $this->singleOrderCount($params['type']),
            'no_take' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_1),
            'assign' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_2),
            'wait_out' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_3),
            'taking' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_4),
            'singed' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_5),
            'cancel_count' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_6),
            'delete_count' => $this->singleOrderCount($params['type'], BaseConstService::ORDER_STATUS_7),
            'exception_count' => $this->singleOrderCount($params['type'], null, BaseConstService::ORDER_EXCEPTION_LABEL_2),
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

    /**
     * 获取所有线路
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getLineList()
    {
        if (CompanyTrait::getCompany()['show_type'] == BaseConstService::LINE_RULE_SHOW && CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_POST_CODE) {
            $info = $this->getLineRangeService()->getList([], ['*'], false);
            $lineId = $info->pluck('line_id')->toArray();
        } elseif (CompanyTrait::getCompany()['show_type'] == BaseConstService::LINE_RULE_SHOW && CompanyTrait::getLineRule() == BaseConstService::LINE_RULE_AREA) {
            $info = $this->getLineAreaService()->getList([], ['*'], false);
            $lineId = $info->pluck('line_id')->toArray();
        } else {
            $info = $this->getLineService()->getList([], ['*'], false);
            $lineId = $info->pluck('id')->toArray();
        }
        if (!empty($lineId)) {
            $data = $this->getLineService()->getList(['id' => ['in', $lineId]], ['id', 'name'], false);
        }
        return $data ?? [];
    }

    public function getPageList()
    {
        if (!empty($this->formData['line_id']) && !empty($this->formData['tour_no'])) {
            $tourList = $this->getTourService()->getList(['line_id' => $this->formData['line_id']])->pluck('tour_no')->toArray();
            $this->query->whereIn('tour_no', $tourList)->where('tour_no', 'like', $this->formData['tour_no']);
        } elseif (!empty($this->formData['line_id'])) {
            $tourList = $this->getTourService()->getList(['line_id' => $this->formData['line_id']])->pluck('tour_no')->toArray();
            $this->query->whereIn('tour_no', $tourList);
        }
        $list = parent::getPageList();
        $tourNoList = collect($list)->where('tour_no', '<>', '')->pluck('tour_no')->toArray();
        $tour = $this->getTourService()->getList(['tour_no' => ['in', $tourNoList]], ['*'], false);
        foreach ($list as $k => $v) {
            $list[$k]['line_id'] = $tour->where('tour_no', $v['tour_no'])->first()['line_id'] ?? '';
            $list[$k]['line_name'] = $tour->where('tour_no', $v['tour_no'])->first()['line_name'] ?? '';
        }
        foreach ($list as &$order) {
            $batchException = $this->getBatchExceptionService()->getInfo(['batch_no' => $order['batch_no']], ['id', 'batch_no', 'stage'], false, ['created_at' => 'desc']);
            $order['exception_stage_name'] = !empty($batchException) ? ConstTranslateTrait::batchExceptionStageList($batchException['stage']) : __('正常');
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
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('订单不存在！');
        }
        $info['package_list'] = $this->getPackageService()->getList(['order_no' => $info['order_no']], ['*'], false);
        $info['material_list'] = $this->getMaterialService()->getList(['order_no' => $info['order_no']], ['*'], false);
        return $info;
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
        //填充发件人信息
        $line = $this->fillSender($params, BaseConstService::YES);
        //设置订单来源
        data_set($params, 'source', $orderSource);
        /*************************************************订单新增************************************************/
        //生成单号
        $params['order_no'] = $this->getOrderNoRuleService()->createOrderNo();
        $order = parent::create($params);
        if ($order === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        $order = $order->getAttributes();
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($order, $line);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order, $batch, $tour, false);
        /**************************************新增订单货物明细********************************************************/
        $this->addAllItemList($params, $batch, $tour);
        //自动记录
        $this->record($params);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);
        //订单轨迹-订单创建
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_CREATED);
        //订单轨迹-订单加入站点
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH, $batch);
        //订单轨迹-订单加入取件线路
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_TOUR, $tour);
        return [
            'order_no' => $params['order_no'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'line' => [
                'line_id' => $tour['line_id'],
                'line_name' => $tour['line_name'],
            ]
        ];
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
        $typeList = array_flip(ConstTranslateTrait::$orderTypeList);
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
     * 自动记录
     * @param $params
     * @throws BusinessLogicException
     */
    public function record($params)
    {
        //记录发件人地址
        $info = $this->getSenderAddressService()->getInfoByUnique($params);
        if (empty($info)) {
            $this->getSenderAddressService()->create($params);
        }
        //记录收件人地址
        $info = $this->getReceiverAddressService()->getInfoByUnique($params);
        if (empty($info)) {
            $this->getReceiverAddressService()->create($params);
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
        //若存在外部订单号,则判断是否存在已预约的订单号
        if (!empty($params['out_order_no'])) {
            $where = ['out_order_no' => $params['out_order_no'], 'status' => ['not in', [BaseConstService::ORDER_STATUS_6, BaseConstService::ORDER_STATUS_7]]];
            !empty($orderNo) && $where['order_no'] = ['<>', $orderNo];
            $dbOrder = parent::getInfo($where, ['id', 'order_no', 'batch_no', 'tour_no'], false);
            if (!empty($dbOrder)) {
                $data = ['order_no' => $dbOrder->order_no, 'batch_no' => $dbOrder->batch_no ?? '', 'tour_no' => $dbOrder->tour_no ?? ''];
                if (!empty($dbOrder->tour_no)) {
                    $tour = $this->getTourService()->getInfo(['tour_no' => $dbOrder->tour_no], ['line_id', 'line_name'], false);
                    if (empty($tour)) {
                        $data['line'] = [];
                    } else {
                        $data['line'] = ['line_id' => $tour->line_id, 'line_name' => $tour->line_name];
                    }
                }
                throw new BusinessLogicException('外部订单号已存在', 1002, [], $data);
            }
        }
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
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::ORDER_OR_BATCH_1, $merchantAlone);
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
     * 添加货物列表
     * @param $params
     * @param $batch
     * @param $tour
     * @throws BusinessLogicException
     */
    private function addAllItemList($params, $batch, $tour)
    {
        $status = $tour['status'] ?? BaseConstService::PACKAGE_STATUS_1;
        $relationship = ['雪花' => '冷冻', '风扇' => '风房'];
        //若存在包裹列表,则新增包裹列表
        if (!empty($params['package_list'])) {
            foreach ($params['package_list'] as $k => $v) {
                if (!empty($params['package_list'][$k]['feature_logo']) && in_array($params['package_list'][$k]['feature_logo'], array_keys($relationship))) {
                    $params['package_list'][$k]['feature_logo'] = $relationship[$params['package_list'][$k]['feature_logo']];
                }
            }
            $packageList = collect($params['package_list'])->map(function ($item, $key) use ($params, $batch, $tour) {
                $collectItem = collect($item)->only(['name', 'express_first_no', 'express_second_no', 'out_order_no', 'feature_logo', 'weight', 'expect_quantity', 'remark', 'is_auth']);
                return $collectItem
                    ->put('order_no', $params['order_no'])
                    ->put('batch_no', $batch['batch_no'])
                    ->put('tour_no', $tour['tour_no'])
                    ->put('merchant_id', $params['merchant_id'])
                    ->put('execution_date', $params['execution_date'])
                    ->put('status', $tour['status'] ?? BaseConstService::PACKAGE_STATUS_1)
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
                    ->put('batch_no', $batch['batch_no'])
                    ->put('tour_no', $tour['tour_no'])
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
     * 填充站点信息和取件线路信息
     * @param $order
     * @param $batch
     * @param $tour
     * @param bool $isFillItem
     * @throws BusinessLogicException
     */
    public function fillBatchTourInfo($order, $batch, $tour, $isFillItem = true)
    {
        $status = $tour['status'] ?? BaseConstService::ORDER_STATUS_1;
        $rowCount = parent::updateById($order['id'], [
            'execution_date' => $batch['execution_date'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'driver_phone' => $tour['driver_phone'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $status,
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        if ($isFillItem === false) return;
        //填充包裹
        $rowCount = $this->getPackageService()->update(['order_no' => $order['order_no']], [
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'status' => $status,
            'execution_date' => $batch['execution_date']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //填充材料
        $rowCount = $this->getMaterialService()->update(['order_no' => $order['order_no']], [
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'status' => $status,
            'execution_date' => $batch['execution_date']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
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
        /*************************************************订单修改******************************************************/
        //获取信息
        $dbInfo = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3, BaseConstService::ORDER_STATUS_4]);
        if (empty($dbInfo)) {
            throw new BusinessLogicException('数据不存在');
        }
        Log::info('true', [$this->updateBaseInfo($dbInfo, $data) == true]);
        if ($this->updateBaseInfo($dbInfo, $data) == true) {
            return '';
        }
        if (intval($dbInfo['source']) === BaseConstService::ORDER_SOURCE_3) {
            throw new BusinessLogicException('第三方订单不能修改');
        }
        if (!in_array($dbInfo['status'], [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2])) {
            throw new BadConversionException('该状态下订单无法修改');
        }
        //验证
        $this->check($data, $dbInfo['order_no']);
        $data = Arr::add($data, 'order_no', $dbInfo['order_no']);
        $data = Arr::add($data, 'status', $dbInfo['status']);
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $isChangeBatch = $this->checkIsChangeBatch($dbInfo, $data);
        if ($isChangeBatch === true) {
            $line = $this->fillSender($data);
            list($batch, $tour) = $this->changeBatch($dbInfo, $data, $line);
        } else {
            $this->getBatchService()->updateAboutOrderByOrder($dbInfo, $data);
            $batch = ['batch_no' => $dbInfo['batch_no']];
            $tour = ['tour_no' => $dbInfo['tour_no']];
        }
        //修改
        $rowCount = parent::updateById($dbInfo['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        /*********************************************更换清单列表***************************************************/
        //删除包裹列表
        $rowCount = $this->getPackageService()->delete(['order_no' => $dbInfo['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //删除材料列表
        $rowCount = $this->getMaterialService()->delete(['order_no' => $dbInfo['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        //新增包裹列表和材料列表
        $this->addAllItemList($data, $batch, $tour);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);

        //更换取派日期通知
        //($isChangeBatch === true) && event(new OrderExecutionDateUpdated($dbInfo['order_no'], $dbInfo['out_order_no'], $data['execution_date'], $batch['batch_no'], ['tour_no' => $tour['tour_no'], 'line_id' => $tour['line_id'], 'line_name' => $tour['line_name']));
    }

    /**
     * 待出库，取派中的订单修改特殊事项
     * @param $dbInfo
     * @param $data
     * @return bool
     * @throws BusinessLogicException
     */
    public function updateBaseInfo($dbInfo, $data)
    {
        $newData=Arr::only($data,array_keys($dbInfo));
        $columns = ['special_remark','id'];
        foreach ($newData as $k => $v) {
            if (!in_array($k, $columns) && $v != $dbInfo[$k]) {
                return false;
            }
        }
        $dbPackageList = $this->getPackageService()->getList(['order_no' => $dbInfo['order_no']],['*'],false);
        foreach ($data['package_list'] as $k => $v) {
            foreach ($v as $x => $y) {
                if ($y !== collect($dbPackageList)->where('express_first_no', $v['express_first_no'])->$x) {
                    dd($x,$y);
                    return false;
                }
            }
        }
        $dbMaterialList = $this->getMaterialService()->getList(['order_no' => $dbInfo['order_no']],['*'],false);
        foreach ($data['material_list'] as $k => $v) {
            foreach ($v as $x => $y) {
                if ($y !== collect($dbMaterialList)->where('code', $v['code'])->$x) {
                    dd($x,$y);
                    return false;
                }
            }
        }
        $rowCount = parent::updateById($dbInfo['id'], Arr::only($data, $columns));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        return true;
    }

    /**
     * 判断是否需要更换站点
     * @param $dbOrder
     * @param $order
     * @return bool
     */
    private function checkIsChangeBatch($dbOrder, $order)
    {
        $fields = ['execution_date', 'receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'];
        $newDbOrder = Arr::only($dbOrder, $fields);
        $newOrder = Arr::only($order, $fields);
        return empty(array_diff($newDbOrder, $newOrder)) ? false : true;
    }


    /**
     * 订单更换站点
     * @param $dbInfo
     * @param $data
     * @param $line
     * @return array
     * @throws BusinessLogicException
     */
    private function changeBatch($dbInfo, $data, $line)
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
        $this->fillBatchTourInfo($dbInfo, $batch, $tour, false);
        return [$batch, $tour];
    }

    /**
     * 通过订单获得可选日期
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getTourDate($id)
    {
        $params = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($params)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = $this->getLineService()->getScheduleList($params);
        return $data;
    }

    /**
     * 通过地址获得可选日期
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getDate($params)
    {
        $this->validate($params);
        $data = $this->getLineService()->getScheduleList($params);
        return $data;
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
     * 获取可分配的站点列表
     * @param $id
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getBatchPageListByOrder($id, $params)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $info['execution_date'] = $params['execution_date'];
        return $this->getBatchService()->getPageListByOrder($info);
    }

    /**
     * 将订单分配至站点
     * @param $id
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function assignToBatch($id, $params)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (!empty($params['batch_no']) && ($info['batch_no'] == $params['batch_no'])) {
            return 'true';
        }
        $info['execution_date'] = $params['execution_date'];
        $line = $this->fillSender($info, BaseConstService::YES);
        /***********************************************1.修改*********************************************************/
        $rowCount = parent::updateById($id, $info);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        /********************************************2.从旧站点移除****************************************************/
        if (!empty($info['batch_no'])) {
            $this->getBatchService()->removeOrder($info);
            //重新统计站点金额
            $this->getBatchService()->reCountAmountByNo($info['batch_no']);
            //重新统计取件线路金额
            !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);
        }
        /*******************************************3.加入新站点*******************************************************/
        $batchNo = !empty($params['batch_no']) ? $params['batch_no'] : null;
        list($batch, $tour) = $this->getBatchService()->join($info, $line, $batchNo);
        /*********************************4.填充取件批次编号和取件线路编号*********************************************/
        $this->fillBatchTourInfo($info, $batch, $tour);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);

        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_JOIN_BATCH, $batch);
        event(new OrderExecutionDateUpdated($info['order_no'], $info['out_order_no'] ?? '', $params['execution_date'], $batch['batch_no'], ['tour_no' => $tour['tour_no'], 'line_id' => $tour['line_id'], 'line_name' => $tour['line_name']]));
        return 'true';
    }

    /**
     * 从站点中移除订单
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (empty($info['batch_no'])) {
            return;
        }
        //订单移除站点和取件线路信息
        $rowCount = parent::updateById($id, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::ORDER_STATUS_1, 'execution_date' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '', 'status' => BaseConstService::PACKAGE_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点和取件线路信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        $this->getBatchService()->removeOrder($info);
        //重新统计站点金额
        !empty($info['batch_no']) && $this->getBatchService()->reCountAmountByNo($info['batch_no']);
        //重新统计取件线路金额
        !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);

        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_REMOVE_BATCH, $info);
        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_REMOVE_TOUR, $info);
    }

    /**
     * 批量订单从站点移除
     * @param $idList
     * @throws BusinessLogicException
     */
    public function removeListFromBatch($idList)
    {
        $idList = explode_id_string($idList);
        $orderList = parent::getList(['id' => ['in', $idList]], ['*'], false)->toArray();
        if (empty($orderList)) {
            throw new BusinessLogicException('所有订单的当前状态不能操作，只允许待分配或已分配状态的订单操作');
        }
        $statusList = [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2];
        $orderList = Arr::where($orderList, function ($order) use ($statusList) {
            if (!in_array($order['status'], $statusList)) {
                throw new BusinessLogicException('订单[:order_no]的当前状态不能操作,只允许待分配或已分配状态的订单操作', 1000, ['order_no' => $order['order_no']]);
            }
            return !empty($order['batch_no']);
        });
        $orderNoList = array_column($orderList, 'order_no');
        $where = ['order_no' => ['in', $orderNoList]];
        //订单移除站点和取件线路信息
        $rowCount = parent::update($where, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update($where, ['tour_no' => '', 'batch_no' => '', 'status' => BaseConstService::PACKAGE_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点和取件线路信息
        $rowCount = $this->getMaterialService()->update($where, ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        foreach ($orderList as $order) {
            $this->getBatchService()->removeOrder($order);
            //重新统计站点金额
            !empty($order['batch_no']) && $this->getBatchService()->reCountAmountByNo($order['batch_no']);
            //重新统计取件线路金额
            !empty($order['tour_no']) && $this->getTourService()->reCountAmountByNo($order['tour_no']);
        }
        OrderTrailService::storeAllByOrderList($orderList, BaseConstService::ORDER_TRAIL_REMOVE_BATCH);
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
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3, BaseConstService::ORDER_STATUS_6, BaseConstService::ORDER_STATUS_7]);
        //若当前订单已取消取派了,在直接返回成功，不再删除
        if (in_array(intval($info['status']), [BaseConstService::ORDER_STATUS_6, BaseConstService::ORDER_STATUS_7])) {
            return 'true';
        }
        $data = ['status' => BaseConstService::ORDER_STATUS_7, 'execution_date' => null, 'batch_no' => '', 'tour_no' => ''];
        if (!empty($params['remark'])) {
            $data = array_merge($data, ['remark' => $params['remark']]);
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单删除失败，请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '', 'execution_date' => null, 'status' => BaseConstService::PACKAGE_STATUS_7]);
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

        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_DELETE);

        //以取消取派方式推送商城
        event(new OrderCancel($info['order_no'], $info['out_order_no']));

        return 'true';
    }

    /**
     * 批量删除
     * @param $params
     * @throws BusinessLogicException
     */
    public function destroyByList($params)
    {
        $log = null;
        $ids = explode_id_string($params['id_list']);
        $orderList = parent::getList(['id' => ['in', $ids]], ['*'], false);
        foreach ($ids as $v) {
            try {
                $this->destroy($v, null);
            } catch (BusinessLogicException $e) {
                $orderNo = $orderList->where('id', $v)->first()->order_no;
                $log .= '[' . $orderNo . ']' . $e->getMessage() . ';';
            }
        }
        if (!empty($log)) {
            throw new BusinessLogicException($log);
        }
    }

    /**
     * 订单恢复
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function recovery($id, $params)
    {
        $order = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::ORDER_STATUS_7);
        if (intval($order['source']) === BaseConstService::ORDER_SOURCE_3) {
            throw new BusinessLogicException('第三方订单不允许恢复');
        }
        /********************************************恢复之前验证包裹**************************************************/
        $packageList = $this->getPackageService()->getList(['order_no' => $order['order_no']], ['*'], false)->toArray();
        !empty($packageList) && $this->getPackageService()->check($packageList, $order['order_no']);
        /********************************************恢复之前验证材料**************************************************/
        $materialList = $this->getMaterialService()->getList(['order_no' => $order['order_no']], ['*'], false)->toArray();
        !empty($materialList) && $this->getMaterialService()->checkAllUnique($materialList);
        /**********************************************订单恢复********************************************************/
        $order['execution_date'] = $params['execution_date'];
        $order['status'] = BaseConstService::ORDER_STATUS_1;
        $line = $this->fillSender($order);
        $rowCount = parent::updateById($order['id'], $order);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单恢复失败');
        }
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($order, $line);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order, $batch, $tour);
        //重新统计站点金额
        $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
        //重新统计取件线路金额
        $this->getTourService()->reCountAmountByNo($tour['tour_no']);

        //订单轨迹-订单加入站点
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH, $batch);
        //订单轨迹-订单加入取件线路
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_TOUR, $tour);
    }


    /**
     * 彻底删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function actualDestroy($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, BaseConstService::ORDER_STATUS_7);
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败，请重新操作');
        }
        //删除包裹
        $rowCount = $this->getPackageService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败，请重新操作');
        }
        //删除材料
        $rowCount = $this->getMaterialService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败，请重新操作');
        }
    }

    /**
     * 批量订单分配至指定取件线路
     * @param $params
     * @throws BusinessLogicException
     * @throws \WebSocket\BadOpcodeException
     */
    public function assignListTour($params)
    {
        list($orderIdList, $tourNo) = [$params['id_list'], $params['tour_no']];
        /******************************************1.获取数据**********************************************************/
        //获取取件线路信息
        $tour = $this->getTourService()->getInfoLock(['tour_no' => $tourNo, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路当前状态不能操作');
        }
        $tour = $tour->toArray();
        //获取线路信息
        list($orderList, $lineId) = $this->getAddOrderList($orderIdList, $tour['execution_date']);
        //判断当前线路ID是否是取件线路ID
        if (intval($lineId) != intval($tour['line_id'])) {
            throw new BusinessLogicException('当前线路已更换，请刷新');
        }
        $orderList = Arr::where($orderList, function ($order) use ($tourNo) {
            return (($order['tour_no'] != $tourNo) && ($order['type'] == BaseConstService::ORDER_TYPE_1));
        });
        //获取线路信息
        $line = $this->getLineService()->getInfoLock(['id' => $lineId], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('线路不存在');
        }
        $line = $line->toArray();
        /*******************************************2.验证*************************************************************/
        $count = 0;
        if ($tour['status'] == BaseConstService::TOUR_STATUS_4) {
            $materialCount = $this->getMaterialService()->count(['order_no' => ['in', array_column($orderList, 'order_no')]]);
            if ($materialCount > 0) {
                throw new BusinessLogicException('当前取件线路正在派送中，取件订单加单不能包含材料');
            }
        }
        if ($tour['expect_pickup_quantity'] + $count > $line['pickup_max_count']) {
            throw new BusinessLogicException('取件数量超过线路取件订单最大值');
        }
        /*******************************************2.加单*************************************************************/
        data_set($orderList, '*.execution_date', $tour['execution_date']);
        foreach ($orderList as $order) {
            $this->removeFromBatch($order['id']);
            //重新统计站点金额
            !empty($order['batch_no']) && $this->getBatchService()->reCountAmountByNo($order['batch_no']);
            //重新统计取件线路金额
            !empty($order['tour_no']) && $this->getTourService()->reCountAmountByNo($order['tour_no']);
            //分配至新的取件线路
            list($batch, $tour) = $this->getBatchService()->join($order, $line, null, $tour, true);
            /**********************************填充取件批次编号和取件线路编号**********************************************/
            $this->fillBatchTourInfo($order, $batch, $tour);
            //重新统计站点金额
            $this->getBatchService()->reCountAmountByNo($batch['batch_no']);
            //重新统计取件线路金额
            $this->getTourService()->reCountAmountByNo($batch['tour_no']);

            OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH, $batch);
            OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_TOUR, $tour);
        }

        //加单推送
        dispatch(new AddOrderPush($orderList, $tour['driver_id']));
    }


    /**
     * 获取可加单的订单列表
     * @param $orderIdList
     * @param $executionDate
     * @return array
     * @throws BusinessLogicException
     */
    public function getAddOrderList($orderIdList, $executionDate)
    {
        $lineId = null;
        $orderList = parent::getList(['id' => ['in', explode(',', $orderIdList)], 'type' => BaseConstService::ORDER_TYPE_1], ['*'], false)->toArray();
        $statusList = [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2];
        foreach ($orderList as $order) {
            if (!in_array($order['status'], $statusList)) {
                throw new BusinessLogicException('订单[:order_no]的不是待分配或已分配状态，不能操作', 1000, ['order_no' => $order['order_no']]);
            }
            if ($order['type'] == BaseConstService::ORDER_TYPE_2) {
                throw new BusinessLogicException('派件订单不允许加单');
            }
            $dbLineId = $this->getLineService()->getLineIdByInfo($order, $executionDate);
            if (empty($dbLineId) || (!empty($lineId) && ($lineId != $dbLineId))) {
                return [$orderList, 0];
            }
            $lineId = $dbLineId;
        }
        return [$orderList, $lineId];
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
     * @param $ids
     * @return array
     * @throws BusinessLogicException
     */
    public function orderExport($ids)
    {
        $ids = explode(',', $ids);
        $orderList = parent::getList(['id' => ['in', $ids]], ['*'], false);
        if ($orderList->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($orderList->count() > 200) {
            throw new BusinessLogicException('数据量过大无法导出，订单数不得超过200');
        }
        $merchant = $this->getMerchantService()->getList(['id' => ['in', $orderList->pluck('merchant_id')->toArray()]]);
        if ($merchant->isEmpty()) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $this->getTourService()->getList(['tour_no' => ['in', $orderList->pluck('tour_no')->toArray()]]);
        $orderList = $orderList->toArray();
        foreach ($orderList as $k => $v) {
            $orderList[$k]['merchant_name'] = $v['merchant_id_name'];
            $orderList[$k]['line_name'] = $tour->where('tour_no', $v['tour_no'])->first()['line_name'] ?? '';
            $orderList[$k]['status'] = $v['status_name'];
        }
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

    /**
     * 无效化已完成订单
     * @param $id
     * @throws BusinessLogicException
     */
    public function neutralize($id)
    {
        $order = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($order)) {
            throw new BusinessLogicException('数据不存在');
        }
        if ($order['status'] !== BaseConstService::ORDER_STATUS_5) {
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
}
