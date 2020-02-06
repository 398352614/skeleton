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
use App\Http\Resources\OrderInfoResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\ReceiverAddress;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\ConstTranslateTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;

class OrderService extends BaseService
{

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
     * 订单明细 服务
     * @return OrderItemService
     */
    private function getOrderItemService()
    {
        return self::getInstance(OrderItemService::class);
    }

    /**
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
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
        $info['item_list'] = $this->getOrderItemService()->getList(['order_no' => $info['order_no']], ['*'], false);
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
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    private function getInfoOfStatus($id, $isToArray = true, $status = BaseConstService::ORDER_STATUS_1)
    {
        $info = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (intval($info['status']) !== $status) {
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
        $this->fillBatchTourInfo($order->getOriginal('id'), $batch, $tour);
        /**************************************新增订单货物明细********************************************************/
        $this->addAllItemList($params);
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
        $where = empty($id) ? [] : ['id' => $id];
        if ($params['express_second_no']) {
            //验证快递单号是否重复,由于外面已经对应验证过了,所以这里只需要验证快递单号1是否和快递单号2重复,快递单号1和快递单号2重复]
            $where['express_first_no'] = $params['express_second_no'];
            $info = parent::getInfo($where, ['*'], false);
            if (!empty($info)) {
                throw new BusinessLogicException('快递单号2已存在');
            }
            unset($where['express_first_no']);
        }
        $where['express_second_no'] = $params['express_first_no'];
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('快递单号1已存在');
        }
        //验证货物名称是否重复
        if (!empty($params['item_list'])) {
            $nameList = array_column(json_decode($params['item_list'], true), 'name');
            if (count(array_unique($nameList)) !== count($nameList)) {
                throw new BusinessLogicException('货物名称有重复!不能添加订单');
            }
        }
    }

    /**
     * 添加货物列表
     * @param $params
     * @throws BusinessLogicException
     */
    private function addAllItemList($params)
    {
        if (empty($params['item_list'])) return;
        $itemList = collect(json_decode($params['item_list'], true))->map(function ($item, $key) use ($params) {
            $collectItem = collect($item)->only(['name', 'quantity', 'weight', 'volume', 'price']);
            return $collectItem->put('order_no', $params['order_no']);
        })->toArray();
        $rowCount = $this->getOrderItemService()->insertAll($itemList);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单货物明细新增失败!');
        }
    }

    /**
     * 填充站点信息和取件线路信息
     * @param $id
     * @param $batch
     * @param $tour
     * @throws BusinessLogicException
     */
    private function fillBatchTourInfo($id, $batch, $tour)
    {
        $rowCount = parent::updateById($id, [
            'execution_date' => $batch['execution_date'],
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::ORDER_STATUS_1,
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
        //验证
        $this->check($data, $id);
        //获取信息
        $dbInfo = $this->getInfoOfStatus($id);
        //修改
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
        $data = Arr::add($data, 'order_no', $dbInfo['order_no']);
        /*********************************************更换货物明细列表***************************************************/
        //删除货物明细列表
        $rowCount = $this->getOrderItemService()->delete(['order_no' => $dbInfo['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败,请重新操作');
        }
        //新增获取明细列表
        $this->addAllItemList($data);
        /******************************判断是否需要更换站点(取派日期+收货方地址 验证)***************************************/
        $isChangeBatch = $this->checkIsChangeBatch($dbInfo, $data);
        if ($isChangeBatch === true) {
            $this->changeBatch($dbInfo, $data);
        } else {
            $this->getBatchService()->updateAboutOrderByOrder($dbInfo, $data);
        }
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
     * @throws BusinessLogicException
     */
    private function changeBatch($dbInfo, $data)
    {
        //站点移除订单,添加新的订单
        $this->getBatchService()->removeOrder(array_merge($data, Arr::only($dbInfo, ['batch_no', 'tour_no'])));
        list($batch, $tour) = $this->getBatchService()->join($data);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($dbInfo['id'], $batch, $tour);
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
        $info = $this->getInfoOfStatus($id);
        if (!empty($params['batch_no']) && ($info['batch_no'] == $params['batch_no'])) {
            throw new BusinessLogicException('当前订单已存在分配的站点中!');
        }
        $info['execution_date'] = $params['execution_date'];
        list($batch, $tour) = $this->getBatchService()->assignOrderToBatch($info, $params);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $this->fillBatchTourInfo($id, $batch, $tour);
    }

    /**
     * 从站点中移除订单
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromBatch($id)
    {
        $info = $this->getInfoOfStatus($id);
        if (empty($info['batch_no'])) {
            throw new BusinessLogicException('已从站点移除!');
        }
        $rowCount = parent::updateById($id, ['tour_no' => '', 'batch_no' => '', 'driver_id' => null, 'driver_name' => '', 'car_id' => null, 'car_no' => null]);
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
        $info = $this->getInfoOfStatus($id);
        $rowCount = parent::updateById($id, ['status' => BaseConstService::ORDER_STATUS_7, 'execution_date' => null, 'batch_no' => '', 'tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单删除失败,请重新操作');
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
        $order = $this->getInfoOfStatus($id, false, BaseConstService::ORDER_STATUS_7);
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
        $this->fillBatchTourInfo($id, $batch, $tour);
    }


    /**
     * 彻底删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function actualDestroy($id)
    {
        $info = $this->getInfoOfStatus($id, true, BaseConstService::ORDER_STATUS_7);
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败,请重新操作');
        }
        $rowCount = $this->getOrderItemService()->delete(['order_no' => $info['order_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('彻底删除失败,请重新操作');
        }
    }
}
