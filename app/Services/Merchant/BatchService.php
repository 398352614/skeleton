<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\BatchInfoResource;
use App\Http\Resources\Api\Merchant\BatchResource;
use App\Models\Batch;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class BatchService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_id' => ['=', 'driver_id'],
        'line_id,line_name' => ['like', 'line_keyword'],
        'batch_no' => ['like', 'keyword'],
        'place_fullname' => ['=', 'place_fullname'],
        'place_phone' => ['=', 'place_phone'],
        'place_country' => ['=', 'place_country'],
        'place_post_code' => ['=', 'place_post_code'],
        'place_house_number' => ['=', 'place_house_number'],
        'place_city' => ['=', 'place_city'],
        'place_street' => ['=', 'place_street'],
        'tour_no' => ['like', 'tour_no']
    ];

    public $orderBy = ['id' => 'desc'];

    public function __construct(Batch $batch)
    {
        parent::__construct($batch, BatchResource::class, BatchInfoResource::class);
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
                'place_fullname' => $info['place_fullname'],
                'place_phone' => $info['place_phone'],
                'place_country' => $info['place_country'],
                'place_city' => $info['place_city'],
                'place_street' => $info['place_street'],
                'place_house_number' => $info['place_house_number'],
                'place_post_code' => $info['place_post_code'],
                'status' => ['in', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED]]
            ];
        } else {
            $where = [
                'execution_date' => $info['execution_date'],
                'place_fullname' => $info['place_fullname'],
                'place_phone' => $info['place_phone'],
                'place_country' => $info['place_country'],
                'place_address' => $info['place_address'],
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
            $dbBatch = parent::getInfoLock($where, ['*'], false);
            $batchList = empty($dbBatch) ? [] : [$dbBatch->toArray()];
        } else {
            $batchList = parent::getListLock($where, ['*'], false, [], ['id' => 'desc']);
            !empty($batchList) && $batchList = $batchList->toArray();
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
            'place_fullname' => $order['place_fullname'],
            'place_phone' => $order['place_phone'],
            'place_country' => $order['place_country'],
            'place_post_code' => $order['place_post_code'],
            'place_house_number' => $order['place_house_number'],
            'place_city' => $order['place_city'],
            'place_street' => $order['place_street'],
            'place_address' => $order['place_address'],
            'place_lon' => $order['place_lon'] ?? '',
            'place_lat' => $order['place_lat'] ?? '',
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
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        //获取运单列表
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $info['batch_no']], ['*'], false)->toArray();
        $orderNoList = array_column($trackingOrderList, 'order_no');
        //订单列表
        $orderList = $this->getOrderService()->getList(['order_no' => ['in', $orderNoList]], ['*'], false)->toArray();
        //获取包裹列表
        $packageList = $this->getPackageService()->getList(['order_no' => ['in', $orderNoList]], ['*'], false)->toArray();
        //获取材料列表
        $materialList = $this->getMaterialService()->getList(['order_no' => ['in', $orderNoList]], ['*'], false)->toArray();
        //数据组装
        foreach ($orderList as $key => &$order) {
            $order['package_list'] = array_values(collect($packageList)->where('order_no', $order['order_no'])->all());
            $order['material_list'] = array_values(collect($materialList)->where('order_no', $order['order_no'])->all());
        }
        $info['tracking_order_count'] = count($trackingOrderList);
        $info['order_count'] = count($orderList);
        $info['orders'] = $orderList;
        return $info;
    }


    /**
     * 站点移除运单
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function removeTrackingOrder($trackingOrder)
    {
        $info = $this->getInfoOfStatus(['batch_no' => $trackingOrder['batch_no']], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT], true);
        $quantity = $info['expect_pickup_quantity'] + $info['expect_pie_quantity'];
        //当站点中不存在其他运单时,删除站点;若还存在其他运单,则只移除运单
        if ($quantity - 1 <= 0) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $data = (intval($trackingOrder['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => $info['expect_pickup_quantity'] - 1] : ['expect_pie_quantity' => $info['expect_pie_quantity'] - 1];
            $rowCount = parent::updateById($info['id'], $data);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('站点移除运单失败，请重新操作');
        }
        //取件线路移除站点
        if (!empty($trackingOrder['tour_no'])) {
            $this->getTourService()->removeBatchTrackingOrder($trackingOrder, $info);
        }
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
     * 重新统计金额
     * @param $batchNo
     * @throws BusinessLogicException
     */
    public function reCountAmountByNo($batchNo)
    {
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batchNo], ['*'], false);
        if ($trackingOrderList->isEmpty()) {
            $totalReplaceAmount = $totalSettlementAmount = 0;
        }else{
            $totalSettlementAmount = $this->getOrderService()->sum('settlement_amount', ['tracking_order_no' => ['in', $trackingOrderList->pluck('tracking_order_no')->toArray()]]);
            $totalReplaceAmount = $this->getOrderService()->sum('replace_amount', ['tracking_order_no' => ['in', $trackingOrderList->pluck('tracking_order_no')->toArray()]]);
        }
        $rowCount = parent::update(['batch_no' => $batchNo], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }
}
