<?php

namespace App\Services\Merchant;

use App\Events\OrderCancel;
use App\Events\OrderExecutionDateUpdated;
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
use App\Services\TrackingOrderTrailService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
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
        'receiver_fullname' => ['=', 'receiver_fullname'],
        'receiver_phone' => ['=', 'receiver_phone'],
        'receiver_country' => ['=', 'receiver_country'],
        'receiver_post_code' => ['=', 'receiver_post_code'],
        'receiver_house_number' => ['=', 'receiver_house_number'],
        'receiver_city' => ['=', 'receiver_city'],
        'receiver_street' => ['=', 'receiver_street'],
        'tour_no' => ['like', 'tour_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Batch $batch)
    {
        parent::__construct($batch, BatchResource::class, BatchInfoResource::class);
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
     * 运单 服务
     * @return TrackingOrderService
     */
    private function getTrackingOrderService()
    {
        return self::getInstance(TrackingOrderService::class);
    }

    /**
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
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
     * 材料 服务
     * @return MaterialService
     */
    private function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }

    public function getPageList()
    {
        if (isset($this->filters['status'][1]) && (intval($this->filters['status'][1]) == 0)) {
            unset($this->filters['status']);
        }
        return parent::getPageList();
    }

    /**
     * 加入站点
     * @param $order
     * @param $batchNo
     * @param $tour
     * @param $line
     * @param $isAddOrder
     * @return array
     * @throws BusinessLogicException
     */
    public function join($order, $line, $batchNo = null, $tour = [], $isAddOrder = false)
    {
        list($batch, $tour) = $this->hasSameBatch($order, $line, $batchNo, $tour, $isAddOrder);
        if (!empty($batchNo) && empty($batch)) {
            throw new BusinessLogicException('当前指定站点不符合当前订单');
        }
        /*******************************若存在相同站点,则直接加入站点,否则新建站点*************************************/
        $batch = !empty($batch) ? $this->joinExistBatch($order, $batch) : $this->joinNewBatch($order, $line);
        /**************************************站点加入取件线路********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $order, $tour);
        /***********************************************填充取件线路编号************************************************/
        $this->fillTourInfo($batch, $line, $tour);

        return [$batch, $tour];
    }


    /**
     * 获取站点条件
     * @param $info
     * @return array
     */
    private function getBatchWhere($info)
    {
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $where = [
                'execution_date' => $info['execution_date'],
                'receiver_fullname' => $info['receiver_fullname'],
                'receiver_phone' => $info['receiver_phone'],
                'receiver_country' => $info['receiver_country'],
                'receiver_city' => $info['receiver_city'],
                'receiver_street' => $info['receiver_street'],
                'receiver_house_number' => $info['receiver_house_number'],
                'receiver_post_code' => $info['receiver_post_code'],
                'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]]
            ];
        } else {
            $where = [
                'execution_date' => $info['execution_date'],
                'receiver_fullname' => $info['receiver_fullname'],
                'receiver_phone' => $info['receiver_phone'],
                'receiver_country' => $info['receiver_country'],
                'receiver_address' => $info['receiver_address'],
                'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]]
            ];
        }
        return $where;
    }

    /**
     * 判断是否存在相同站点
     * @param $order
     * @param $batchNo
     * @param $tour
     * @param $line
     * @param $isAddOrder bool 是否是加单
     * @return array
     * @throws BusinessLogicException
     */
    private function hasSameBatch($order, $line, $batchNo = null, $tour = [], $isAddOrder = false)
    {
        $where = $this->getBatchWhere($order);
        $where = Arr::add($where, 'line_id', $line['id']);
        !empty($tour['tour_no']) && $where['tour_no'] = $tour['tour_no'];
        $isAddOrder && $where['status'] = ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING]];
        (isset($line['range_merchant_id']) && empty($batchNo)) && $where['merchant_id'] = $line['range_merchant_id'];
        if (!empty($batchNo)) {
            $where['batch_no'] = $batchNo;
            $dbBatch = parent::getInfo($where, ['*'], false);
            $batchList = empty($dbBatch) ? [] : [$dbBatch->toArray()];
        } else {
            $batchList = parent::getList($where, ['*'], false, [], ['id' => 'desc'])->toArray();
        }
        if (empty($batchList)) return [[], $tour];
        foreach ($batchList as $batch) {
            $tour = !empty($tour) ? $tour : $this->getTourService()->getTourInfo($batch, $line, true, $batch['tour_no'] ?? '');
            if (!empty($tour)) {
                return [$batch, $tour];
            }
        }
        return [[], $tour];
    }


    /**
     * 加入新的站点
     * @param $order
     * @param $line
     * @return array
     * @throws BusinessLogicException
     */
    private function joinNewBatch($order, $line)
    {
        $batchNo = $this->getOrderNoRuleService()->createBatchNo();
        $batch = parent::create($this->fillData($order, $line, $batchNo));
        if ($batch === false) {
            throw new BusinessLogicException('订单加入站点失败!');
        }
        $batch = $batch->getOriginal();
        return $batch;
    }

    /**
     * 加入已存在的站点
     * @param $order
     * @param $batch
     * @return array
     * @throws BusinessLogicException
     */
    public function joinExistBatch($order, $batch)
    {
        //锁定站点
        $batch = parent::getInfoLock(['id' => $batch['id']], ['*'], false);
        $data = (intval($order['type']) === 1) ? [
            'expect_pickup_quantity' => intval($batch['expect_pickup_quantity']) + 1] : ['expect_pie_quantity' => intval($batch['expect_pie_quantity']) + 1
        ];
        $rowCount = parent::updateById($batch['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单加入站点失败!');
        }
        $batch = array_merge($batch->toArray(), $data);
        return $batch;
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
            'receiver_fullname' => $order['receiver_fullname'],
            'receiver_phone' => $order['receiver_phone'],
            'receiver_country' => $order['receiver_country'],
            'receiver_post_code' => $order['receiver_post_code'],
            'receiver_house_number' => $order['receiver_house_number'],
            'receiver_city' => $order['receiver_city'],
            'receiver_street' => $order['receiver_street'],
            'receiver_address' => $order['receiver_address'],
            'receiver_lon' => $order['lon'],
            'receiver_lat' => $order['lat'],
            'merchant_id' => $line['range_merchant_id'] ?? 0
        ];
        if (intval($order['type']) === 1) {
            $data['expect_pickup_quantity'] = 1;
            $data['expect_pie_quantity'] = 0;
        } else {
            $data['expect_pickup_quantity'] = 0;
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
        $info['tracking_order_count'] = $this->getTrackingOrderService()->count(['batch_no' => $info['batch_no']]);
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
        $info = $this->getInfoOfStatus(['batch_no' => $dbOrder['batch_no']], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        //若订单类型改变,则站点统计数量改变
        $data = [];
        if (intval($dbOrder['type']) !== intval($order['type'])) {
            if (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] + 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] - 1;
            } else {
                $data['expect_pickup_quantity'] = $info['expect_pickup_quantity'] - 1;
                $data['expect_pie_quantity'] = $info['expect_pie_quantity'] + 1;
            }
        }
        //代收款费用
        $rowCount = parent::updateById($info['id'], array_merge($data));
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
     * @throws BusinessLogicException
     */
    public function getPageListByOrder($order)
    {
        //通过订单获取可能站点
        $data = [];
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $fields = ['receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'];
        } else {
            $fields = ['receiver_fullname', 'receiver_phone', 'receiver_country', 'receiver_address'];
        }
        $rule = array_merge($this->formData, Arr::only($order, $fields));
        $this->query->whereIn('status', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]);
        $info = $this->getList($rule);
        if (!empty($info)) {
            for ($i = 0, $j = count($info); $i < $j; $i++) {
                $tour = $this->getTourService()->getInfo(['tour_no' => $info[$i]['tour_no']], ['*'], false)->toArray();
                $line = $this->getLineService()->getInfo(['id' => $info[$i]['line_id']], ['*'], false)->toArray();
                if (!empty($tour) && !empty($line)) {
                    //当日截止时间验证
                    if ((date('Y-m-d') == $info[$i]['execution_date'] && time() < strtotime($info[$i]['execution_date'] . ' ' . $line['order_deadline']) ||
                        date('Y-m-d') !== $info[$i]['execution_date'])) {
                        //取件订单，线路最大订单量验证
                        if ($this->formData['status'] = BaseConstService::TRACKING_ORDER_TYPE_1 && $tour['expect_pickup_quantity'] + $info[$i]['expect_pickup_quantity'] < $line['pickup_max_count']) {
                            $data[$i] = $info[$i];
                        }
                        //派件订单，线路最大订单量验证
                        if ($this->formData['status'] = BaseConstService::TRACKING_ORDER_TYPE_2 && $tour['expect_pie_quantity'] + $info[$i]['expect_pie_quantity'] < $line['pie_max_count']) {
                            $data[$i] = $info[$i];
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 站点移除订单
     * @param $order
     * @throws BusinessLogicException
     */
    public function removeOrder($order)
    {
        $info = $this->getInfoOfStatus(['batch_no' => $order['batch_no']], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT], true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他订单时,删除站点;若还存在其他订单,则只移除订单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('站点移除订单失败，请重新操作');
        }
        //取件线路移除站点
        if (!empty($order['tour_no'])) {
            $this->getTourService()->removeBatchOrder($order, $info);
        }
    }

    /**
     * 取消取派
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function cancel($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        $orderList = $this->getOrderService()->getList(['batch_no' => $info['batch_no'], 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2]]], ['*'], false)->toArray();
        //站点删除
        $rowCount = parent::delete(['id' => $info['id']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //若存在取件线路编号,则移除站点
        if (!empty($info['tour_no'])) {
            $this->getTourService()->removeBatch($info);
            $this->getTourService()->reCountAmountByNo($info['tour_no']);
        }

        TrackingOrderTrailService::storeAllByTrackingOrderList($orderList, BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);

        //取消通知
        foreach ($orderList as $order) {
            event(new OrderCancel($order['order_no'], $order['out_order_no']));
        }
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
            'merchant_id' => $tour['merchant_id']
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
     * 填充站点信息和取件线路信息
     * @param $batch
     * @param $line
     * @param $tour
     * @throws BusinessLogicException
     */
    private function fillTourInfo(&$batch, $line, $tour)
    {
        $data = [
            'execution_date' => $tour['execution_date'],
            'tour_no' => $tour['tour_no'],
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'driver_id' => $tour['driver_id'] ?? null,
            'driver_name' => $tour['driver_name'] ?? '',
            'car_id' => $tour['car_id'] ?? null,
            'car_no' => $tour['car_no'] ?? '',
            'status' => $tour['status'] ?? BaseConstService::BATCH_WAIT_ASSIGN,
            'merchant_id' => $tour['merchant_id']
        ];
        $rowCount = parent::updateById($batch['id'], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入取件线路失败，请重新操作');
        }
        $batch = array_merge($batch, $data);
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
            return 'true';
        }
        //修改站点
        $rowCount = parent::updateById($id, ['tour_no' => '', 'driver_id' => null, 'driver_name' => '', 'execution_date' => null, 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::BATCH_WAIT_ASSIGN]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //材料移除取件线路信息
        $rowCount = $this->getMaterialService()->update(['batch_no' => $info['batch_no']], ['tour_no' => '', 'execution_date' => null]);
        if ($rowCount === false) {
            throw new BusinessLogicException('移除失败,请重新操作');
        }
        //将站点从取件线路移除
        $this->getTourService()->removeBatch($info);
        //重新统计取件线路金额
        !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);

        TrackingOrderTrailService::storeByBatch($info, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR);
        return 'true';
    }

    /**
     * 获取可分配日期
     * @param $id
     * @return \Illuminate\Support\Collection
     * @throws BusinessLogicException
     */
    public function getTourDate($id)
    {
        $ids = [];
        $data = [];
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $lineRange = $this->getLineRangeService()->query->where('post_code_start', '<=', $info['receiver_post_code'])
            ->where('post_code_end', '>=', $info['receiver_post_code'])
            ->where('country', $info['receiver_country'])
            ->get();
        if (empty($lineRange)) {
            throw new BusinessLogicException('当前订单没有合适的线路，请先联系管理员');
        }
        foreach ($lineRange as $key => $value) {
            $ids[$key] = $value['line_id'];
            $data[intval($value['schedule'])] = $this->getLineService()->getInfo(['id' => $ids[$key]], ['*'], false)->toArray()['appointment_days'];
        }
        for ($i = 0; $i < 7; $i++) {
            if (empty($data[$i])) {
                $data[$i] = 0;
            }
        }
        krsort($data);
        return array_reverse($data);
    }

    /**
     * 重新统计金额
     * @param $batchNo
     * @throws BusinessLogicException
     */
    public function reCountAmountByNo($batchNo)
    {
        $totalReplaceAmount = $this->getOrderService()->sum('replace_amount', ['batch_no' => $batchNo]);
        $totalSettlementAmount = $this->getOrderService()->sum('settlement_amount', ['batch_no' => $batchNo]);
        $rowCount = parent::update(['batch_no' => $batchNo], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }
}
