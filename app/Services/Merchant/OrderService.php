<?php

/**
 * 订单服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:39
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Http\Validate\Api\Merchant\OrderImportValidate;
use App\Http\Validate\BaseValidate;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Services\Admin\OrderImportService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\LineService;
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
        'source'=>['=','source'],
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
     * 仓库 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 线路 服务
     * @return mixed
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
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
     * 查询初始化
     * @return array
     */
    public function initIndex(){
        $data = [];
        $data['source_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderSourceList);
        $data['status_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderStatusList);
        return $data;
    }

    /**
     * 取件列初始化
     * @return array
     */
    public function initPickupIndex()
    {
        $allCount = $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1]);
        $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_1]);
        $assignCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_2]);
        $waitOutCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_3]);
        $takingCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_4]);
        $signedCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_5]);
        $cancelCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_6]);
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        $delCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_7]);
        return [
            'all_count' => $allCount,
            'no_take' => $noTakeCount,
            'assign' => $assignCount,
            'wait_out' => $waitOutCount,
            'taking' => $takingCount,
            'singed' => $signedCount,
            'cancel_count' => $cancelCount,
            'exception_count' => $exceptionCount,
            'delete_count' => $delCount
        ];
    }

    /**
     * 派件列表初始化
     * @return array
     */
    public function initPieIndex()
    {
        $allCount = $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2]);
        $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_1]);
        $assignCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_2]);
        $waitOutCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_3]);
        $takingCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_4]);
        $signedCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_5]);
        $cancelCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_6]);
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        $delCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_7]);
        return [
            'all_count' => $allCount,
            'no_take' => $noTakeCount,
            'assign' => $assignCount,
            'wait_out' => $waitOutCount,
            'taking' => $takingCount,
            'singed' => $signedCount,
            'cancel_count' => $cancelCount,
            'exception_count' => $exceptionCount,
            'delete_count' => $delCount
        ];
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

    public function initStore()
    {
        $data = [];
        $data['nature_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderNatureList);
        $data['settlement_type_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderSettlementTypeList);
        $data['type'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$orderTypeList);
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
     * @param bool $import
     * @return array
     * @throws BusinessLogicException
     */
    public function store($params,$import=false)
    {
            $this->check($params);
        //设置订单来源
        data_set($params, 'source', (auth()->user()->getAttribute('is_api') == true) ? BaseConstService::ORDER_SOURCE_3 : BaseConstService::ORDER_SOURCE_1);
        if($import===true){
            data_set($params, 'source', BaseConstService::ORDER_SOURCE_2);
        }
        //填充商户ID
        $params['merchant_id'] = auth()->id();
        //用仓库填充发件人
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::ORDER_OR_BATCH_1);
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        $params = array_merge($params, [
            'sender' => $warehouse['contacter'],
            'sender_phone' => $warehouse['phone'],
            'sender_country' => $warehouse['country'],
            'sender_post_code' => $warehouse['post_code'],
            'sender_house_number' => $warehouse['house_number'],
            'sender_city' => $warehouse['city'],
            'sender_street' => $warehouse['street'],
            'sender_address' => $warehouse['address']
        ]);

        /*************************************************订单新增************************************************/
        //生成单号
        $params['order_no'] = $this->getOrderNoRuleService()->createOrderNo();
        if (empty($params['receiver_address'])) {
            $params['receiver_address'] = '';
        }
        $order = parent::create($params);
        if ($order === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        $order = $order->getAttributes();
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($params);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order, $batch, $tour, false);
        /**************************************新增订单货物明细********************************************************/
        $this->addAllItemList($params, $batch, $tour);
        //自动记录
        $this->record($params);
        //订单轨迹-订单创建
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_CREATED);
        //订单轨迹-订单加入站点
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
        //订单轨迹-订单加入取件线路
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_TOUR);
        return [
            'order_no' => $params['order_no'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'line_name' => $tour['line_name']
        ];
    }

    /**
     * 订单批量新增
     * @param $list
     * @param $name
     * @return mixed
     * @throws BusinessLogicException
     */
    public function createByList($params)
    {
        $list = json_decode($params['list'], true);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i] = $this->form($list[$i]);
            $list[$i]['receiver_city']='';
            $list[$i]['receiver_street']='';
            try {
                $this->store($list[$i],true);
            } catch (BusinessLogicException $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
            } catch (\Exception $e) {
                throw new BusinessLogicException(__('行') . ($i + 1) . ':' . $e->getMessage());
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
    public function orderImport($params)
    {
        $this->orderImportValidate($params);
        $params['dir'] = 'order';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace(env('APP_URL') . '/storage/', 'public//', $params['path']);
        $headings=array_values(__('excel.order'));
        $row = collect($this->orderExcelImport($params['path'])[0])->whereNotNull('0')->toArray();
        if ($row[0] !== $headings) {
            throw new BusinessLogicException('表格格式不正确，请使用正确的模板导入');
        }
        $headings = OrderImportService::$headings;
        $data = [];
        for ($i = 1; $i < count($row); $i++) {
            $data[$i - 1] = collect($headings)->combine($row[$i])->toArray();
        }
        if (count($data) > 100) {
            throw new BusinessLogicException('导入订单数量不得超过100个');
        }
        $id = $this->orderImportLog($params);
        //数据处理
        $typeList = ['取件' => 1, '派件' => 2, 'Pickup' => 1, 'Delivery' => 2];
        $settlementList = ['寄付' => 1, '到付' => 1, 'Send' => 1, 'Pay on site' => 2];
        $deliveryList = ['是' => 1, '否' => 2, 'Yes' => 1, 'No' => 2];
        $itemList = ['包裹' => 1, '材料' => 2, 'Package' => 1, 'Material' => 2];
        $countryNameList = array_unique(collect($data)->pluck('receiver_country_name')->toArray());
        $countryShortList = CountryTrait::getShortListByName($countryNameList);
        for ($i = 0; $i < count($data); $i++) {
            //处理格式
            $data[$i]['execution_date'] = date('Y-m-d', ($data[$i]['execution_date'] - 25569) * 24 * 3600);
            $data[$i] = array_map('strval', $data[$i]);
            $data[$i]['receiver_country'] = $countryShortList[$data[$i]['receiver_country_name']];
            $data[$i]['type'] = $typeList[$data[$i]['type_name']];
            $data[$i]['settlement_type'] = $settlementList[$data[$i]['settlement_type_name']];
            $data[$i]['delivery'] = $deliveryList[$data[$i]['delivery_name']] ?? 1;
            for ($j = 0; $j < 5; $j++) {
                $data[$i]['item_type_' . ($j + 1)] = $itemList[$data[$i]['item_type_name_' . ($j + 1)]] ?? 0;
            }
        }
        return $data;
    }

    /**
     * 订单格式转换
     * @param $data
     * @return mixed
     */
    public function form($data){
        $data['package_list']=[];
        $data['material_list']=[];
        for($j=0;$j<5;$j++){
            if($data['item_type_'.($j+1)] === 1 ||$data['item_type_'.($j+1)] === '1'){
                $data['package_list'][$j]['name']=$data['item_name_'.($j+1)];
                $data['package_list'][$j]['express_first_no']=$data['item_number_'.($j+1)];
                $data['package_list'][$j]['weight']=$data['item_weight_'.($j+1)]??1;
                $data['package_list'][$j]['quantity']=$data['item_count_'.($j+1)]??1;
                $data['package_list'][$j]['express_second_no']='';
                $data['package_list'][$j]['out_order_no']='';
                $data['package_list']=array_values($data['package_list']);
            }elseif ($data['item_type_'.($j+1)] === 2 || $data['item_type_'.($j+1)] === '2'){
                $data['material_list'][$j]['name']=$data['item_name_'.($j+1)];
                $data['material_list'][$j]['code']=$data['item_number_'.($j+1)];
                $data['material_list'][$j]['remark']='';
                $data['material_list'][$j]['quantity']=$data['item_count_'.($j+1)]??1;
                $data['material_list'][$j]['out_order_no']='';
                $data['material_list']=array_values($data['material_list']);
            }
        }
        $data = Arr::only($data, [
            'type',
            'receiver',
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
        $info = $this->getSenderAddressService()->check($params);
        if (empty($info)) {
            $this->getSenderAddressService()->create($params);
        }
        //记录收件人地址
        $info = $this->getReceiverAddressService()->check($params);
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
        //获取经纬度
        $fields = ['receiver_house_number', 'receiver_city', 'receiver_street'];
        $params = array_merge(array_fill_keys($fields, ''), $params);
        if (empty($params['lon']) || empty($params['lat'])) {
            $info = LocationTrait::getLocation($params['receiver_country'], $params['receiver_city'], $params['receiver_street'], $params['receiver_house_number'], $params['receiver_post_code']);
            $params['lon'] = $info['lon'];
            $params['lat'] = $info['lat'];
        }
        if (empty($params['package_list']) && empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        //验证包裹列表
        if (!empty($params['package_list'])) {
            $packageList = $params['package_list'];
            $expressNoList = array_filter(array_merge(array_column($packageList, 'express_first_no'), array_column($packageList, 'express_second_no')));
            if (count(array_unique($expressNoList)) !== count($expressNoList)) {
                throw new BusinessLogicException('快递单号有重复！不能添加订单');
            }
            //验证外部标识/快递单号1/快递单号2
            $this->getPackageService()->checkAllUnique($packageList, $orderNo);
        }
        //验证材料列表
        if (!empty($params['material_list'])) {
            $materialList = $params['material_list'];
            $codeList = array_column($materialList, 'code');
            if (count(array_unique($codeList)) !== count($codeList)) {
                throw new BusinessLogicException('材料代码有重复！不能添加订单');
            }
        }
        //检查仓库
        $line = $this->getLineService()->getInfoByRule($params, BaseConstService::ORDER_OR_BATCH_1);
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('仓库不存在');
        }
    }

    /**
     * 批量导入验证
     * @param $params
     * @return mixed
     */
    public function importCheckByList($params){
        $list = json_decode($params['list'], true);
        for($i=0,$j=count($list);$i<$j;$i++){
            $list[$i]=$this->importCheck($list[$i]);
        }
        return $list;
    }

    /**
     * 单条导入验证
     * @param $data
     * @return array
    */
    public function importCheck($data){
        $list=[];
        $validate=new OrderImportValidate;
        $validator = Validator::make($data, $validate->rules, array_merge(BaseValidate::$baseMessage, $validate->message));
        if ($validator->fails()) {
            $key = $validator->errors()->keys();
            foreach ($key as $v) {
                $list[$v] = $validator->errors()->first($v);
            }
        }
        //如果没传经纬度，就去地址库拉经纬度
        if(empty($data['lon']) || empty($data['lat'])){
            $address=$this->getReceiverAddressService()->check($data);
            $list['lon']=$address['lon']??null;
            $list['lat']=$address['lat']??null;
        }
        //如果地址库没有，就通过第三方API获取经纬度
        $fields = ['receiver_city', 'receiver_street'];
        $data = array_merge(array_fill_keys($fields, ''), $data);
        if (empty($data['lon']) || empty($data['lat'])) {
            try{
                $info = LocationTrait::getLocation($data['receiver_country'], $data['receiver_city'], $data['receiver_street'], $data['receiver_house_number'], $data['receiver_post_code']);
            }catch (BusinessLogicException $e){
                $list['log']=__($e->getMessage());
            } catch (\Exception $e) {
            }
            $data['lon'] = $info['lon']??null;
            $data['lat'] = $info['lat']??null;
        }
        $package=[];
        $material=[];
        for($j=0;$j<5;$j++){
            if($data['item_number_'.($j+1)]){
                if($data['item_type_'.($j+1)] === 1){
                    $package[$j]=$data['item_number_'.($j+1)];
                    $result[$j] = DB::table('package')->where('express_first_no',$data['item_number_'.($j+1)])->whereNotIn('status', [BaseConstService::PACKAGE_STATUS_6, BaseConstService::PACKAGE_STATUS_7])->first();
                    if (!empty($result[$j])) {
                        $list['item_number_' . ($j + 1)] = __('物品') . ($j+1) . __('编号有重复');
                    }
                }elseif ($data['item_type_'.($j+1)] === 2){
                    $material[$j]=$data['item_number_'.($j+1)];
                    $result[$j] = DB::table('material')->where('code',$data['item_number_'.($j+1)])->first();
                    if (!empty($result[$j])) {
                        $list['item_number_' . ($j + 1)] = __('物品') . ($j+1) . __('编号有重复');
                    }
                }
                if(count(array_unique($package)) !== count($package) || count(array_unique($material)) !== count($material) ){
                    $list['item_number_'.($j+1)]=__('编号有重复');
                }
            }
        }
        //检查仓库
        try{
            $line = $this->getLineService()->getInfoByRule($data, BaseConstService::ORDER_OR_BATCH_1);
            $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        }catch (BusinessLogicException $e){
            $list['log']=__($e->getMessage());
        }catch (\Exception $e){
            $list['log']=__('当前订单没有合适的线路，请先联系管理员');
        }
        $list['lon']=$data['lon']??'';
        $list['lat']=$data['lat']??'';
        if(count($list) >2){
            $list['status']='fail';
        }else{
            $list['status']='success';
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
        //若存在包裹列表,则新增包裹列表
        if (!empty($params['package_list'])) {
            $packageList = collect($params['package_list'])->map(function ($item, $key) use ($params, $batch, $tour) {
                $collectItem = collect($item)->only(['name', 'express_first_no', 'express_second_no', 'out_order_no', 'weight', 'expect_quantity', 'remark']);
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
     * @param $id
     * @param $batch
     * @param $tour
     * @param bool 是否需要填充包裹和材料
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
        //验证
        $this->check($data, $dbInfo['order_no']);
        //修改
        $rowCount = parent::updateById($dbInfo['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
        $data = Arr::add($data, 'order_no', $dbInfo['order_no']);
        $data = Arr::add($data, 'status', $dbInfo['status']);
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $isChangeBatch = $this->checkIsChangeBatch($dbInfo, $data);
        if ($isChangeBatch === true) {
            list($batch, $tour) = $this->changeBatch($dbInfo, $data);
        } else {
            $this->getBatchService()->updateAboutOrderByOrder($dbInfo, $data);
            $batch = ['batch_no' => $dbInfo['batch_no']];
            $tour = ['tour_no' => $dbInfo['tour_no']];
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
    }


    /**
     * 判断是否需要更换站点
     * @param $dbOrder
     * @param $order
     * @return bool
     */
    private function checkIsChangeBatch($dbOrder, $order)
    {
        $fields = ['execution_date', 'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'];
        $newDbOrder = Arr::only($dbOrder, $fields);
        $newOrder = Arr::only($order, $fields);
        return empty(array_diff($newDbOrder, $newOrder)) ? false : true;
    }


    /**
     * 订单更换站点
     * @param $dbInfo
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    private function changeBatch($dbInfo, $data)
    {
        //站点移除订单,添加新的订单
        if (!empty($dbInfo['batch_no'])) {
            $this->getBatchService()->removeOrder($dbInfo);
        }
        list($batch, $tour) = $this->getBatchService()->join($data);
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
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if(empty($info)){
            throw new BusinessLogicException('数据不存在');
        }
        $data=$this->getSchedule($info);
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
        $data = $this->getSchedule($params);
        return $data;
    }

    /**
     * 获取可选日期
     * @param $info
     * @return array
     * @throws BusinessLogicException
     */
    public function getSchedule($info)
    {
        $data = [];
        //获取邮编数字部分
        $postCode = explode_post_code($info['receiver_post_code']);
        //获取线路范围
        $lineRange = $this->getLineRangeService()->query->where('post_code_start', '<=', $postCode)
            ->where('post_code_end', '>=', $postCode)
            ->where('country', $info['receiver_country'])
            ->get();
        //按邮编范围循环
        if (!empty($lineRange)) {
            for ($i = 0, $j = count($lineRange); $i < $j; $i++) {
                //获取线路信息
                $line[$i] = $this->getLineService()->getInfo(['id' => $lineRange[$i]['line_id']], ['*'], false)->toArray();
                if (!empty($line[$i])) {
                    //获得当前邮编范围的首天
                    if ($lineRange[$i]['schedule'] ===0){
                        $lineRange[$i]['schedule']=7;
                    }
                    if (Carbon::today()->dayOfWeek < $lineRange[$i]['schedule']) {
                        $date = Carbon::today()->startOfWeek()->addDays($lineRange[$i]['schedule']-1)->format('Y-m-d');
                    } else {
                        $date = Carbon::today()->addWeek()->startOfWeek()->addDays($lineRange[$i]['schedule']-1)->format('Y-m-d');
                    }
                    //如果线路不自增，验证最大订单量
                    if ($line[$i]['is_increment'] == BaseConstService::IS_INCREMENT_2) {
                        for ($k = 0, $l = $line[$i]['appointment_days']; $k < $l; $k = $k + 7) {
                            $params['execution_date'] = Carbon::create($date)->addDays($k)->format('Y-m-d');
                            if ($info['type'] == 1) {
                                $orderCount = $this->getTourService()->sumOrderCount($params, $line[$i], 1);
                                if (1 + $orderCount['pickup_count'] <= $line[$i]['pickup_max_count']) {
                                    if ($params['execution_date'] === Carbon::today()->format('Y-m-d')) {
                                        if (time() > strtotime($params['execution_date'] . ' ' . $line[$i]['order_deadline'])) {
                                            $data[] = $params['execution_date'];
                                        }
                                    } else {
                                        $data[] = $params['execution_date'];
                                    }
                                }
                            } else {
                                $orderCount = $this->getTourService()->sumOrderCount($params, $line[$i], 2);
                                if (1 + $orderCount['pie_count'] <= $line[$i]['pie_max_count']) {
                                    if ($params['execution_date'] === Carbon::today()->format('Y-m-d')) {
                                        if (time() > strtotime($params['execution_date'] . ' ' . $line[$i]['order_deadline'])) {
                                            $data[] = $params['execution_date'];
                                        }
                                    } else {
                                        $data[] = $params['execution_date'];
                                    }
                                }
                            }
                        }
                    } elseif ($line[$i]['is_increment'] == BaseConstService::IS_INCREMENT_1) {
                        for ($k = 0, $l = $line[$i]['appointment_days']; $k < $l; $k = $k + 7) {
                            $params['execution_date'] = Carbon::create($date)->addDays($k)->format('Y-m-d');
                            if ($params['execution_date'] === Carbon::today()->format('Y-m-d')) {
                                if (time() > strtotime($params['execution_date'] . ' ' . $line[$i]['order_deadline'])) {
                                    $data[] = $params['execution_date'];
                                }
                            } else {
                                $data[] = $params['execution_date'];
                            }
                        }
                    }
                }
            }
        }
        asort($data);
        $data=array_values($data);
        return $data;
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
        if (!empty($params['batch_no']) && ($info['batch_no'] == $params['batch_no'])) {
            throw new BusinessLogicException('当前订单已存在分配的站点中！');
        }
        $info['execution_date'] = $params['execution_date'];
        list($batch, $tour) = $this->getBatchService()->assignOrderToBatch($info, $params);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($info, $batch, $tour);
        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
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
        $rowCount = parent::updateById($info['id'], ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::ORDER_STATUS_1,'execution_date'=>null]);
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
        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_REMOVE_BATCH);
    }


    /**
     * 删除
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function destroy($id, $params)
    {
        $info = $this->getInfoByIdOfStatus($id, true, [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2]);
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
        OrderTrailService::OrderStatusChangeCreateTrail($info, BaseConstService::ORDER_TRAIL_DELETE);
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
        /********************************************恢复之前验证包裹**************************************************/
        $packageList = $this->getPackageService()->getList(['order_no' => $order['order_no']], ['*'], false)->toArray();
        if (!empty($packageList)) {
            $this->getPackageService()->checkAllUnique($packageList, $order['order_no']);
        }
        /********************************************恢复之前验证材料**************************************************/
        $materialList = $this->getMaterialService()->getList(['order_no' => $order['order_no']], ['*'], false)->toArray();
        if (!empty($materialList)) {
            $outOrderNoList = array_column($materialList, 'out_order_no');
            if (!empty($outOrderNoList)) {
                $this->getMaterialService()->checkAllUniqueByOutOrderNoList($outOrderNoList, $order['order_no']);
            }
        }
        /**********************************************订单恢复********************************************************/
        $rowCount = parent::updateById($order['id'], ['status' => BaseConstService::ORDER_STATUS_1, 'execution_date' => $params['execution_date']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单恢复失败');
        }
        $order['execution_date'] = $params['execution_date'];
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($order);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order, $batch, $tour);
        //订单轨迹-订单加入站点
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
        //订单轨迹-订单加入取件线路
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
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
}
