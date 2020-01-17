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
        'exception_label' => ['=', 'exception_label']
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
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_1,'exception_label'=>BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        return ['no_take' => $noTakeCount, 'assign' => $assignCount, 'wait_out' => $waitOutCount, 'taking' => $takingCount, 'singed' => $signedCount, 'cancel_count' => $cancelCount,'exception_count'=>$exceptionCount];
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
        $exceptionCount = parent::count(['type' => BaseConstService::ORDER_TYPE_2,'exception_label'=>BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        return ['no_take' => $noTakeCount, 'assign' => $assignCount, 'wait_out' => $waitOutCount, 'taking' => $takingCount, 'singed' => $signedCount, 'cancel_count' => $cancelCount,'exception_count'=>$exceptionCount];
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
        if(empty($params['receiver_address'])){
            $params['receiver_address']='';
        }
        $order = parent::create($params);
        if ($order === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_CREATED);
        /*****************************************订单加入站点*********************************************************/
        list($batch, $tour) = $this->getBatchService()->join($params);
        OrderTrailService::OrderStatusChangeCreateTrail($order, BaseConstService::ORDER_TRAIL_JOIN_BATCH);
        /**********************************填充取件批次编号和取件线路编号**********************************************/
        $rowCount = parent::updateById($order->getOriginal('id'), [
            'batch_no' => $batch['batch_no'],
            'tour_no' => $tour['tour_no'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::ORDER_STATUS_1
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单新增失败');
        }
        /**************************************新增订单货物明细********************************************************/
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
     * 验证
     * @param $params
     * @throws BusinessLogicException
     */
    private function check(&$params)
    {
        if ($params['express_second_no']) {
            //验证快递单号是否重复,由于外面已经对应验证过了,所以这里只需要验证快递单号1是否和快递单号2重复,快递单号1和快递单号2重复
            $info = parent::getInfo(['express_first_no' => $params['express_second_no']], ['*'], false);
            if (!empty($info)) {
                throw new BusinessLogicException('快递单号2已存在');
            }
        }
        $info = parent::getInfo(['express_second_no' => $params['express_first_no']], ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('快递单号1已存在');
        }
        //验证货物名称是否重复
        if (!empty($data['item_list'])) {
            $nameList = array_column(json_decode($params['item_list'], true), 'name');
            if (count(array_unique($nameList)) !== count($nameList)) {
                throw new BusinessLogicException('货物名称有重复!不能添加订单');
            }
        }
    }
}
