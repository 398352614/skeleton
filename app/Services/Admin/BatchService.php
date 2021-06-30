<?php

namespace App\Services\Admin;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\BatchInfoResource;
use App\Http\Resources\Api\Admin\BatchResource;
use App\Jobs\UpdateLineCountTime;
use App\Models\Batch;
use App\Models\Driver;
use App\Models\Tour;
use App\Notifications\CancelBatch;
use App\Notifications\TourAddTrackingOrder;
use App\Services\BaseConstService;
use App\Services\OrderTrailService;
use App\Services\TrackingOrderTrailService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use WebSocket\Base;

class BatchService extends BaseService
{

    public $filterRules = [
        'status' => ['=', 'status'],
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'driver_name' => ['like', 'driver_name'],
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
        $list = parent::getPageList();
        $merchantList = $this->getMerchantService()->getList([], ['id', 'name'], false)->toArray();
        $merchantList = array_create_index($merchantList, 'id');
        foreach ($list as &$batch) {
            if ($batch['merchant_id'] == 0) {
                $batch['merchant_id_name'] = __('多商家');
            } else {
                $batch['merchant_id_name'] = $merchantList[$batch['merchant_id']]['name'] ?? '';
            }
        }
        return $list;
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
        /**************************************站点加入线路任务********************************************************/
        $tour = $this->getTourService()->join($batch, $line, $trackingOrder, $tour);
        /***********************************************填充线路任务编号************************************************/
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
            $tour = !empty($tour) ? $tour : $this->getTourService()->getTourInfo($batch, $line, true, $batch['tour_no'] ?? '', false, $isAddOrder);
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
        $batch = parent::getInfoLock(['id' => $batch['id']], ['*'], false);
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
     * @param $trackingOrder
     * @param $line
     * @param $batchNo
     * @param $lon
     * @param $lat
     * @return array
     */
    private function fillData($trackingOrder, $line, $batchNo)
    {
        $data = [
            'batch_no' => $batchNo,
            'line_id' => $line['id'],
            'line_name' => $line['name'],
            'execution_date' => $trackingOrder['execution_date'],
            'place_fullname' => $trackingOrder['place_fullname'],
            'place_phone' => $trackingOrder['place_phone'],
            'place_country' => $trackingOrder['place_country'],
            'place_post_code' => $trackingOrder['place_post_code'],
            'place_house_number' => $trackingOrder['place_house_number'],
            'place_city' => $trackingOrder['place_city'],
            'place_street' => $trackingOrder['place_street'],
            'place_address' => $trackingOrder['place_address'],
            'place_lon' => $trackingOrder['place_lon'] ?? '',
            'place_lat' => $trackingOrder['place_lat'] ?? '',
            'merchant_id' => $line['range_merchant_id'] ?? 0
        ];
        if (intval($trackingOrder['type']) === 1) {
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
        $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $info['batch_no']], ['*'], false)->toArray();
        //获取材料列表
        $materialList = $this->getTrackingOrderMaterialService()->getList(['batch_no' => $info['batch_no']], ['*'], false)->toArray();
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
     * 通过运单数据,获取站点列表
     * @param $trackingOrder
     * @return array
     */
    public function getListByTrackingOrder($trackingOrder)
    {
        //通过运单获取可能站点
        $data = [];
        if (CompanyTrait::getLineRule() === BaseConstService::LINE_RULE_POST_CODE) {
            $fields = ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street'];
        } else {
            $fields = ['place_fullname', 'place_phone', 'place_country', 'place_address'];
        }
        $rule = array_merge($this->formData, Arr::only($trackingOrder, $fields));
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
                        //取件运单，线路最大运单量验证
                        if ($this->formData['status'] = BaseConstService::TRACKING_ORDER_TYPE_1 && $tour['expect_pickup_quantity'] + $info[$i]['expect_pickup_quantity'] < $line['pickup_max_count']) {
                            $data[$i] = $info[$i];
                        }
                        //派件运单，线路最大运单量验证
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
        //线路任务移除站点
        if (!empty($trackingOrder['tour_no'])) {
            $this->getTourService()->removeBatchTrackingOrder($trackingOrder, $info);
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
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING], true);
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $info['batch_no'], 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4]]], ['*'], false)->toArray();
        //若是待分配,已分配,待出库,则删除;若是取派中，则取消取派
        if (in_array($info['status'], [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT])) {
            $rowCount = parent::delete(['id' => $info['id']]);
        } else {
            $rowCount = parent::update(['id' => $info['id']], ['status' => BaseConstService::BATCH_CANCEL, 'is_skipped' => BaseConstService::IS_NOT_SKIPPED]);
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //运单取消取派
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $info['batch_no'], 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4]]], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //运单包裹取消取派
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $info['batch_no'], 'status' => ['in', [BaseConstService::TRACKING_ORDER_STATUS_1, BaseConstService::TRACKING_ORDER_STATUS_2, BaseConstService::TRACKING_ORDER_STATUS_3, BaseConstService::TRACKING_ORDER_STATUS_4]]], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //运单材料取消取派
        $rowCount = $this->getTrackingOrderMaterialService()->update(['batch_no' => $info['batch_no']], ['tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //自动自动终止派送
        $this->getOrderService()->autoEnd($trackingOrderList);
        //若存在线路任务编号,则移除站点
        if (!empty($info['tour_no'])) {
            $tour = $this->getTourService()->removeBatch($info, true);
            $this->getTourService()->reCountAmountByNo($info['tour_no']);
            if (!empty($tour['driver_id']) && in_array($tour['status'], [BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4])) {
                Notification::send(Driver::findOrFail($tour['driver_id']), new CancelBatch($info));
            }
        }

        TrackingOrderTrailService::storeAllByTrackingOrderList($trackingOrderList, BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER);
        OrderTrailService::storeAllByOrderList($trackingOrderList, BaseConstService::ORDER_TRAIL_FAIL);
    }

    /**
     * 获取线路任务
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
            throw new BusinessLogicException('线路不存在');
        }
        $line = $line->toArray();
        $info['execution_date'] = $params['execution_date'];
        return $this->getTourService()->getListByBatch($info, $line);
    }


    /**
     * 分配至线路
     * @param $id
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function assignToTour($id, $params)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        if (intval($info['merchant_id']) != 0 && empty($params['is_alone']) && empty($params['tour_no'])) {
            throw new BusinessLogicException('独立取派站点，需先选择线路类型');
        }
        //若非独立取派，则加入混合线路任务中
        if (!empty($params['is_alone']) && (intval($params['is_alone']) == BaseConstService::NO)) {
            $info['merchant_id'] = 0;
        }
        //若直接加入线路任务,则不判断是否独立
        if (!empty($params['tour_no'])) {
            unset($info['merchant_id']);
        }
        unset($params['merchant_id']);
        $this->assignBatchToTour($info, $params);
        return 'true';
    }

    /**
     * 分配
     * @param $info
     * @param $params
     * @throws BusinessLogicException
     */
    private function assignBatchToTour($info, $params)
    {
        $date = $info['execution_date'];
        $info['execution_date'] = $params['execution_date'];
        if (isset($params['merchant_id'])) {
            $info['merchant_id'] = $params['merchant_id'];
        }
        //获取线路信息
        $line = $this->getLineService()->getInfoByLineId($info, $params);
        list($tour, $batch) = $this->getTourService()->assignBatchToTour($info, $line, $params);
        /***********************************************填充线路任务编号************************************************/
        $this->fillTourInfo($batch, $line, $tour);
        /***********************************************修改运单************************************************/
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $info['batch_no']], ['*'], false)->toArray();
        foreach ($trackingOrderList as $trackingOrder) {
            $this->getTrackingOrderService()->fillBatchTourInfo($trackingOrder, $batch, $tour, true);
        }
        //重新统计站点金额
        $this->reCountAmountByNo($info['batch_no']);
        //重新统计线路任务金额
        !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);
        TrackingOrderTrailService::storeByBatch($batch, BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR);
        if ($date != $tour['execution_date']) {
            OrderTrailService::storeByBatch($batch, BaseConstService::ORDER_TRAIL_UPDATE, $trackingOrderList[0]);
        }
    }

    /**
     * 批量分配站点到线路任务
     * @param $idList
     * @param $params
     * @return string
     * @throws BusinessLogicException
     */
    public function assignListToTour($idList, $params)
    {
        if (empty($params['tour_no'])) {
            throw new BusinessLogicException('线路编号是必须的');
        }
        $tour = parent::getInfo(['tour_no' => $params['tour_no']], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $params['merchant_id'] = $tour->merchant_id;
        $idList = explode_id_string($idList, ',');
        foreach ($idList as $id) {
            $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
            $this->assignBatchToTour($info, $params);
            if ($info['execution_date'] != $tour['execution_date']) {
                OrderTrailService::storeByBatch($info, BaseConstService::ORDER_TRAIL_UPDATE, $info);
            }
        }
        return 'true';
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
     * 填充站点信息和线路任务信息
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
            throw new BusinessLogicException('站点加入线路任务失败，请重新操作');
        }
        $batch = array_merge($batch, $data);
    }

    /**
     * 从线路任务移除站点
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function removeFromTour($id)
    {
        $info = $this->getInfoOfStatus(['id' => $id], true, [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED], true);
        if (empty($info['tour_no'])) {
            return 'true';
        }
        //修改站点
        $rowCount = parent::updateById($id, ['line_id' => null, 'line_name' => '', 'tour_no' => '', 'driver_id' => null, 'driver_name' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::BATCH_WAIT_ASSIGN]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //修改运单
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $info['batch_no']], ['line_id' => null, 'line_name' => '', 'tour_no' => '', 'driver_id' => null, 'driver_name' => '', 'car_id' => null, 'car_no' => null, 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //修改运单材料
        $rowCount = $this->getTrackingOrderPackageService()->update(['batch_no' => $info['batch_no']], ['tour_no' => '']);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //修改运单包裹
        $rowCount = $this->getTrackingOrderMaterialService()->update(['batch_no' => $info['batch_no']], ['tour_no' => '', 'status' => BaseConstService::TRACKING_ORDER_STATUS_1]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        //将站点从线路任务移除
        $this->getTourService()->removeBatch($info);
        //重新统计线路任务金额
        !empty($info['tour_no']) && $this->getTourService()->reCountAmountByNo($info['tour_no']);

        TrackingOrderTrailService::storeByBatch($info, BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR);
        return 'true';
    }

    /**
     * 批量删除
     * @param $idList
     * @return mixed
     * @throws BusinessLogicException
     */
    public function removeListFromTour($idList)
    {
        $idList = explode_id_string($idList, ',');
        foreach ($idList as $id) {
            $this->removeFromTour($id);
        }
        return 'true';
    }

    /**
     * 通过运单获得可选日期
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
        $data = $this->getBaseLineService()->getScheduleList($params, BaseConstService::TRACKING_ORDER_OR_BATCH_2);
        return $data;
    }

    /**
     * 通过线路获得可选日期
     * @param $id
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function getLineDate($id, $data)
    {
        $params = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($params)) {
            throw new BusinessLogicException('数据不存在');
        }
        $data = $this->getBaseLineService()->getScheduleListByLine($params, $data['line_id']);
        return $data;
    }

    /**
     * 获取可选线路
     * @return BaseLineService|array|mixed
     */
    public function getLineList()
    {
        $list = $this->getLineService()->query->where('rule', '=', CompanyTrait::getLineRule())->paginate();
        return $list ?? [];
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
        $rowCount = parent::update(['batch_no' => $batchNo], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

    public function getAdditionalPackageList($batchNo)
    {
        $this->query->whereIn('batch_no', $batchNo)->orderByDesc('updated_at');
        $info = $this->getPageList();
        return $info;
    }


    /**
     * 更新站点排序
     * @param $tourNo
     * @return string
     * @throws BusinessLogicException
     */
    public function updateBatchSort($tourNo)
    {
        $batchList = parent::getList(['tour_no' => $tourNo], ['id', 'batch_no', 'sort_id', 'status'], false, [], ['is_skipped' => 'desc', 'sort_id' => 'asc'])->toArray();
        if (empty($batchList)) return 'true';
        $nextBatchNo = null;
        $first = false;
        foreach ($batchList as $key => $batch) {
            if (!$first && in_array($batch['status'], [
                    BaseConstService::BATCH_WAIT_ASSIGN,
                    BaseConstService::BATCH_WAIT_OUT,
                    BaseConstService::BATCH_DELIVERING,
                    BaseConstService::BATCH_ASSIGNED
                ])) {
                $nextBatchNo = $batch['batch_no'];
                $first = true;
            }
            $rowCount = parent::update(['id' => $batch['id']], ['sort_id' => $key + 1]);
            if ($rowCount === false) {
                throw new BusinessLogicException('站点顺序调整失败,请重新操作');
            }
        }
        if (empty($nextBatchNo)) return 'true';
        dispatch(new UpdateLineCountTime(Tour::where('tour_no', $tourNo)->firstOrFail(), $nextBatchNo));
        return 'true';
    }

}
