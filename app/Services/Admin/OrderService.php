<?php

/**
 * 订单服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 16:39
 */

namespace App\Services\Admin;

use App\Events\Order\OrderCreated;
use App\Exceptions\BusinessLogicException;
use App\Http\Middleware\Validate;
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Http\Validate\Api\Admin\OrderValidate;
use App\Models\Order;
use App\Models\OrderImportLog;
use App\Models\ReceiverAddress;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\ConstTranslateTrait;
use App\Traits\ImportTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;



class OrderService extends BaseService
{
    use ImportTrait, LocationTrait;

    public $filterRules = [
        'type' => ['=', 'type'],
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'order_no,out_order_no' => ['like', 'keyword'],
        'exception_label' => ['=', 'exception_label'],
        'source' => ['=', 'source']
    ];

    public $orderBy = ['created_at' => 'desc'];

    public function __construct(Order $order)
    {
        $this->model = $order;
        $this->query = $this->model::query();
        $this->resource = OrderResource::class;
        $this->infoResource = OrderInfoResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
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
     * 来源 服务
     * @return mixed
     */
    public function getSourceSerice()
    {
        return self::getInstance(SourceService::class);
    }

    /**
     * 发件人地址 服务
     * @return mixed
     */
    public function getSenderAddressService()
    {
        return self::getInstance(SenderAddressService::class);
    }

    /**
     * 收人地址 服务
     * @return mixed
     */
    public function getReceiverAddressService()
    {
        return self::getInstance(ReceiverAddressService::class);
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
     * 取件列初始化
     * @return array
     */
    public function initPickupIndex()
    {
        $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_1]);
        $assignCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_2]);
        $waitOutCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_3]);
        $takingCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_4]);
        $signedCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_5]);
        $cancelCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::ORDER_STATUS_6]);
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        return ['no_take' => $noTakeCount, 'assign' => $assignCount, 'wait_out' => $waitOutCount, 'taking' => $takingCount, 'singed' => $signedCount, 'cancel_count' => $cancelCount, 'exception_count' => $exceptionCount];
    }

    /**
     * 派件列表初始化
     * @return array
     */
    public function initPieIndex()
    {
        $noTakeCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_1]);
        $assignCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_2]);
        $waitOutCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_3]);
        $takingCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_4]);
        $signedCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_5]);
        $cancelCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::ORDER_STATUS_6]);
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2, 'exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        return ['no_take' => $noTakeCount, 'assign' => $assignCount, 'wait_out' => $waitOutCount, 'taking' => $takingCount, 'singed' => $signedCount, 'cancel_count' => $cancelCount, 'exception_count' => $exceptionCount];
    }


    public function getPageList()
    {
        $list = parent::getPageList();
        foreach ($list as &$order) {
            $batchException = $this->getBatchExceptionService()->getInfo(['batch_no' => $order['batch_no']], ['id', 'batch_no', 'stage'], false, ['created_at' => 'desc']);
            $order['exception_stage_name'] = !empty($batchException) ? ConstTranslateTrait::$batchExceptionStageList[$batchException['stage']] : __('正常');
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
            throw new BusinessLogicException('订单不存在!');
        }
        $info['package_list'] = $this->getPackageService()->getList(['order_no' => $info['order_no']], ['*'], false);
        $info['material_list'] = $this->getMaterialService()->getList(['order_no' => $info['order_no']], ['*'], false);
        return $info;
    }

    public function initStore()
    {
        $data = [];
        $data['nature_list'] = array_values(collect(ConstTranslateTrait::$orderNatureList)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        $data['settlement_type_list'] = array_values(collect(ConstTranslateTrait::$orderSettlementTypeList)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        $data['type'] = array_values(collect(ConstTranslateTrait::$orderTypeList)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
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
    private function getInfoOfStatus($where, $isToArray = true, $status = BaseConstService::ORDER_STATUS_1, $isLock = true)
    {
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
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
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
        //自动记录
        $this->record($params);
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_CREATED);
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($params);
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order->getAttributes(), $batch, $tour, false);
        /**************************************新增订单货物明细********************************************************/
        $this->addAllItemList($params, $batch, $tour);
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
        $list=json_decode($params['list'],true);
        $successCount = 0;
        $failCount = 0;
        $validatorList = [];
        $log = [];
        $validate = new OrderValidate;
        $rules = $validate->rules;
        $message = $validate->message;
        $item_rules = $validate->item_rules;
        $itemCustomAttributes = $validate->itemCustomAttributes;
        for ($i = 0; $i < count($list); $i++) {
            //处理格式
            $list[$i]['execution_date'] = date('Y-m-d', ($list[$i]['execution_date'] - 25569) * 24 * 3600);
            $list[$i] = array_map('strval', $list[$i]);
            //获取经纬度
            $info = $this->getReceiverAddressService()->check($list[$i]);
            if (empty($info)) {
                $info = $this->getLocation($list[$i]['receiver_country'], $list[$i]['receiver_city'], $list[$i]['receiver_street'], $list[$i]['receiver_house_number'], $list[$i]['receiver_post_code']);
            }
            $list[$i]['lon'] = $info['lon'];
            $list[$i]['lat'] = $info['lat'];
            //订单新增验证
            $validator[$i] = \Illuminate\Support\Facades\Validator::make($list[$i], $rules, ['*.unique_ignore' => ':attribute已存在','settlement_amount.required_if' => '当结算方式为到付时,:attribute字段必填',
            ]);
            if ($validator[$i]->fails()) {
                $log[$i + 1] = array_values($validator[$i]->errors()->getMessages())[0];
                $failCount = $failCount + 1;
            }else{if (!empty($list[$i]['item_list'])) {
                $itemList = json_decode($list[$i]['item_list'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $log[$i + 1] = (new BusinessLogicException('明细数据格式不正确', 3001))->getMessage();
                } else {
                    foreach ($itemList as $item) {
                        $validatorList[$i] = \Illuminate\Support\Facades\Validator::make($item, $item_rules);
                    }
                    if ($validatorList[$i]->fails()) {
                        $log[$i + 1] = array_values($validatorList[$i]->errors()->getMessages())[0];
                        $failCount = $failCount + 1;
                    } else {
                        //订单新增事务
                        try {
                            DB::beginTransaction();
                            $this->store($list[$i]);
                            $log[$i + 1] = 'success';
                            $successCount = $successCount + 1;
                            DB::commit();
                        } catch (BusinessLogicException $e) {
                            DB::rollBack();
                            $log[$i + 1] = $e->getMessage();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $log[$i + 1] = $e->getMessage();
                        }
                    }
                }
            }}

        }
        $data['log'] = $log;
        $data['success'] = $successCount;
        $data['fail'] = $failCount;
        OrderImportLog::query()->where('id', $params['id'])->update([
            'success_order' => $data['success'],
            'fail_order' => $data['fail'],
            'log' => json_encode($data['log']),
            'status'=>2
        ]);
    }

    /**
     * 订单导入
     * @param $params
     * @throws BusinessLogicException
     */
    public function orderImport($params)
    {
        $this->orderImportValidate($params);
        $params['dir'] = 'order';
        $params['path'] = $this->getUploadService()->fileUpload($params)['path'];
        $params['path'] = str_replace('tms-api.test/storage/', 'public//', $params['path']);
        $heading = ['execution_date', 'out_order_no', 'express_first_no', 'express_second_no', 'source', 'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery', 'sender', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street', 'sender_address', 'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address', 'special_remark', 'remark', 'item_list'];
        $this->headingCheck($params['path'], $heading);//表头验证
        $row = $this->excelImport($params['path'])[0];
        $id =$this->orderImportLog($params);
        return ['row'=>$row,'id'=>$id];
    }

    public function orderImportLog($params){
        $orderImport =[
            'company_id'=>auth()->user()->company_id,
            'name'=>$params['name'],
            'url'=>$params['path'],
            'status'=>1,
            'success_order'=>0,//$info['success'],
            'fail_order'=>0,//$info['fail'],
            'log'=>''//json_encode($info['log']),
        ];
        return OrderImportLog::query()->create($orderImport)->id;
    }

    /**
     * 验证传入参数
     * @param $params
     * @throws BusinessLogicException
     */
    public function orderImportValidate($params){
        //验证$params
        $checkfile=\Illuminate\Support\Facades\Validator::make($params,
            ['file' => 'required|file|mimes:txt,xls,xlsx','name'=>'required|unique:order_import_log'],
            ['file.file' => '必须是文件', 'file.mimes' => ':attribute类型必须是excel,word,jpeg,bmp,png,pdf类型']);
        if($checkfile->fails()){
            $error = array_values($checkfile->errors()->getMessages())[0][0];
            throw new BusinessLogicException($error,301);
        }
    }



    /**
     * 自动记录
     * @param $params
     */
    public function record($params)
    {
        //记录来源
        if (empty($this->getSourceSerice()->getInfo(['source_name' => $params['source']], ['*'], false))) {
            $this->getSourceSerice()->create(['source_name' => $params['source']]);
        }
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
     * @param $id
     * @throws BusinessLogicException
     */
    private function check(&$params, $id = null)
    {
        if (empty($params['package_list']) || empty($params['material_list'])) {
            throw new BusinessLogicException('订单中必须存在一个包裹或一种材料');
        }
        $this->validateFields($params);
        //验证包裹列表
        if (!empty($params['package_list'])) {
            $packageList = json_decode($params['package_list'], true);
            $nameList = array_column($packageList, 'name');
            if (count(array_unique($nameList)) !== count($nameList)) {
                throw new BusinessLogicException('包裹名称有重复!不能添加订单');
            }
            $outOrderNoList = array_column($packageList, 'out_order_no');
            if (!empty($outOrderNoList) && (count(array_unique($outOrderNoList)) !== count($outOrderNoList))) {
                throw new BusinessLogicException('包裹外部标识有重复!不能添加订单');
            }
        }

        //验证材料列表
        if (!empty($params['material_list'])) {
            $materialList = json_decode($params['material_list'], true);
            $nameList = array_column($materialList, 'name');
            if (count(array_unique($nameList)) !== count($nameList)) {
                throw new BusinessLogicException('材料名称有重复!不能添加订单');
            }
            $codeList = array_column($materialList, 'code');
            if (count(array_unique($codeList)) !== count($codeList)) {
                throw new BusinessLogicException('材料代码有重复!不能添加订单');
            }
            $outOrderNoList = array_column($materialList, 'out_order_no');
            if (!empty($outOrderNoList) && (count(array_unique($outOrderNoList)) !== count($outOrderNoList))) {
                throw new BusinessLogicException('材料外部标识有重复!不能添加订单');
            }
//            $statusList = [BaseConstService::ORDER_STATUS_6, BaseConstService::ORDER_STATUS_7];
//            $material = DB::select("SELECT * FROM material INNER JOIN `order` ON (material.order_no = `order`.order_no) WHERE material.out_order_no IN $outOrderNoList AND `order`.status NOT IN $statusList LIMIT 1");
//            if (!empty($material)) {
//                throw new BusinessLogicException('当前外部标识已存在其他订单中');
//            }
        }
    }

    /**
     * 字段验证
     * @param $params
     * @throws BusinessLogicException
     */
    private function validateFields(&$params)
    {
        //验证包裹字段
        if (!empty($params['package_list'])) {
            $packageList = json_decode($params['package_list'], true);
            $rules = [
                'name' => 'required|string|max:50',
                'out_order_no' => 'nullable|string|max:50',
                'weight' => 'required|numeric',
                'quantity' => 'required|integer',
                'express_first_no' => 'required|string|max:50',
                'express_second_no' => 'nullable|string|max:50',
                'remark' => 'nullable|string|max:250',
            ];
            foreach ($packageList as $package) {
                $validator = Validator::make($package, $rules);
                if ($validator->fails()) {
                    $messageList = Arr::flatten($validator->errors()->getMessages());
                    throw new BusinessLogicException(implode(';', $messageList), 3001);
                }
            }
        }
        //验证材料字段
        if (!empty($params['material_list'])) {
            $materialList = json_decode($params['material_list'], true);
            $rules = [
                'name' => 'required|string|max:50',
                'code' => 'required|string|max:50',
                'out_order_no' => 'nullable|string|max:50',
                'expect_quantity' => 'required|integer',
                'remark' => 'nullable|string|max:250',
            ];
            foreach ($materialList as $material) {
                $validator = Validator::make($material, $rules);
                if ($validator->fails()) {
                    $messageList = Arr::flatten($validator->errors()->getMessages());
                    throw new BusinessLogicException(implode(';', $messageList), 3001);
                }
            }
        }
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
        //若存在包裹列表,则新增包裹列表
        if (!empty($params['package_list'])) {
            $packageList = collect(json_decode($params['package_list'], true))->map(function ($item, $key) use ($params, $batch, $tour) {
                $collectItem = collect($item)->only(['name', 'express_first_no', 'express_second_no', 'out_order_no', 'weight', 'quantity', 'remark']);
                return $collectItem->put('order_no', $params['order_no'])->put('batch_no', $batch['batch_no'])->put('tour_no', $tour['tour_no']);
            })->toArray();
            $rowCount = $this->getPackageService()->insertAll($packageList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单包裹新增失败!');
            }
        }
        //若材料存在,则新增材料列表
        if (!empty($params['material_list'])) {
            $materialList = collect(json_decode($params['material_list'], true))->map(function ($item, $key) use ($params, $batch, $tour) {
                $collectItem = collect($item)->only(['name', 'code', 'out_order_no', 'expect_quantity', 'remark']);
                return $collectItem->put('order_no', $params['order_no'])->put('batch_no', $batch['batch_no'])->put('tour_no', $tour['tour_no']);
            })->toArray();
            $rowCount = $this->getMaterialService()->insertAll($materialList);
            if ($rowCount === false) {
                throw new BusinessLogicException('订单材料新增失败!');
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
        $rowCount = parent::updateById($order['id'], [
            'execution_date' => $batch['execution_date'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'driver_phone' => $tour['driver_phone'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::ORDER_STATUS_1,
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败,请重新操作');
        }
        if ($isFillItem === false) return;
        //填充包裹
        $rowCount = $this->getPackageService()->update(['order_no' => $order['order_no']], ['batch_no' => $batch['batch_no'], 'tour_no' => $tour['tour_no']]);
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
        //验证
        $this->check($data, $id);
        //获取信息
        $dbInfo = $this->getInfoOfStatus(['id' => $id]);
        //修改
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
        $data = Arr::add($data, 'order_no', $dbInfo['order_no']);
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
            throw new BusinessLogicException('修改失败,请重新操作');
        }
        //删除材料列表
        $rowCount = $this->getMaterialService()->delete(['order_no' => $dbInfo['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
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
        $this->getBatchService()->removeOrder(array_merge($data, Arr::only($dbInfo, ['batch_no', 'tour_no'])));
        list($batch, $tour) = $this->getBatchService()->join($data);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($dbInfo, $batch, $tour, false);
        return [$batch, $tour];
    }

    public function getTourDate($id){
        $info = parent::getInfo(['id' => $id], ['*'], true);
        return $this->getLineRangeService()->query
            ->where('post_code_start','<=',$info['receiver_post_code'])
            ->where('post_code_end','>=',$info['receiver_post_code'])->distinct()->pluck('schedule');
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
     * @throws BusinessLogicException
     */
    public function assignToBatch($id, $params)
    {
        $info = $this->getInfoOfStatus(['id' => $id]);
        if (!empty($params['batch_no']) && ($info['batch_no'] == $params['batch_no'])) {
            throw new BusinessLogicException('当前订单已存在分配的站点中!');
        }
        $info['execution_date'] = $params['execution_date'];
        list($batch, $tour) = $this->getBatchService()->assignOrderToBatch($info, $params);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($info, $batch, $tour);
    }

    /**
     * 从站点中移除订单
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id]);
        if (empty($info['batch_no'])) {
            throw new BusinessLogicException('已从站点移除!');
        }
        //订单移除站点和取件线路信息
        $rowCount = parent::updateById($id, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'driver_phone' => '', 'car_id' => null, 'car_no' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //材料移除站点和取件线路信息
        $rowCount = $this->getMaterialService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        $this->getBatchService()->removeOrder($info);
    }


    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id]);
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_7, 'execution_date' => null, 'batch_no' => '', 'tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单删除失败,请重新操作');
        }
        //包裹移除站点和取件线路信息
        $rowCount = $this->getPackageService()->update(['order_no' => $info['order_no']], ['tour_no' => '', 'batch_no' => '']);
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
    }


    /**
     * 订单恢复
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function recovery($id, $params)
    {
        $order = $this->getInfoOfStatus(['id' => $id], false, BaseConstService::ORDER_STATUS_7);
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_1, 'execution_date' => $params['execution_date']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单恢复失败');
        }
        $order['execution_date'] = $params['execution_date'];
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_CREATED);
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($order);
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($order, $batch, $tour);
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
            throw new BusinessLogicException('彻底删除失败,请重新操作');
        }
        //删除包裹
        $rowCount = $this->getPackageService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败,请重新操作');
        }
        //删除材料
        $rowCount = $this->getMaterialService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败,请重新操作');
        }
    }
}
