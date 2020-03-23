<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\BatchResource;
use App\Http\Resources\BatchInfoResource;
use App\Http\Resources\TourResource;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Services\OrderTrailService;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class BatchService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_id' => ['=', 'driver_id'],
        'line_id,line_name' => ['like', 'line_keyword'],
        'batch_no' => ['like', 'keyword'],
        'receiver' => ['=', 'receiver'],
        'receiver_phone' => ['=', 'receiver_phone'],
        'receiver_country' => ['=', 'receiver_country'],
        'receiver_post_code' => ['=', 'receiver_post_code'],
        'receiver_house_number' => ['=', 'receiver_house_number'],
        'receiver_city' => ['=', 'receiver_city'],
        'receiver_street' => ['=', 'receiver_street']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Batch $batch)
    {
        $this->model = $batch;
        $this->query = $this->model::query();
        $this->resource = BatchResource::class;
        $this->infoResource = BatchInfoResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 线路服务
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
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
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
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    public function getPageList()
    {
        if (isset($this->filters['status'][1]) && (intval($this->filters['status'][1]) == 0)) {
            unset($this->filters['status']);
        }
        return parent::getPageList();
    }

    /**
     * 获取待分配订单信息
     * @param $where
     * @param $isToArray
     * @param $status
     * @param $isLock
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    private function getInfoOfStatus($where, $isToArray = true, $status = BaseConstService::BATCH_WAIT_ASSIGN, $isLock = true)
    {
        $info = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (!in_array(intval($info['status']), Arr::wrap($status))) {
            throw new BusinessLogicException('当前站点状态不能操作');
        }
        return $isToArray ? $info->toArray() : $info;
    }


    //新增
    public function store($params)
    {
    }


    /**
     * 加入站点
     * @param $order
     * @return array
     * @throws BusinessLogicException
     */
    public function join($order)
    {
        list($batch, $line) = $this->hasSameBatch($order);
        /*******************************若存在相同站点,则直接加入站点,否则新建站点*************************************/
        list($batch, $line) = !empty($batch) ? $this->joinExistBatch($order, $batch, $line) : $this->joinNewBatch($order);
        /**************************************站点加入取件线路********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $order['type']);
        /***********************************************填充取件线路编号************************************************/
        $this->fillTourInfo($batch, $line, $tour);

        return [$batch, $tour];
    }

    /**
     * 获取站点条件
     *
     * @param $info
     * @return array
     */
    private function getBatchWhere($info)
    {
        return [
            'execution_date' => $info['execution_date'],
            'receiver' => $info['receiver'],
            'receiver_phone' => $info['receiver_phone'],
            'receiver_country' => $info['receiver_country'],
            'receiver_city' => $info['receiver_city'],
            'receiver_street' => $info['receiver_street'],
            'receiver_house_number' => $info['receiver_house_number'],
            'receiver_post_code' => $info['receiver_post_code'],
            'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]]
        ];
    }

    /**
     * 判断是否存在相同站点
     * @param $order
     * @param $batchNo
     * @return array
     * @throws BusinessLogicException
     */
    private function hasSameBatch($order, $batchNo = null)
    {
        $where = $this->getBatchWhere($order);
        if (!empty($batchNo)) {
            $where['batch_no'] = $batchNo;
            $dbBatch = parent::getInfo($where, ['*'], false);
            $batchList = empty($dbBatch) ? [] : [$dbBatch->toArray()];
        } else {
            $batchList = parent::getList($where, ['*'], false, [], ['created_at' => 'desc'])->toArray();
        }
        if (empty($batchList)) return [[], []];
        foreach ($batchList as $batch) {
            //获取线路信息
            $line = $this->getLineService()->getInfo(['id' => $batch['line_id']], ['*'], false);
            if (empty($line)) {
                continue;
            }
            $line = $line->toArray();
            //获取取件线路信息
            $tour = $this->getTourService()->getInfo(['tour_no' => $batch['tour_no']], ['*'], false)->toArray();
            //若取件线路不是待分配或已分配状态,则需要新建站点
            if (!in_array(intval($tour['status']), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2])) {
                continue;
            }
            //若未超过最大订单量,则加入已有站点
            if ((intval($tour['expect_pickup_quantity']) + intval($tour['expect_pie_quantity'])) < intval($line['order_max_count'])) {
                //锁定当前站点
                $batch = parent::getInfoLock(['id' => $batch['id']], ['*'], false)->toArray();
                return [$batch, $line];
            }
        }
        return [[], []];
    }


    /**
     * 加入新的站点
     * @param $order
     * @return array
     * @throws BusinessLogicException
     */
    private function joinNewBatch($order)
    {
        $line = $this->getLineInfo($order);
        //站点新增
        $batchNo = $this->getOrderNoRuleService()->createBatchNo();
        $batch = parent::create($this->fillData($order, $line, $batchNo));
        if ($batch === false) {
            throw new BusinessLogicException('订单加入站点失败!');
        }
        $batch = $batch->getOriginal();
        return [$batch, $line];
    }

    /**
     * 加入已存在的站点
     * @param $order
     * @param $batch
     * @return array
     * @throws BusinessLogicException
     */
    public function joinExistBatch($order, $batch, $line)
    {
        $replaceAmount = empty($order['replace_amount']) ? 0.00 : $order['replace_amount'];
        $settlementAmount = empty($order['settlement_amount']) ? 0.00 : $order['settlement_amount'];
        $data = (intval($order['type']) === 1) ? [
            'expect_pickup_quantity' => intval($batch['expect_pickup_quantity']) + 1] : ['expect_pie_quantity' => intval($batch['expect_pie_quantity']) + 1,
            'replace_amount' => $batch['replace_amount'] + $replaceAmount,
            'settlement_amount' => $batch['settlement_amount'] + $settlementAmount
        ];
        $rowCount = parent::updateById($batch['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单加入站点失败!');
        }
        return [$batch, $line];
    }

    /**
     * 填充站点新增数据
     * @param $order
     * @param $line
     * @param $batchNo
     * @param $lon
     * @param $lat
     * @return array
     */
    private function fillData($order, $line, $batchNo)
    {
        $data = [
            'batch_no' => $batchNo,
            'line_id' => $line['id'],
            'line_name' => $line['name'],
            'execution_date' => $order['execution_date'],
            'receiver' => $order['receiver'],
            'receiver_phone' => $order['receiver_phone'],
            'receiver_country' => $order['receiver_country'],
            'receiver_post_code' => $order['receiver_post_code'],
            'receiver_house_number' => $order['receiver_house_number'],
            'receiver_city' => $order['receiver_city'],
            'receiver_street' => $order['receiver_street'],
            'receiver_address' => $order['receiver_address'],
            'receiver_lon' => $order['lon'],
            'receiver_lat' => $order['lat'],
            'replace_amount' => empty($order['replace_amount']) ? 0.00 : $order['replace_amount'],
            'settlement_amount' => empty($order['settlement_amount']) ? 0.00 : $order['settlement_amount']
        ];
        if (intval($order['type']) === 1) {
            $data['expect_pickup_quantity'] = 1;
        } else {
            $data['expect_pie_quantity'] = 1;
        }
        return $data;
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
            throw new BusinessLogicException('数据不存在');
        }
        $info['order_count'] = $this->getOrderService()->count(['batch_no' => $info['batch_no']]);
        return $info;
    }

    /**
     * 通过订单,修改订单相关数据
     * @param $dbOrder
     * @param $order
     * @throws BusinessLogicException
     */
    public function updateAboutOrderByOrder($dbOrder, $order)
    {
        $info = $this->getInfoOfStatus(['batch_no' => $dbOrder['batch_no']], true, BaseConstService::BATCH_WAIT_ASSIGN, true);
        //若订单类型改变,则站点统计数量改变
        $data = [];
        if (intval($dbOrder['type']) !== intval($order['type'])) {
            if (intval($order['type']) === BaseConstService::ORDER_TYPE_1) {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] + 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] - 1;
            } else {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] - 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] + 1;
            }
        }
        //代收款费用
        $diffReplaceAmount = $order['replace_amount'] - $dbOrder['replace_amount'];
        $diffSettlementAmount = $order['settlement_amount'] - $dbOrder['settlement_amount'];
        $rowCount = parent::updateById($info['id'], array_merge($data, [
            'replace_amount' => $info['replace_amount'] + $diffReplaceAmount,
            'settlement_amount' => $info['settlement_amount'] + $diffSettlementAmount
        ]));
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        //取件线路修订单信息
        $this->getTourService()->updateAboutOrderByOrder($dbOrder, $order);
    }


    /**
     * 通过订单数据,获取站点列表
     * @param $order
     * @return mixed
     */
    public function getPageListByOrder($order)
    {
        $fields = ['receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'];
        $this->formData = array_merge($this->formData, Arr::only($order, $fields));
        $this->filters['execution_date'] = ['=', $order['execution_date']];
        $this->setFilterRules();
        return parent::getPageList();
    }

    /**
     * 订单分配至站点
     * @param $order
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function assignOrderToBatch($order, $params)
    {
        //从旧站点移除
        if (!empty($order['batch_no'])) {
            $this->removeOrder($order);
        }
        //判断是否可以加入该站点
        $batchNo = !empty($params['batch_no']) ? $params['batch_no'] : null;
        list($batch, $line) = $this->hasSameBatch($order, $batchNo);
        if (!empty($batchNo) && empty($batch)) {
            throw new BusinessLogicException('当前指定站点不符合当前订单');
        }
        /*******************************若存在相同站点,则直接加入站点,否则新建站点*************************************/
        list($batch, $line) = !empty($batch) ? $this->joinExistBatch($order, $batch, $line) : $this->joinNewBatch($order);
        /**************************************站点加入取件线路********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $order['type']);
        /***********************************************填充取件线路编号************************************************/
        $this->fillTourInfo($batch, $line, $tour);
        return [$batch, $tour];
    }


    /**
     * 站点移除订单
     * @param $order
     * @throws BusinessLogicException
     */
    public function removeOrder($order)
    {
        $info = $this->getInfoOfStatus(['batch_no' => $order['batch_no']], true, BaseConstService::BATCH_WAIT_ASSIGN, true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他订单时,删除站点;若还存在其他订单,则只移除订单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $data['settlement_amount'] = $info['settlement_amount'] - $order['settlement_amount'];
            $data['replace_amount'] = $info['replace_amount'] - $order['replace_amount'];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('站点移除订单失败，请重新操作');
        }
        //取件线路移除站点
        $this->getTourService()->removeBatchOrder($order, $info);
    }

    /**
     * 取消取派
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function cancel($id, $params)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        //站点取消取派
        $data = Arr::only($params, ['cancel_type', 'cancel_remark', 'cancel_picture']);
        $rowCount = parent::updateById($info['id'], Arr::add($data, 'status', BaseConstService::BATCH_CANCEL));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //订单取消取派
        $rowCount = $this->getOrderService()->update(['batch_no' => $info['batch_no']], Arr::add($data, 'status', BaseConstService::ORDER_STATUS_6));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        OrderTrailService::OrderStatusChangeUseOrderCollection(Order::where('batch_no', $info['batch_no'])->get(), BaseConstService::ORDER_TRAIL_CANCEL_DELIVER);
    }

    /**
     * 获取取件线路
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function getTourListByBatch($id, $params)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $line = $this->getLineService()->getInfo(['id' => $info['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('路线不存在');
        }
        $line = $line->toArray();
        $info['execution_date'] = $params['execution_date'];
        return $this->getTourService()->getListByBatch($info, $line);
    }


    /**
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function assignToTour($id, $params)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_CANCEL], true);
        if (!empty($params['tour_no']) && ($info['tour_no'] == $params['tour_no'])) {
            throw new BusinessLogicException('当前站点已分配至当前指定取件线路');
        }
        $info['execution_date'] = $params['execution_date'];
        //获取线路信息
        $line = $this->getLineInfo($info);
        list($tour, $batch) = $this->getTourService()->assignBatchToTour($info, $line, $params);
        /***********************************************填充取件线路编号************************************************/
        $this->fillTourInfo($batch, $line, $tour);
        /***********************************************修改订单************************************************/
        $orderList = $this->getOrderService()->getList(['batch_no' => $info['batch_no']], ['*'], false)->toArray();
        foreach ($orderList as $order) {
            $this->getOrderService()->fillBatchTourInfo($order, $batch, $tour);
        }
    }

    /**
     * 合并两个站点
     *
     * @param $tour
     * @param $batch
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function mergeTwoBatch($tour, $batch)
    {
        $dbBatch = parent::getInfo(array_merge(['tour_no' => $tour['tour_no']], $this->getBatchWhere($batch)), ['*'], false);
        if (empty($dbBatch)) return $batch;
        $dbBatch = $dbBatch->toArray();
        $rowCount = $this->model->newQuery()->where('id', $dbBatch['id'])->update([
            'expect_pickup_quantity' => DB::raw('expect_pickup_quantity+' . $batch['expect_pickup_quantity']),
            'actual_pickup_quantity' => DB::raw('actual_pickup_quantity+' . $batch['actual_pickup_quantity']),
            'expect_pie_quantity' => DB::raw('expect_pie_quantity+' . $batch['expect_pie_quantity']),
            'actual_pie_quantity' => DB::raw('actual_pie_quantity+' . $batch['actual_pie_quantity']),
            'replace_amount' => DB::raw('replace_amount+' . $batch['replace_amount']),
            'settlement_amount' => DB::raw('settlement_amount+' . $batch['settlement_amount']),
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        //删除站点
        $rowCount = parent::delete(['id' => $batch['id']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        return $dbBatch;
    }

    /**
     * 获取线路信息
     * @param $info
     * @return array
     * @throws BusinessLogicException
     */
    private function getLineInfo($info)
    {
        //获取邮编数字部分
        $postCode = explode_post_code($info['receiver_post_code']);
        //获取线路范围
        $lineRange = $this->getLineRangeService()->getInfo(['post_code_start' => ['<=', $postCode], 'post_code_end' => ['>=', $postCode], 'schedule' => Carbon::parse($info['execution_date'])->dayOfWeek, 'country' => $info['receiver_country']], ['*'], false);
        if (empty($lineRange)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        $lineRange = $lineRange->toArray();
        //获取线路信息
        $line = $this->getLineService()->getInfo(['id' => $lineRange['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        return $line->toArray();
    }

    /**
     * 填充站点信息和取件线路信息
     * @param $id
     * @param $batch
     * @param $tour
     * @throws BusinessLogicException
     */
    private function fillTourInfo($batch, $line, $tour)
    {
        $rowCount = parent::updateById($batch['id'], [
            'execution_date' => $tour['execution_date'],
            'tour_no' => $tour['tour_no'],
            'line_id' => $line['id'],
            'line_name' => $line['name'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::BATCH_WAIT_ASSIGN
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作');
        }
    }

    /**
     * 从取件线路移除站点
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromTour($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        if (empty($info['tour_no'])) {
            throw new BusinessLogicException('当前站点已经移除，不能重复操作');
        }
        //修改站点
        $rowCount = parent::updateById($id, ['tour_no' => '', 'driver_id' => null, 'driver_name' => '', 'car_id' => null, 'car_no' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //修改订单
        $rowCount = $this->getOrderService()->update(['batch_no' => $info['batch_no']], ['tour_no' => '', 'driver_id' => null, 'driver_name' => '', 'car_id' => null, 'car_no' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //将站点从取件线路移除
        $this->getTourService()->removeBatch($info);
    }

    /**
     * 获取可分配日期
     * @param $id
     * @return \Illuminate\Support\Collection
     * @throws BusinessLogicException
     */
    public function getTourDate($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $this->getLineRangeService()->query
            ->where('post_code_start', '<=', $info['receiver_post_code'])
            ->where('post_code_end', '>=', $info['receiver_post_code'])->distinct()->pluck('schedule');
    }
}
