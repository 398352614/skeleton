<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:57
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\BatchInfoResource;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class BatchService extends BaseService
{
    public function __construct(Batch $batch)
    {
        parent::__construct($batch, BatchResource::class, BatchInfoResource::class);
    }

    /**
     * 取件线路 服务
     * @return TourService
     */
    private function getTourService()
    {
        return self::getInstance(TourService::class);
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
     * 订单 服务
     * @return OrderService
     */
    private function getOrderService()
    {
        return self::getInstance(OrderService::class);
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
        $where['driver_id'] = ['<>', null];
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
        $batch = parent::getInfoLock(['id' => $batch['id'], 'driver_id' => ['<>', null]], ['*'], false);
        $data = (intval($trackingOrder['type']) === 1) ? [
            'expect_pickup_quantity' => intval($batch['expect_pickup_quantity']) + 1] : ['expect_pie_quantity' => intval($batch['expect_pie_quantity']) + 1
        ];
        $rowCount = parent::updateById($batch['id'], $data);
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
            'receiver_fullname' => $order['receiver_fullname'],
            'receiver_phone' => $order['receiver_phone'],
            'receiver_country' => $order['receiver_country'],
            'receiver_post_code' => $order['receiver_post_code'],
            'receiver_house_number' => $order['receiver_house_number'],
            'receiver_city' => $order['receiver_city'],
            'receiver_street' => $order['receiver_street'],
            'receiver_address' => $order['receiver_address'],
            'receiver_lon' => $order['receiver_lon'],
            'receiver_lat' => $order['receiver_lat'],
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
        $totalReplaceAmount = $this->getOrderService()->sum('replace_amount', ['batch_no' => $batchNo, 'driver_id' => ['<>', null]]);
        $totalSettlementAmount = $this->getOrderService()->sum('settlement_amount', ['batch_no' => $batchNo, 'driver_id' => ['<>', null]]);
        $rowCount = parent::update(['batch_no' => $batchNo, 'driver_id' => ['<>', null]], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

}
