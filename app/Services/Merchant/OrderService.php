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
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Http\Validate\Api\Merchant\OrderImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Services\Merchant\RouteTrackingService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderService extends BaseService
{
    use ImportTrait, LocationTrait, CountryTrait;

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
     * 线路区域 服务
     * @return LineAreaService
     */
    public function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
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
     * 线路追踪 服务
     * @return RouteTrackingService
     */
    public function getRouteTrackingService()
    {
        return self::getInstance(RouteTrackingService::class);
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

    public function getPageList()
    {
        $list = parent::getPageList();
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
        $this->query->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_6, BaseConstService::PACKAGE_STATUS_7]);
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
        $data['type'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderTypeList);
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
    private function getInfoByIdOfStatus($id, $isToArray = true, $status = BaseConstService::ORDER_STATUS_1, $isLock = true)
    {
        $where = [$this->getIdKeyName($id) => $id];
        $info = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!in_array(intval($info['status']), Arr::wrap($status))) {
            throw new BusinessLogicException('当前订单状态不能操作');
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
        //填充发件人信息
        $line = $this->fillSender($params);
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
        $typeList = array_flip(ConstTranslateTrait::$orderTypeList);
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
        //若是新增,则填充商户ID及国家
        if (empty($orderNo)) {
            $params['merchant_id'] = auth()->user()->id;
            //若邮编是纯数字，则认为是比利时邮编
            $params['receiver_country'] = is_numeric(trim($params['receiver_post_code'])) ? BaseConstService::POSTCODE_COUNTRY : CompanyTrait::getCountry();
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
        $params['receiver_country'] = is_numeric(trim($params['receiver_post_code'])) ? BaseConstService::POSTCODE_COUNTRY : CompanyTrait::getCountry();
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        !empty($params['package_list']) && $this->getPackageService()->check($params['package_list'], $orderNo);
        //验证材料列表
        !empty($params['material_list']) && $this->getMaterialService()->checkAllUnique($params['material_list']);
        //填充地址
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($params['receiver_address'])) {
            $fields = ['receiver_country', 'receiver_city', 'receiver_street', 'receiver_house_number', 'receiver_post_code'];
            $params['receiver_address'] = implode(' ', array_filter(array_only_fields_sort($params, $fields)));
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
     * @return array
     * @throws BusinessLogicException
     */
    private function fillSender(&$params)
    {
        //获取线路
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::ORDER_OR_BATCH_1);
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
            'sender_address' => $warehouse['address']
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
                    $result[$j] = DB::table('package')->where('express_first_no', $data['item_number_' . ($j + 1)])->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_6, BaseConstService::PACKAGE_STATUS_7])->first();
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
            $line = $this->getLineService()->getInfoByRule($data, BaseConstService::ORDER_OR_BATCH_1);
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
                return $collectItem->put('order_no', $params['order_no'])->put('batch_no', $batch['batch_no'])->put('tour_no', $tour['tour_no']);
            })->toArray();
            data_set($packageList, '*.status', $status);
            data_set($packageList, '*.type', $params['type']);
            $rowCount = $this->getPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单包裹新增失败！');
            }
        }
        //若材料存在,则新增材料列表
        if (!empty($params['material_list'])) {
            $materialList = collect($params['material_list'])->map(function ($item, $key) use ($params, $batch, $tour) {
                $collectItem = collect($item)->only(['name', 'code', 'out_order_no', 'expect_quantity', 'remark']);
                return $collectItem->put('order_no', $params['order_no'])->put('batch_no', $batch['batch_no'])->put('tour_no', $tour['tour_no']);
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
        $rowCount = $this->getPackageService()->update(['order_no' => $order['order_no']], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no'], 'status' => $status]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        //填充材料
        $rowCount = $this->getMaterialService()->update(['order_no' => $order['order_no']], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no']]);
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
        $dbInfo = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (intval($dbInfo['source']) === BaseConstService::ORDER_SOURCE_3) {
            throw new BusinessLogicException('第三方订单不能修改');
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
        //($isChangeBatch === true) && event(new OrderExecutionDateUpdated($dbInfo['order_no'], $dbInfo['out_order_no'], $data['execution_date'], $batch['batch_no'], ['tour_no' => $tour['tour_no'], 'line_id' => $tour['line_id'], 'line_name' => $tour['line_name']]));
    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePhoneDateByApi($id, $data)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (!empty($data['receiver_phone']) && empty($data['execution_date'])) {
            $data = Arr::only($data, ['receiver_phone']);
        } elseif (empty($data['receiver_phone']) && !empty($data['execution_date'])) {
            $data = Arr::only($data, ['execution_date']);
        } elseif (empty($data['receiver_phone']) && empty($data['execution_date'])) {
            throw new BusinessLogicException('电话或取派日期必填其一');
        }
        //分类
        if ($info['status'] < BaseConstService::ORDER_STATUS_3) {
            return $this->updateDatePhone($info, $data);
        } elseif (in_array($info['status'], [BaseConstService::ORDER_STATUS_3, BaseConstService::ORDER_STATUS_4]) && empty($data['execution_date'])) {
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
        unset($newData['order_no'], $newData['tour_no'], $newData['batch_no'],$data['order_no'], $data['tour_no'], $data['batch_no']);
        /******************************更换站点***************************************/
        $line = $this->fillSender($newData);
        list($batch, $tour) = $this->changeBatch($dbInfo, $newData, $line);

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
        $this->addAllItemList($newData, $batch, $tour);
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
        return $this->getLineService()->getScheduleList($params);
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
        return $this->getLineService()->getScheduleList($params);
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
     * 将订单分配至站点(重新预约)
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignToBatch($id, $params)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        $dbExecutionDate = $info['execution_date'];
        if (!empty($params['batch_no']) && ($info['batch_no'] == $params['batch_no'])) {
            throw new BusinessLogicException('当前订单已存在分配的站点中！');
        }
        $info['execution_date'] = $params['execution_date'];
        $line = $this->fillSender($info);
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
    }

    /**
     * 从站点中移除订单
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
        if (empty($info['batch_no'])) {
            throw new BusinessLogicException('已从站点移除!');
        }
        //订单移除站点和取件线路信息
        $rowCount = parent::updateById($info['id'], ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::ORDER_STATUS_1, 'execution_date' => null]);
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
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3, BaseConstService::ORDER_STATUS_6]);
        //若当前订单已取消取派了,在直接返回成功，不再删除
        if (intval($info['status']) == BaseConstService::ORDER_STATUS_6) {
            return 'true';
        }
        $rowCount = parent::updateById($info['id'], ['status' => BaseConstService::ORDER_STATUS_7, 'remark' => $params['remark'] ?? '', 'execution_date' => null, 'batch_no' => '', 'tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单删除失败，请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '', 'status' => BaseConstService::PACKAGE_STATUS_7]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点和取件线路信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '']);
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
        if ((!empty($params['no_push']) && $params['no_push'] == 0) || empty($params['no_push'])) {
            //以取消取派方式推送商城
            event(new OrderCancel($info['order_no'], $info['out_order_no']));
        }

        return 'true';
    }


    /**
     * 订单恢复
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function recovery($id, $params)
    {
        $orderCollection = $this->getInfoByIdOfStatus($id, false, BaseConstService::ORDER_STATUS_7);
        $order = $orderCollection->toArray();
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
        $info = $this->getInfoByIdOfStatus($id, true, BaseConstService::ORDER_STATUS_7);
        $rowCount = parent::delete(['id' => $info['id']]);
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
     * 修改订单的出库状态
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateOutStatus($id, $params)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_3]);
        $rowCount = parent::updateById($info['id'], ['out_status' => $params['out_status']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
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
        //处理预计耗时
        if (!empty($batch->expect_arrive_time)) {
            $expectTime = strtotime($batch->expect_arrive_time) - time();
        } else {
            $expectTime = 0;
        }
        $routeTracking = $this->getRouteTrackingService()->getInfo(['tour_no' => $batch->tour_no], ['lon', 'lat'], false) ?? '';
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

            'out_order_no' => $info['out_order_no'] ?? ''
        ];
    }

}
