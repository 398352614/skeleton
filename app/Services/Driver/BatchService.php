<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:57
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\BatchInfoResource;
use App\Http\Resources\Api\Driver\BatchResource;
use App\Models\Batch;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class BatchService extends BaseService
{
    public function __construct(Batch $batch)
    {
        parent::__construct($batch, BatchResource::class, BatchInfoResource::class);
    }

    /**
     * 加入站点
     * @param $trackingOrder
     * @param $batchNo
     * @param $tour
     * @param $line
     * @param $isAddOrder
     * @return array
     * @throws BusinessLogicException
     */
    public function join($trackingOrder, $line, $batchNo = null, $tour = [], $isAddOrder = false)
    {
        list($batch, $tour) = $this->hasSameBatch($trackingOrder, $line, $batchNo, $tour, $isAddOrder);
        if (!empty($batchNo) && empty($batch)) {
            throw new BusinessLogicException('当前指定站点不符合当前运单');
        }
        /*******************************若存在相同站点,则直接加入站点,否则新建站点*************************************/
        $batch = !empty($batch) ? $this->joinExistBatch($trackingOrder, $batch) : $this->joinNewBatch($trackingOrder, $line);
        /**************************************站点加入取件线路********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $trackingOrder, $tour);
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
     * @param $trackingOrder
     * @param $batchNo
     * @param $tour
     * @param $line
     * @param $isAddOrder bool 是否是加单
     * @return array
     * @throws BusinessLogicException
     */
    private function hasSameBatch($trackingOrder, $line, $batchNo = null, $tour = [], $isAddOrder = false)
    {
        $where = $this->getBatchWhere($trackingOrder);
        $where['driver_id'] = ['all', null];
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
     * @param $trackingOrder
     * @param $line
     * @return array
     * @throws BusinessLogicException
     */
    private function joinNewBatch($trackingOrder, $line)
    {
        $batchNo = $this->getOrderNoRuleService()->createBatchNo();
        $batch = parent::create($this->fillData($trackingOrder, $line, $batchNo));
        if ($batch === false) {
            throw new BusinessLogicException('运单加入站点失败!');
        }
        $batch = $batch->getOriginal();
        return $batch;
    }

    /**
     * 加入已存在的站点
     * @param $trackingOrder
     * @param $batch
     * @return array
     * @throws BusinessLogicException
     */
    public function joinExistBatch($trackingOrder, $batch)
    {
        //锁定站点
        $batch = parent::getInfoLock(['id' => $batch['id'], 'driver_id' => ['all', null]], ['*'], false);
        if(empty($batch)){
            throw new BusinessLogicException('数据被占用，请稍后操作');
        }
        $data = (intval($trackingOrder['type']) === 1) ? [
            'expect_pickup_quantity' => intval($batch['expect_pickup_quantity']) + 1
        ] : [
            'expect_pie_quantity' => intval($batch['expect_pie_quantity']) + 1
        ];
        $rowCount = parent::update(['id' => $batch['id'], 'driver_id' => ['all', null]], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单加入站点失败!');
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
            'place_lon' => $order['place_lon'],
            'place_lat' => $order['place_lat'],
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
        $rowCount = parent::update(['id' => $batch['id'], 'driver_id' => ['all', null]], $data);
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
        } else {
            $totalSettlementAmount = $this->getOrderService()->sum('settlement_amount', ['tracking_order_no' => ['in', $trackingOrderList->pluck('tracking_order_no')->toArray()]]);
            $totalReplaceAmount = $this->getOrderService()->sum('replace_amount', ['tracking_order_no' => ['in', $trackingOrderList->pluck('tracking_order_no')->toArray()]]);
        }
        $rowCount = parent::update(['batch_no' => $batchNo, 'driver_id' => ['all', null]], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

}
