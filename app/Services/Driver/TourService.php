<?php
/**
 * 线路任务 服务
 * User: long
 * Date: 2019/12/30
 * Time: 11:55
 */

namespace App\Services\Driver;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\TourBatchResource;
use App\Models\AdditionalPackage;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Models\TourMaterial;
use App\Models\TrackingOrder;
use App\Services\BaseConstService;
use App\Services\FeeService;
use App\Services\OrderTrailService;
use App\Services\PackageTrailService;
use App\Services\TrackingOrderTrailService;
use App\Traits\ConstTranslateTrait;
use App\Traits\TourRedisLockTrait;
use App\Traits\TourTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class TourService
 * @package App\Services\Driver
 * @property  TourMaterial $tourMaterialModel
 * 线路任务流程
 * 1.开始装货 线路任务状态 已分配-待出库
 * 2.出网点   线路任务状态 待出库-取派中
 * 3.确认出库 线路任务智能调度
 * 4.到达站点 线路任务状态 取派中  有三种情况:1-签收 2-异常上报 3-取消取派
 * 5.回网点   线路任务状态 取派中-已完成
 */
class TourService extends BaseService
{
    use TourRedisLockTrait;

    private $tourMaterialModel;

    public function __construct(Tour $tour, TourMaterial $tourMaterial)
    {
        parent::__construct($tour);
        $this->tourMaterialModel = $tourMaterial;
    }

    /**
     * 锁定-开始装货
     * @param $id
     * @throws BusinessLogicException
     */
    public function lock($id)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_2) {
            throw new BusinessLogicException('线路任务当前状态不允许装货');
        }
        if (!empty($this->getInfo(['driver_id' => auth()->user()->id, 'status' => ['=', BaseConstService::TOUR_STATUS_4]], ['*'], false))) {
            throw new BusinessLogicException('同时只能进行一个任务，请先完成其他取派中的任务');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('线路任务待分配车辆，请先分配车辆');
        }
        //线路任务 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_ASSIGNED], ['status' => BaseConstService::BATCH_WAIT_OUT]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点锁定失败，请重新操作');
        }
        //运单 处理
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_2], ['status' => BaseConstService::TRACKING_ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单锁定失败，请重新操作');
        }
        //运单包裹 处理
        $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_2], ['status' => BaseConstService::TRACKING_ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单锁定失败，请重新操作');
        }
        TrackingOrderTrailService::storeByTour($tour, BaseConstService::TRACKING_ORDER_TRAIL_LOCK);
        OrderTrailService::storeByTour($tour, BaseConstService::ORDER_TRAIL_LOCK);
    }

    /**
     * 取消锁定-将状态改为已分配
     * @param $id
     * @throws BusinessLogicException
     */
    public function unlock($id)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_3) {
            throw new BusinessLogicException('线路任务当前状态不允许取消锁定');
        }
        //线路任务 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务取消锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败，请重新操作');
        }
        //运单 处理
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单取消锁定失败，请重新操作');
        }
        //运单 处理
        $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('运单取消锁定失败，请重新操作');
        }
        TrackingOrderTrailService::storeByTour($tour, BaseConstService::TRACKING_ORDER_TRAIL_UN_LOCK);
        OrderTrailService::storeByTour($tour, BaseConstService::ORDER_TRAIL_UNLOCK);
    }


    /**
     * 备注
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function remark($id, $params)
    {
        $rowCount = parent::updateById($id, ['remark' => $params['remark']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('备注失败，请重新操作');
        }
    }

    /**
     * 修改车辆
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function changeCar($id, $params)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        if (!in_array($tour['status'], [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])) {
            throw new BusinessLogicException('线路任务当前状态不允许分配车辆');
        }
        //查看当前车辆是否已被分配给其他线路任务(由于model会自动加上driver_id条件,所以此处不用model)
        $otherTour = DB::table('tour')
            ->where('company_id', '<>', auth()->user()->company_id)
            ->where('id', '<>', $id)
            ->where('car_id', '=', $params['car_id'])
            ->where('execution_date', '=', $tour['execution_date'])
            ->where('status', '<>', BaseConstService::TOUR_STATUS_5)
            ->first();
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前车辆已被分配，请选择其他车辆');
        }
        //获取车辆
        $car = $this->getCarService()->getInfo(['id' => $params['car_id'], 'is_locked' => BaseConstService::CAR_TO_NORMAL], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('车辆不存在');
        }
        //分配
        $car = $car->toArray();
        if ($car['is_locked'] == BaseConstService::CAR_TO_LOCK) {
            throw new BusinessLogicException('车辆已被锁定');
        }
        $rowCount = $this->updateTourAll($tour, ['car_id' => $car['id'], 'car_no' => $car['car_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆分配失败，请重新操作');
        }
    }

    /**
     * 修改线路任务-站点-订单
     * @param $tour
     * @param $data
     * @return bool
     */
    private function updateTourAll($tour, $data)
    {
        //线路任务
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) return false;
        //站点
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;
        //运单
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;

        return true;
    }

    /**
     * 出库
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function outWarehouse($id, $params)
    {
        $tour = $this->checkOutWarehouse($id, $params);
        $params = Arr::only($params, ['material_list', 'cancel_tracking_order_id_list', 'begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic']);
        $params = Arr::add($params, 'status', BaseConstService::TOUR_STATUS_4);
        //线路任务更换状态
        $rowCount = parent::updateById($id, $params);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //站点更换状态
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_DELIVERING]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //取消取派订单和包裹
        $cancelTrackingOrderList = $cancelTrackingOrderBatchNoList = [];
        if (!empty($params['cancel_tracking_order_id_list'])) {
            $cancelTrackingOrderIdList = explode_id_string($params['cancel_tracking_order_id_list'], ',');
            if (!empty($cancelTrackingOrderIdList)) {
                $cancelTrackingOrderList = $this->getTrackingOrderService()->getList(['id' => ['in', $cancelTrackingOrderIdList]], ['*'], false)->toArray();
                //更换运单包裹状态
                $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no'], 'tracking_order_no' => ['in', array_column($cancelTrackingOrderList, 'tracking_order_no')], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6, 'batch_no' => '', 'tour_no' => '']);
                if ($rowCount === false) {
                    throw new BusinessLogicException('出库失败');
                }
                //如果运单中还存在材料要派送,则派送
                foreach ($cancelTrackingOrderList as $key => $cancelTrackingOrder) {
                    $where = ['tour_no' => $tour['tour_no'], 'tracking_order_no' => $cancelTrackingOrder['tracking_order_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3];
                    $materialQuantity = $this->getTrackingOrderMaterialService()->sum('expect_quantity', ['tour_no' => $tour['tour_no'], 'tracking_order_no' => $cancelTrackingOrder['tracking_order_no']]);
                    if ($materialQuantity == 0) {
                        $rowCount = $this->getTrackingOrderService()->update($where, ['status' => BaseConstService::TRACKING_ORDER_STATUS_6, 'batch_no' => '', 'tour_no' => '']);
                        if ($rowCount === false) {
                            throw new BusinessLogicException('出库失败');
                        }
                        $cancelTrackingOrderBatchNoList[] = $cancelTrackingOrder['batch_no'];
                    } else {
                        unset($cancelTrackingOrderList[$key]);
                    }
                }
            }
        }
        //运单更换状态
        $rowCount = $this->getTrackingOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //更换包裹状态
        $rowCount = $this->getTrackingOrderPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3], ['status' => BaseConstService::TRACKING_ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //插入线路任务材料
        !empty($params['material_list']) && $this->insertMaterialList($tour, $params['material_list']);

        //若站点下所有运单都取消了，就取消取派站点
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no'], 'batch_no' => ['in', array_filter(array_unique($cancelTrackingOrderBatchNoList))]], ['batch_no', 'id'], false)->toArray();
        foreach ($batchList as $batch) {
            $order = $this->getTrackingOrderService()->getInfo(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['id'], false);
            if (!empty($order)) continue;
            //若在出库前，没有订单派送，则删除站点
            $rowCount = $this->getBatchService()->delete(['tour_no' => $tour['tour_no'], 'id' => $batch['id']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('出库失败');
            }
        }
        //重新统计线路任务金额
        $this->reCountAmountByNo($tour['tour_no']);
        $newCancelTrackingOrderList = $this->getTrackingOrderService()->getList(['tracking_order_no' => ['in', array_column(array_values($cancelTrackingOrderList), 'tracking_order_no')]], ['*'], false)->toArray();
        $cancelTrackingOrderList = array_create_index($cancelTrackingOrderList, 'tracking_order_no');
        foreach ($newCancelTrackingOrderList as &$newCancelTrackingOrder) {
            $newCancelTrackingOrder['batch_no'] = $cancelTrackingOrderList[$newCancelTrackingOrder['tracking_order_no']]['batch_no'] ?? '';
            $newCancelTrackingOrder['tour_no'] = $cancelTrackingOrderList[$newCancelTrackingOrder['tracking_order_no']]['tour_no'] ?? '';
        }
        $this->getOrderService()->outWarehouse($tour['tour_no'], $newCancelTrackingOrderList);
        $outPackageList = $this->getTrackingOrderPackageService()->getList(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['*'], false)->toArray();
        //包裹出库
        $this->getStockService()->outWarehouse($outPackageList, $tour);
        return [$tour, $newCancelTrackingOrderList];
    }


    /**
     * 确认出库
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function actualOutWarehouse($id, $params)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false)->toArray();
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no'], 'status' => ['in', [BaseConstService::BATCH_CHECKOUT]]], ['*'], false)->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4 || !empty($batchList)) {
            throw new BusinessLogicException('状态错误');
        }
        $car = $this->getCarService()->getInfo(['car_no' => $tour['car_no']], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('司机不存在');
        }
        /*        if ($params['begin_distance'] < $car['distance']) {
                    throw new BusinessLogicException('出库里程数小于该车上次入库里程数，请重新填写');
                }*/
        $row = $this->getCarService()->update(['car_no' => $tour['car_no']], ['distance' => $params['begin_distance']]);
        if ($row == false) {
            throw new BusinessLogicException('车辆里程记录失败，请重试');
        }
        $row = parent::updateById($id, ['actual_out_status' => BaseConstService::YES, 'begin_distance' => $params['begin_distance'] * 1000, 'begin_time' => now()]);
        if ($row == false) {
            throw new BusinessLogicException('实际出库失败');
        }
    }

    /**
     * 批量新增取件材料
     * @param $tour
     * @param $materialList
     * @throws BusinessLogicException
     */
    private function insertMaterialList($tour, $materialList)
    {
        data_fill($materialList, '*.name', '');
        $materialList = collect($materialList)->map(function ($material, $key) use ($tour) {
            $material = Arr::only($material, ['expect_quantity', 'actual_quantity', 'code', 'name']);
            $material = Arr::add($material, 'tour_no', $tour['tour_no']);
            $material = Arr::add($material, 'surplus_quantity', $material['actual_quantity']);
            return collect($material);
        })->toArray();
        $rowCount = $this->tourMaterialModel->insertAll($materialList);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败');
        }
    }


    /**
     * 验证-出库
     * @param $id
     * @param $params
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function checkOutWarehouse($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_3) {
            throw new BusinessLogicException('线路任务当前状态不允许出库');
        }
        if (!empty($this->getInfo(['driver_id' => auth()->user()->id, 'status' => ['=', BaseConstService::TOUR_STATUS_4]], ['*'], false))) {
            throw new BusinessLogicException('同时只能进行一个任务，请先完成其他取派中的任务');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('线路任务待分配车辆，请先分配车辆');
        }
        //存在出库订单,则验证
        if (!empty($params['out_tracking_order_id_list'])) {
            //验证订单是否都可出库
            $outTrackingOrderIdList = explode_id_string($params['out_tracking_order_id_list']);
            $noOutTrackingOrderList = $this->getTrackingOrderService()->getList(['id' => ['in', $outTrackingOrderIdList], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2, 'status' => ['<>', BaseConstService::TRACKING_ORDER_STATUS_3]], ['order_no', 'tracking_order_no'], false);
            if ($noOutTrackingOrderList->isNotEmpty()) {
                Log::channel('info')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'noOutTrackingOrderList', $noOutTrackingOrderList);
                throw new BusinessLogicException('订单已取消或已删除，不能出库，请先剔除', 5006, [], $noOutTrackingOrderList);
            }
        }
        //验证运单数量
        $trackingOrderCount = $this->getTrackingOrderService()->count(['tour_no' => $tour['tour_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2]);
        if ($trackingOrderCount != $params['tracking_order_count']) {
            throw new BusinessLogicException($trackingOrderCount, 5002);
        }
        $expectMaterialList = $this->getTourTaskService()->getTourMaterialList($tour);
        empty($params['material_list']) && $params['material_list'] = [];
        if (!empty($expectMaterialList) && (count($expectMaterialList) != count($params['material_list']))) {
            throw new BusinessLogicException('有未取材料，请刷新页面');
        }
        //材料验证
        if (!empty($params['material_list'])) {
            $materialList = $params['material_list'];
            $codeList = array_column($materialList, 'code');
            if (count($codeList) != count(array_unique($codeList))) {
                throw new BusinessLogicException('材料有重复，请先合并');
            }
            //验证材料数量
            foreach ($materialList as $v) {
                $expectQuantity = collect($expectMaterialList)->where('code', $v['code'])->first();
                if (empty($expectQuantity)) {
                    throw new BusinessLogicException('材料种类不正确', 5004);
                }
                if ($expectQuantity['expect_quantity'] != $v['expect_quantity']) {
                    throw new BusinessLogicException('材料种类不正确', 5004);
                }
                if (intval($v['actual_quantity']) > intval($expectQuantity['expect_quantity'])) {
                    throw new BusinessLogicException('当前线路任务的材料数量不正确');
                }
            }
        }
        //判断是否存在不可出库且待出库的订单
        $disableWhere = ['tour_no' => $tour['tour_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_3, 'out_status' => BaseConstService::OUT_STATUS_2];
        if (!empty($params['cancel_tracking_order_id_list'])) {
            $disableWhere['id'] = ['not in', explode_id_string($params['cancel_tracking_order_id_list'], ',')];
        }
        $disableOutTrackingOrder = $this->getTrackingOrderService()->getInfo($disableWhere, ['id', 'order_no', 'tracking_order_no'], false);
        if (!empty($disableOutTrackingOrder)) {
            throw new BusinessLogicException('订单为[:order_no],运单为[:tracking_order_no]不可出库', 1000, ['order_no' => $disableOutTrackingOrder->order_no, 'tracking_order_no' => $disableOutTrackingOrder->tracking_order_no]);
        }
        return $tour;
    }


    /**
     * 线路任务中的站点列表
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchList($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $batchList = Batch::query()->where('tour_no', $tour['tour_no'])->whereIn('status', [BaseConstService::BATCH_CANCEL, BaseConstService::BATCH_CHECKOUT])->orderBy('actual_arrive_time')->get()->toArray();
        $ingBatchList = Batch::query()->where('tour_no', $tour['tour_no'])->whereNotIn('status', [BaseConstService::BATCH_CANCEL, BaseConstService::BATCH_CHECKOUT])->orderBy('sort_id')->get()->toArray();
        $batchList = array_merge($batchList, $ingBatchList);
        $packageList = $this->getTrackingOrderPackageService()->getList(['tour_no' => $tour['tour_no']], ['batch_no', 'type', DB::raw('COUNT(`expect_quantity`) as expect_quantity'), DB::raw('SUM(`actual_quantity`) as actual_quantity')], false, ['batch_no', 'type'])->toArray();
        $packageList = collect($packageList)->groupBy('batch_no')->map(function ($itemPackageList) {
            return collect($itemPackageList)->keyBy('type');
        })->toArray();
        $batchList = array_map(function ($batch) use ($packageList) {
            $batch['expect_pickup_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_1]['expect_quantity'] ?? "0";
            $batch['actual_pickup_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_1]['actual_quantity'] ?? "0";
            $batch['expect_pie_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_2]['expect_quantity'] ?? "0";
            $batch['actual_pie_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_2]['actual_quantity'] ?? "0";
            $batch['receiver_lon'] = $batch['place_lon'];
            $batch['receiver_lat'] = $batch['place_lat'];
            return $batch;
        }, $batchList);
        $tour['batch_count'] = count($batchList);
        $tour['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_CHECKOUT]);
        $tour['batch_list'] = $batchList;
        //获取延迟次数
        $tour['total_delay_amount'] = $this->getTourDelayService()->count(['tour_no' => $tour['tour_no']]);
        //获取延时时间
        $tour['total_delay_time'] = intval($this->getTourDelayService()->sum('delay_time', ['tour_no' => $tour['tour_no']]));
        $tour['total_delay_time_human'] = round($tour['total_delay_time'] / 60) . __('分钟');
        return TourBatchResource::make($tour)->toArray(request());
    }

    /**
     * 达到时-获取站点的运单列表
     * @param $id
     * @param $params
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchTrackingOrderList($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        //获取所有订单列表
        $trackingOrderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'execution_date', 'type', 'mask_code', 'batch_no', 'order_no', 'status'], false)->toArray();
        //$orderList = array_create_group_index($orderList, 'type');
        //获取所有包裹列表
        $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        //获取所有材料列表
        $materialList = $this->getTrackingOrderMaterialService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $batch['tracking_order_list'] = $trackingOrderList;
        $batch['package_list'] = $packageList;
        $batch['material_list'] = $materialList;
        return $batch;
    }


    /**
     * 站点到达 主要处理到达时间和里程
     * @param $id
     * @param $params
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function batchArrive($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        if ($batch['status'] !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('当前站点为[:status],无法进行此操作', 1000, ['status' => ConstTranslateTrait::$batchStatusList[$batch['status']]]);
        }
        $line = $this->getLineService()->getInfo(['id' => $tour['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('线路已删除，请联系管理员');
        }
        if ($line->can_skip_batch == BaseConstService::CAN_NOT_SKIP_BATCH) {
            $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no'], 'sort_id' => ['<', $batch['sort_id']], 'status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
            if ($batchList->isNotEmpty()) {
                throw new BusinessLogicException('请按优化的站点顺序进行派送，或手动跳过之前的站点');
            }
        }
        if ($tour['actual_out_status'] == BaseConstService::NO) {
            throw new BusinessLogicException('请先确认出库');
        }
        $now = now();
        //查找当前线路任务中最新完成的站点
        $lastCompleteBatch = $this->getBatchService()->getInfo(['tour_no' => $tour['tour_no'], 'status' => ['in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]], ['actual_arrive_time', 'sign_time'], false, ['sign_time' => 'desc']);
        if (!empty($lastCompleteBatch) && !empty($lastCompleteBatch->sign_time)) {
            $actualTime = strtotime($now) - strtotime($lastCompleteBatch->sign_time);
        } else {
            $actualTime = strtotime($now) - strtotime($tour['begin_time']);
        }
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['actual_arrive_time' => $now, 'actual_time' => $actualTime, 'actual_distance' => $batch['expect_distance']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('线路任务更新失败，请稍后重试');
        }
        TourTrait::afterBatchArrived($tour, $batch);
        $specialRemarkList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no'], 'special_remark' => ['<>', null]], ['id', 'tracking_order_no', 'order_no', 'special_remark'], false);
        return $specialRemarkList;
    }


    /**
     * 到达后-站点详情
     * @param $id
     * @param $params
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchInfo($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $trackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'tracking_order_no', 'out_user_id', 'order_no', 'mask_code', 'type', 'batch_no', 'status'], false);
        $trackingOrderList = collect($trackingOrderList)->map(function ($trackingOrder, $key) {
            /**@var TrackingOrder $trackingOrder */
            return collect(Arr::add($trackingOrder->toArray(), 'status_name', $trackingOrder->status_name));
        })->toArray();
        //获取所有包裹列表
        $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        for ($i = 0, $j = count($packageList); $i < $j; $i++) {
            $packageList[$i]['feature_logo'] = $packageList[$i]['feature_logo'] ?? '';
        }
        $authPackage = collect($packageList)->first(function ($package) {
            return (intval($package['status']) == BaseConstService::BATCH_DELIVERING) && (intval($package['is_auth']) == BaseConstService::IS_AUTH_1);
        });
        $batch['is_auth'] = !empty($authPackage) ? BaseConstService::IS_AUTH_1 : BaseConstService::IS_AUTH_2;
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $trackingOrderList = array_map(function ($order) use ($packageList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            return $order;
        }, $trackingOrderList);
        //获取站点中过所有材料
        $materialList = $this->getTrackingOrderMaterialService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $batch['tour_id'] = $tour['id'];
        $batch['actual_total_amount'] = number_format_simple(round($batch['sticker_amount'] + $batch['delivery_amount'] + $batch['actual_replace_amount'] + $batch['actual_settlement_amount'], 2), 2);
        if ($batch['sticker_amount'] + $batch['sticker_amount'] + $batch['settlement_amount'] + $batch['delivery_amount'] == 0) {
            $batch['no_need_to_pay'] = BaseConstService::YES;
        } else {
            $batch['no_need_to_pay'] = BaseConstService::NO;
        }
        $additionalPackageList = AdditionalPackage::query()->where('batch_no', $batch['batch_no'])->get();
        $batch['additional_package_count'] = count($additionalPackageList);
        $batch['additional_package_list'] = $additionalPackageList ?? [];
        $batch['tracking_order_list'] = $trackingOrderList;
        $batch['material_list'] = $materialList;
        return $batch;
    }

    /**
     * 站点异常上报
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchException($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('线路任务当前状态不允许上报异常');
        }
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能上报异常');
        }
        //生成站点异常
        $batchExceptionNo = $this->getOrderNoRuleService()->createBatchExceptionNo();
        $data = [
            'batch_exception_no' => $batchExceptionNo,
            'batch_no' => $batch['batch_no'],
            'fullname' => $batch['place_fullname'],
            'source' => '司机来源',
            'stage' => $params['stage'],
            'type' => $params['type'],
            'remark' => $params['exception_remark'],
            'picture' => $params['picture'],
            'driver_name' => auth()->user()->fullname
        ];
        $rowCount = $this->getBatchExceptionService()->create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常上报失败，请重新操作');
        }
        //站点异常
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常上报失败，请重新操作');
        }
        //运单异常
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $batch['batch_no']], ['exception_label' => BaseConstService::BATCH_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('异常上报失败，请重新操作');
        }
    }


    /**
     * 站点取消取派
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function batchCancel($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('线路任务当前状态不允许站点取消取派');
        }
        //异常站点和取派中的站点都可以取消取派
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能取消取派');
        }
        //站点取消取派
        $params['sign_time'] = now();
        $data = Arr::only($params, ['cancel_type', 'cancel_remark', 'cancel_picture', 'sign_time']);
        $rowCount = $this->getBatchService()->updateById($batch['id'], Arr::add($data, 'status', BaseConstService::BATCH_CANCEL));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //运单取消取派
        $cancelTrackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['*'], false)->toArray();
        $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], Arr::add($data, 'status', BaseConstService::TRACKING_ORDER_STATUS_6));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //包裹取消取派
        $rowCount = $this->getTrackingOrderPackageService()->update(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //包裹轨迹
        $packageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $batch['batch_no']], ['*'], false);
        $pickupPackageList = $packageList->where('type', BaseConstService::PACKAGE_TYPE_1);
        if ($pickupPackageList->isNotEmpty()) {
            PackageTrailService::storeByTrackingOrderList($pickupPackageList->toArray(), BaseConstService::PACKAGE_TRAIL_PICKUP_CANCEL, array_merge($batch, $data));
        }
        $piePackageList = $packageList->where('type', BaseConstService::PACKAGE_TYPE_2);
        if ($piePackageList->isNotEmpty()) {
            PackageTrailService::storeByTrackingOrderList($piePackageList->toArray(), BaseConstService::PACKAGE_TRAIL_PIE_CANCEL, array_merge($batch, $data));
        }
        $this->getOrderService()->batchCancel($batch['batch_no']);
        return [$tour, $batch, $cancelTrackingOrderList];
    }

    /**
     * 验证签收
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function checkBatchSign($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        $stickerAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::STICKER]);
        $deliveryAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::DELIVERY]);
        //贴单费统计
        $packageList = collect($params['package_list'])->unique('id')->keyBy('id')->toArray();
        $packageIdList = array_keys($packageList);
        $totalStickerAmount = $totalDeliveryAmount = 0.00;
        $packageList = $this->getTrackingOrderPackageService()->getList(['id' => ['in', $packageIdList], 'batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['id', 'order_no', 'batch_no', 'type'], false)->toArray();
        foreach ($packageList as $packageId => $package) {
            if ((intval($package['type']) == BaseConstService::TRACKING_ORDER_TYPE_1) && !empty($params['package_list'][$packageId]['sticker_no'])) {
                $totalStickerAmount += $stickerAmount;
            }
            if ((intval($package['type']) == BaseConstService::TRACKING_ORDER_TYPE_1) && !empty($params['package_list'][$packageId]['delivery_charge']) && $params['package_list'][$packageId]['delivery_charge'] == BaseConstService::YES) {
                $totalDeliveryAmount += $deliveryAmount;
            }
        }
        $orderNoList = array_unique(array_column($packageList, 'order_no'));
        //代收货款统计
        $totalReplaceAmount = $this->getOrderService()->sum('replace_amount', ['order_no' => ['in', $orderNoList]]);
        //运费统计
        $totalSettlementAmount = $this->getOrderService()->sum('settlement_amount', ['order_no' => ['in', $orderNoList]]);
        return [
            'total_sticker_amount' => number_format_simple($totalStickerAmount, 2),
            'total_delivery_amount' => number_format_simple($totalDeliveryAmount, 2),
            'total_replace_amount' => number_format_simple($totalReplaceAmount, 2),
            'total_settlement_amount' => number_format_simple($totalSettlementAmount, 2),
            'total_amount' => number_format_simple($totalStickerAmount + $totalReplaceAmount + $totalSettlementAmount + $totalDeliveryAmount, 2),
        ];
    }

    /**
     * 站点签收
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function batchSign($id, $params)
    {
        list($tour, $batch, $dbMaterialList) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('线路任务当前状态不允许站点签收');
        }
        //异常站点和取派中的站点都可以签收
        if (intval($batch['status'] !== BaseConstService::BATCH_DELIVERING)) {
            throw new BusinessLogicException('站点当前状态不能签收');
        }
        if (!empty($params['additional_package_list']) && intval($params['pay_type']) == BaseConstService::BATCH_PAY_TYPE_4) {
            foreach ($params['additional_package_list'] as $v) {
                if ($v['sticker_no'] !== '' || $v['delivery_charge'] == BaseConstService::YES) {
                    throw new BusinessLogicException('顺带包裹费用不为0，不能选择无需支付');
                }
            }
        }
        if (intval($params['pay_type']) == BaseConstService::BATCH_PAY_TYPE_4 && (intval($params['total_sticker_amount']) !== 0 || intval($params['total_replace_amount']) !== 0 || intval($params['total_settlement_amount']) !== 0 || intval($params['total_delivery_amount']) !== 0)) {
            throw new BusinessLogicException('费用不为0，不能选择无需支付');
        }
        $line = $this->getLineService()->getInfo(['id' => $tour['line_id']], ['*'], false);
        if (empty($line)) {
            throw new BusinessLogicException('线路已删除，请联系管理员');
        }
        if ($batch['is_skipped'] == BaseConstService::IS_SKIPPED) {
            throw new BusinessLogicException('此站点已被跳过，请先恢复站点');
        }
        /*******************************************1.处理站点下的材料*************************************************/
        !empty($params['material_list']) && $this->dealMaterialList($tour, $params['material_list'], $dbMaterialList);
        /*******************************************2.处理站点下的包裹*************************************************/
        $info = $this->dealPackageList($batch, $params);
        $totalStickerAmount = $info['totalStickerAmount'];
        $orderStickerAmountList = $info['orderStickerAmount'];
        $totalDeliveryAmount = $info['totalDeliveryAmount'];
        $orderDeliveryAmountList = $info['orderDeliveryAmount'];
        /****************************************2.处理站点下的顺带包裹************************************************/
        !empty($params['additional_package_list']) && $this->dealAdditionalPackageList($batch, $params['additional_package_list']);
        /****************************************2.处理站点下的所有订单************************************************/
        $pickupCount = $pieCount = 0;
        $signTrackingOrderList = $cancelTrackingOrderList = [];
        $dbTrackingOrderList = $this->getTrackingOrderService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['*'], false)->toArray();
        foreach ($dbTrackingOrderList as $dbTrackingOrder) {
            //若不存在取派完成的包裹,则认为运单取派失败
            $packageCount = $this->getTrackingOrderPackageService()->count(['tracking_order_no' => $dbTrackingOrder['tracking_order_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_5]);
            if ($packageCount == 0) {
                $cancelTrackingOrderList[] = $dbTrackingOrder;
                continue;
            }
            //若存在取派完成的包裹,则认为订单取派完成;
            if (intval($dbTrackingOrder['type']) === BaseConstService::ORDER_TYPE_1) {
                $pickupCount += 1;
            } else {
                $pieCount += 1;
            }
            $signTrackingOrderList[] = $dbTrackingOrder;
            //更新订单贴单费及提货费
            $rowCount = $this->getOrderService()->update(['order_no' => $dbTrackingOrder['order_no']], [
                'sticker_amount' => $orderStickerAmountList[$dbTrackingOrder['order_no']] ?? 0.00,
                'delivery_amount' => $orderDeliveryAmountList[$dbTrackingOrder['order_no']] ?? 0.00
            ]);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        foreach ($cancelTrackingOrderList as $key => $cancelTrackingOrder) {
            $material = $this->getTrackingOrderMaterialService()->getInfo(['tracking_order_no' => $cancelTrackingOrder['tracking_order_no'], 'actual_quantity' => ['>', 0]], ['id'], false);
            if (!empty($material)) {
                $signTrackingOrderList[] = $cancelTrackingOrder;
                unset($cancelTrackingOrderList[$key]);
            }
        }
//        $totalAmount = [
//            'sticker_amount' => $totalStickerAmount,
//            'delivery_amount' => $totalDeliveryAmount,
//            'actual_replace_amount' => array_sum(array_column($signTrackingOrderList, 'replace_amount')),
//            'actual_settlement_amount' => array_sum(array_column($signTrackingOrderList, 'settlement_amount'))
//        ];
        //金额验证
        //$this->checkBatchSignAmount($params, $totalAmount);
        //签收成功运单
        if (!empty($signTrackingOrderList)) {
            $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $batch['batch_no'], 'tracking_order_no' => ['in', array_column($signTrackingOrderList, 'tracking_order_no')], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['status' => BaseConstService::TRACKING_ORDER_STATUS_5]);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        //签收失败运单
        if (!empty($cancelTrackingOrderList)) {
            $rowCount = $this->getTrackingOrderService()->update(['batch_no' => $batch['batch_no'], 'tracking_order_no' => ['in', array_column($cancelTrackingOrderList, 'tracking_order_no')], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['status' => BaseConstService::TRACKING_ORDER_STATUS_6]);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        /********************************************3.处理站点********************************************************/
        $batchData = [
            'status' => BaseConstService::BATCH_CHECKOUT,
            'actual_pickup_quantity' => $pickupCount,
            'actual_pie_quantity' => $pieCount,
            'signature' => $params['signature'],
            'sign_time' => now(),
            'pay_type' => $params['pay_type'],
            'pay_picture' => $params['pay_picture'],
            'sticker_amount' => $totalStickerAmount,
            'delivery_amount' => $totalDeliveryAmount
        ];
        $rowCount = $this->getBatchService()->updateById($batch['id'], array_merge($batchData, ['auth_fullname' => $params['auth_fullname'] ?? '', 'auth_birth_date' => !empty($params['auth_birth_date']) ? $params['auth_birth_date'] : null]));
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        /*****************************************4.更新线路任务信息***************************************************/
        $tourData = [
            'actual_pickup_quantity' => intval($tour['actual_pickup_quantity']) + $pickupCount,
            'actual_pie_quantity' => intval($tour['actual_pie_quantity']) + $pieCount,
            'sticker_amount' => $tour['sticker_amount'] + $totalStickerAmount,
            'delivery_amount' => $tour['delivery_amount'] + $totalDeliveryAmount
        ];
        $rowCount = parent::updateById($id, $tourData);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        //重新统计金额
        $this->reCountActualAmountByNo($tour['tour_no']);
        $batch = $this->getBatchService()->getInfo(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $additionalPackageList = $this->getAdditionalPackageService()->getList(['batch_no' => $batch['batch_no']], ['package_no', 'merchant_id'], false);
        if (!empty($additionalPackageList)) {
            $batch['additional_package_list'] = $additionalPackageList->toArray();
        } else {
            $batch['additional_package_list'] = [];
        }
        $this->getOrderService()->batchSign($batch['batch_no']);
        return [$tour, $batch];
    }

    /**
     * 验证签收金额
     * @param $params
     * @param $dbParams
     * @throws BusinessLogicException
     */
    private function checkBatchSignAmount($params, $dbParams)
    {
        //验证贴单费用
        if (bccomp($params['total_sticker_amount'], $dbParams['sticker_amount']) !== 0) {
            throw new BusinessLogicException('5001', 5001);
        }
        //验证提货费用
        if (intval($params['total_delivery_amount']) !== intval($dbParams['delivery_amount'])) {
            throw new BusinessLogicException('5003', 5003);
        }
        //验证代收货款
        if (bccomp($params['total_replace_amount'], $dbParams['actual_replace_amount']) !== 0) {
            throw new BusinessLogicException('总计代收货款不正确');
        }
        //验证结算费用(运费)
        if (bccomp($params['total_settlement_amount'], $dbParams['actual_settlement_amount']) !== 0) {
            throw new BusinessLogicException('总计运费不正确');
        }
    }

    /**
     * 处理签收时的材料
     * @param $tour
     * @param $materialList
     * @param $dbMaterialList
     * @throws BusinessLogicException
     */
    private function dealMaterialList($tour, $materialList, $dbMaterialList)
    {
        $dbMaterialList = array_create_index($dbMaterialList, 'id');
        foreach ($materialList as $material) {
            $actualQuantity = intval($material['actual_quantity']);
            $rowCount = $this->getTrackingOrderMaterialService()->update(['id' => $material['id']], ['actual_quantity' => $actualQuantity]);
            if ($rowCount === false) {
                throw new BusinessLogicException('材料处理失败，请重新操作');
            };
            $rowCount = $this->tourMaterialModel->newQuery()
                ->where('tour_no', '=', $tour['tour_no'])
                ->where('code', '=', $dbMaterialList[$material['id']]['code'])
                ->update(['finish_quantity' => DB::raw("finish_quantity+$actualQuantity"), 'surplus_quantity' => DB::raw("surplus_quantity-$actualQuantity")]);
            if ($rowCount === false) {
                throw new BusinessLogicException('材料处理失败，请重新操作');
            }
        }
    }

    /**
     * 处理签收时的包裹列表
     * @param $batch
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function dealPackageList($batch, $params)
    {
        $packageList = $params['package_list'] ?? [];
        $orderStickerAmount = $orderDeliveryAmount = [];
        $stickerAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::STICKER]);
        $deliveryAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::DELIVERY]);
        /***************************************2.处理站点下的所有包裹*************************************************/
        $packageList = collect($packageList)->unique('id')->keyBy('id')->toArray();
        $packageIdList = array_keys($packageList);
        $totalStickerAmount = $totalDeliveryAmount = 0.00;
        $dbPackageList = $this->getTrackingOrderPackageService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::TRACKING_ORDER_STATUS_4], ['*'], false)->toArray();
        $trackingOrderList = $this->getTrackingOrderService()->getList(['tracking_order_no' => ['in', collect($dbPackageList)->pluck('tracking_order_no')->toArray()]], ['*'], false);
        foreach ($dbPackageList as $dbPackage) {
            $trackingOrder = $trackingOrderList->where('tracking_order_no')->first();
            //判断是否签收
            if (in_array(intval($dbPackage['id']), $packageIdList)) {
                $packageData = ['status' => BaseConstService::TRACKING_ORDER_STATUS_5, 'auth_fullname' => $params['auth_fullname'] ?? '', 'auth_birth_date' => !empty($params['auth_birth_date']) ? $params['auth_birth_date'] : null];
                //判断取件或派件
                if (intval($dbPackage['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) {
                    //贴单费计算
                    if (!empty($packageList[$dbPackage['id']]['sticker_no'])) {
                        $totalStickerAmount += $stickerAmount;
                        //赋0
                        if (empty($orderStickerAmount[$dbPackage['order_no']])) {
                            $orderStickerAmount[$dbPackage['order_no']] = 0;
                        }
                        $orderStickerAmount[$dbPackage['order_no']] += $stickerAmount;
                        $packageStickerAmount = $stickerAmount;
                    } else {
                        $packageStickerAmount = 0.00;
                    }
                    //提货费计算
                    if ($packageList[$dbPackage['id']]['delivery_charge'] == BaseConstService::YES) {
                        $totalDeliveryAmount += $deliveryAmount;
                        //赋0
                        if (empty($orderDeliveryAmount[$dbPackage['order_no']])) {
                            $orderDeliveryAmount[$dbPackage['order_no']] = 0;
                        }
                        $orderDeliveryAmount[$dbPackage['order_no']] += $deliveryAmount;
                        $packageDeliveryAmount = $deliveryAmount;
                    } else {
                        $packageDeliveryAmount = 0.00;
                    }
                    $packageData = array_merge($packageData, ['actual_quantity' => 1, 'sticker_amount' => $packageStickerAmount, 'delivery_amount' => $packageDeliveryAmount, 'sticker_no' => $packageList[$dbPackage['id']]['sticker_no'] ?? '']);
                } else {
                    $packageData = array_merge($packageData, ['actual_quantity' => 1]);
                }
                if ($dbPackage['type'] == BaseConstService::TRACKING_ORDER_TYPE_1) {
                    PackageTrailService::storeByTrackingOrderList([$dbPackage], BaseConstService::PACKAGE_TRAIL_PICKUP_DONE, $trackingOrder);
                } else {
                    PackageTrailService::storeByTrackingOrderList([$dbPackage], BaseConstService::PACKAGE_TRAIL_PIE_DONE, $trackingOrder);
                }
            } else {
                $packageData = ['status' => BaseConstService::TRACKING_ORDER_STATUS_6];
                if ($dbPackage['type'] == BaseConstService::TRACKING_ORDER_TYPE_1) {
                    PackageTrailService::storeByTrackingOrderList([$dbPackage], BaseConstService::PACKAGE_TRAIL_PICKUP_CANCEL, $trackingOrder);
                } else {
                    PackageTrailService::storeByTrackingOrderList([$dbPackage], BaseConstService::PACKAGE_TRAIL_PIE_CANCEL, $trackingOrder);
                }
            }
            $rowCount = $this->getTrackingOrderPackageService()->update(['id' => $dbPackage['id']], $packageData);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        return [
            'totalStickerAmount' => $totalStickerAmount,
            'orderStickerAmount' => $orderStickerAmount,
            'totalDeliveryAmount' => $totalDeliveryAmount,
            'orderDeliveryAmount' => $orderDeliveryAmount,
        ];
    }

    /**
     * 处理顺带包裹列表
     * @param $batch
     * @param $params
     * @throws BusinessLogicException
     */
    public function dealAdditionalPackageList($batch, $params)
    {
        $stickerAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::STICKER]);
        $deliveryAmount = FeeService::getFeeAmount(['company_id' => auth()->user()->company_id, 'code' => BaseConstService::DELIVERY]);
        $merchantIDList = collect($params)->pluck('merchant_id')->toArray();
        $merchantList = $this->getMerchantService()->getList(['id' => ['in', $merchantIDList]], ['*'], false)->toArray();
        $data = [];
        $this->getPackageNoRuleService()->additionalCheck($params);
        foreach ($params as $k => $v) {
            $merchant = collect($merchantList)->where('id', $v['merchant_id'])->first();
            if (empty($merchant)) {
                throw new BusinessLogicException('货主不存在，无法顺带包裹');
            }
            if ($merchant['additional_status'] == BaseConstService::MERCHANT_ADDITIONAL_STATUS_2) {
                throw new BusinessLogicException('货主未开启顺带包裹服务');
            }
            if (!empty($v['sticker_no'])) {
                $data[$k]['sticker_amount'] = $stickerAmount;
            } else {
                $data[$k]['sticker_amount'] = 0;
            }
            $data[$k]['delivery_amount'] = $v['delivery_charge'] == BaseConstService::YES ? $deliveryAmount : 0;
            $data[$k]['merchant_id'] = $params[$k]['merchant_id'];
            $data[$k]['package_no'] = $params[$k]['package_no'];
            $data[$k]['sticker_no'] = $params[$k]['sticker_no'];
            $data[$k]['batch_no'] = $batch['batch_no'];
            $data[$k]['tour_no'] = $batch['tour_no'];
            $data[$k]['line_id'] = $batch['line_id'];
            $data[$k]['line_name'] = $batch['line_name'];
            $data[$k]['place_fullname'] = $batch['place_fullname'];
            $data[$k]['execution_date'] = $batch['execution_date'];
            $data[$k]['place_phone'] = $batch['place_phone'];
            $data[$k]['place_country'] = $batch['place_country'];
            $data[$k]['place_province'] = $batch['place_province'];
            $data[$k]['place_post_code'] = $batch['place_post_code'];
            $data[$k]['place_house_number'] = $batch['place_house_number'];
            $data[$k]['place_city'] = $batch['place_city'];
            $data[$k]['place_district'] = $batch['place_district'];
            $data[$k]['place_street'] = $batch['place_street'];
            $data[$k]['place_address'] = $batch['place_address'];
            $data[$k]['place_lon'] = $batch['place_lon'];
            $data[$k]['place_lat'] = $batch['place_lat'];
            $data[$k]['status'] = BaseConstService::ADDITIONAL_PACKAGE_STATUS_1;
        }
        $this->getAdditionalPackageService()->insertAll($data);
    }

    /**
     * 验证-站点
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function checkBatch($id, $params)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfo(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前线路任务');
        }
        if (!empty($params['additional_package_list']) && (!collect($params['additional_package_list'][0])->has('merchant_id') || !collect($params['additional_package_list'][0])->has('name'))) {
            throw new BusinessLogicException('顺带包裹格式不正确');
        }

        return [$tour, $batch];
    }

    /**
     * 验证-站点并锁定数据
     * @param $id
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    private function checkBatchLock($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfoLock(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前线路任务');
        }
        //验证材料列表
        $materialList = [];
        if (!empty($params['material_list'])) {
            $pageMaterialList = array_create_index($params['material_list'], 'id');
            $materialList = $this->getTrackingOrderMaterialService()->getList(['tour_no' => $tour['tour_no'], 'batch_no' => $batch['batch_no'], 'id' => ['in', array_column($params['material_list'], 'id')]], ['*'], false)->toArray();
            $materialList = array_create_index($materialList, 'id');
            $tourMaterialList = $this->tourMaterialModel->newQuery()->where('tour_no', $tour['tour_no'])->whereIn('code', array_column($materialList, 'code'))->get()->toArray();
            $tourMaterialList = array_create_index($tourMaterialList, 'code');
            foreach ($materialList as $materialId => $material) {
                if (empty($tourMaterialList[$material['code']])) {
                    throw new BusinessLogicException('未从网点取材料[:code]', 1000, ['code' => $material['code']]);
                }
                if (intval($pageMaterialList[$materialId]['actual_quantity']) > intval($material['expect_quantity'])) {
                    throw new BusinessLogicException('材料数量不得超过预计材料数量');
                }
                if (empty($sumActualQuantity[$material['code']])) {
                    $sumActualQuantity[$material['code']] = intval($pageMaterialList[$materialId]['actual_quantity']);
                } else {
                    $sumActualQuantity[$material['code']] = $sumActualQuantity[$material['code']] + intval($pageMaterialList[$materialId]['actual_quantity']);
                }
                if ($sumActualQuantity[$material['code']] > $tourMaterialList[$material['code']]['surplus_quantity']) {
                    throw new BusinessLogicException('材料[:code]只剩[:count]个，请重新选择材料数量', 3001, ['code' => $material['code'], 'count' => $tourMaterialList[$material['code']]['surplus_quantity']]);
                }
            }
        }
        //验证包裹列表
        if (!empty($params['package_list'])) {
            $dbPackage = $this->getTrackingOrderPackageService()->getInfo(['id' => ['in', array_column($params['package_list'], 'id')], 'tour_no' => $tour['tour_no'], 'is_auth' => BaseConstService::IS_AUTH_1], ['id'], false);
            if (!empty($dbPackage) && (empty($params['auth_fullname']) || empty($params['auth_birth_date']))) {
                throw new BusinessLogicException('存在需要身份验证的包裹，请填写身份验证信息', 1000);
            }
        }
        return [$tour, $batch, $materialList];
    }

    /**
     * 获取线路任务统计数据
     * @param $id
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getTotalInfo($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        //顺带包裹信息
        $additionalPackageList = DB::table('additional_package')->whereIn('batch_no', $batchList->pluck('batch_no')->toArray())->get();
        if (!empty($additionalPackageList)) {
            $additionalPackageList = $additionalPackageList->toArray();
        } else {
            $additionalPackageList = [];
        }
        $tour['additional_package_list'] = $additionalPackageList;
        $tour['additional_package_count'] = count($additionalPackageList);
        //包裹信息
        $tour['pickup_package_expect_count'] = $this->getTrackingOrderPackageService()->sum('expect_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_1]);
        $tour['pickup_package_actual_count'] = $this->getTrackingOrderPackageService()->sum('actual_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_1, 'status' => BaseConstService::TRACKING_ORDER_STATUS_5]);
        $tour['pie_package_expect_count'] = $this->getTrackingOrderPackageService()->sum('expect_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2]);
        $tour['pie_package_actual_count'] = $this->getTrackingOrderPackageService()->sum('actual_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::TRACKING_ORDER_TYPE_2, 'status' => BaseConstService::TRACKING_ORDER_STATUS_5]);
        //材料信息
        $tour['material_expect_count'] = $this->tourMaterialModel->newQuery()->where('tour_no', $tour['tour_no'])->sum('expect_quantity');
        $tour['material_actual_count'] = $this->tourMaterialModel->newQuery()->where('tour_no', $tour['tour_no'])->sum('finish_quantity');
        //总费用计算
        $tour['actual_total_amount'] = number_format_simple(round($tour['sticker_amount'] + $tour['actual_replace_amount'] + $tour['actual_settlement_amount'] + $tour['delivery_amount'], 2), 2);
        return $tour;
    }

    /**
     * 司机入库
     * @param $id
     * @param $params
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function inWarehouse($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('线路任务不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('线路任务当前状态不允许回网点');
        }
        $batchCount = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => ['not in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]]);
        if ($batchCount !== 0) {
            throw new BusinessLogicException('当前线路任务还有未完成站点，请先处理');
        }
        $data = Arr::only($params, ['end_signature', 'end_signature_remark']);
        $data = Arr::add($data, 'end_time', now());
        $actualTime = strtotime($data['end_time']) - strtotime($tour['begin_time']);
        $car = $this->getCarService()->getInfo(['id' => $tour['car_id']], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('车辆不存在');
        }
//        if ($params['end_distance'] < $car['distance']) {
//            throw new BusinessLogicException('出库里程数小于该车上次入库里程数，请重新填写');
//        }
//        if ($params['end_distance'] > $car['distance'] + 1000000) {
//            throw new BusinessLogicException('出库里程数过大，请重新填写');
//        }
        $row = $this->getCarService()->update(['id' => $tour['car_id']], ['distance' => $params['end_distance']]);
        if ($row == false) {
            throw new BusinessLogicException('车辆里程记录失败，请重试');
        }
        $data = array_merge($data, ['actual_time' => $actualTime, 'actual_distance' => $tour['expect_distance'], 'end_distance' => $params['end_distance'] * 1000]);
        $rowCount = parent::updateById($tour['id'], Arr::add($data, 'status', BaseConstService::TOUR_STATUS_5));
        if ($rowCount === false) {
            throw new BusinessLogicException('司机入库失败，请重新操作');
        }
        return $tour;
    }

    /**
     * 更新批次配送顺序
     * @param $params
     * @param int $value
     * @return string
     * @throws BusinessLogicException
     * @throws Throwable
     */
    public function updateBatchIndex($params, $value = BaseConstService::NO)
    {
        Log::info('1', $params['batch_ids']);
        if ($value == BaseConstService::YES && (is_object($params['batch_ids']))) {
            $params['batch_ids'] = json_decode($params['batch_ids'], true);
        }
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        set_time_limit(240);

        Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '更新线路传入的参数', $params);

        $tour = Tour::where('tour_no', $params['tour_no'])->firstOrFail();
        throw_if(
            $tour->batchs->count() != count($params['batch_ids']),
            new BusinessLogicException('线路的站点数量不正确')
        );

        //此处的所有 batchids 应该经过验证!
        $nextBatch = $this->getNextBatchAndUpdateIndex($params['batch_ids']);

        TourLog::create([
            'tour_no' => $params['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        //0.5s执行一次
        return '修改线路成功';
    }

    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
     * @param $batchIds
     * @return Batch
     * @throws BusinessLogicException
     */
    public function getNextBatchAndUpdateIndex($batchIds): Batch
    {
        $first = false;
        foreach ($batchIds as $key => $batchId) {
            $tempbatch = Batch::where('id', $batchId)->first();
            if (!$first && in_array($tempbatch->status, [
                    BaseConstService::BATCH_WAIT_ASSIGN,
                    BaseConstService::BATCH_WAIT_OUT,
                    BaseConstService::BATCH_DELIVERING,
                    BaseConstService::BATCH_ASSIGNED
                ])) {
                if ($tempbatch) {
                    $batch = $tempbatch;
                    $first = true; // 找到了下一个目的地
                }
            }
            $tempbatch->update(['sort_id' => $key + 1]);
        }
        if ($batch ?? null) {
            return $batch;
        }

        throw new BusinessLogicException('未查找到下一个目的地');
    }

    /**
     * 通过线路ID获得线路任务
     * @param $params
     * @return array|Collection
     */
    public function getTourByLine($params)
    {
        $info = DB::table('tour')
            ->where('company_id', auth()->user()->company_id)
            ->where('line_id', $params['line_id'])
            ->where('execution_date', today()->format('Y - m - d'))
            ->pluck('tour_no')
            ->toArray();
        return $info ?? [];
    }

    public function getTourList()
    {
        $executionDate = [
            today()->addDay()->format('Y-m-d'),
            today()->subDay()->format('Y-m-d'),
            today()->format('Y-m-d')
        ];
        $info = [];
        $tour = DB::table('tour')
            ->where('company_id', auth()->user()->company_id)
            ->whereIn('execution_date', $executionDate)
            ->get()->toArray();
        $tour = collect($tour)->groupBy('line_id')->toArray();
        foreach ($tour as $k => $v) {
            $info[$k]['line_name'] = $v[0]->line_name;
            $info[$k]['tour_no_list'] = collect($v)->pluck('tour_no');
        }
        $info = array_values($info);
        return $info;
    }

    /**
     * 重新统计金额
     * @param $tourNo
     * @throws BusinessLogicException
     */
    public function reCountActualAmountByNo($tourNo)
    {
        $totalActualReplaceAmount = $this->getBatchService()->sum('actual_replace_amount', ['tour_no' => $tourNo, 'status' => BaseConstService::BATCH_CHECKOUT]);
        $totalActualSettlementAmount = $this->getBatchService()->sum('actual_settlement_amount', ['tour_no' => $tourNo, 'status' => BaseConstService::BATCH_CHECKOUT]);
        $rowCount = parent::update(['tour_no' => $tourNo], ['actual_replace_amount' => $totalActualReplaceAmount, 'actual_settlement_amount' => $totalActualSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

    /**
     * 重新统计金额
     * @param $tourNo
     * @throws BusinessLogicException
     */
    public function reCountAmountByNo($tourNo)
    {
        $totalReplaceAmount = $this->getBatchService()->sum('replace_amount', ['tour_no' => $tourNo, 'driver_id' => ['all', null]]);
        $totalSettlementAmount = $this->getBatchService()->sum('settlement_amount', ['tour_no' => $tourNo, 'driver_id' => ['all', null]]);
        $rowCount = parent::update(['tour_no' => $tourNo, 'driver_id' => ['all', null]], ['replace_amount' => $totalReplaceAmount, 'settlement_amount' => $totalSettlementAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('金额统计失败');
        }
    }

    /**
     * 跳过
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     * @throws Throwable
     */
    public function batchSkip($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false, [], ['sort_id' => 'asc']);
        if (empty($batchList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $batchList = $batchList->toArray();
        $row = $this->getBatchService()->update(['id' => $params['batch_id'], 'tour_no' => $tour['tour_no']], ['is_skipped' => BaseConstService::IS_SKIPPED]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        //删除该站点，在末尾加上该站点
        $batchIds = collect($batchList)->pluck('id')->toArray();
        array_splice($batchIds, array_search($params['batch_id'], $batchIds), 1);
        array_push($batchIds, intval($params['batch_id']));
        Log::channel('info')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'batchIds', $batchIds);
        $this->updateBatchIndex(['tour_no' => $tour['tour_no'], 'batch_ids' => $batchIds]);
    }

    /**
     * 恢复
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     * @throws Throwable
     */
    public function batchRecovery($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('数据不存在');
        }
        $tour = $tour->toArray();
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], ['*'], false);
        if (empty($batchList)) {
            throw new BusinessLogicException('数据不存在');
        }
        $row = $this->getBatchService()->update(['id' => $params['batch_id'], 'tour_no' => $tour['tour_no']], ['is_skipped' => BaseConstService::IS_NOT_SKIPPED]);
        if ($row == false) {
            throw new BusinessLogicException('操作失败');
        }
        //以已完成，恢复站点，未完成站点排序
        $assignedBatchIds = $batchList->where('status', BaseConstService::BATCH_CHECKOUT)->sortBy('sort_id')->pluck('id')->toArray();
        $ingBatchIds = $batchList->whereIn('status', [BaseConstService::BATCH_DELIVERING, BaseConstService::BATCH_CANCEL])->sortBy('sort_id')->pluck('id')->toArray();
        array_splice($ingBatchIds, array_search($params['batch_id'], $ingBatchIds), 1);
        $batchIds = array_merge($assignedBatchIds, [intval($params['batch_id'])], $ingBatchIds);
        Log::channel('info')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'batchIds', $batchIds);
        $this->updateBatchIndex(['tour_no' => $tour['tour_no'], 'batch_ids' => $batchIds]);
    }

    /**
     * 延迟
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function delay($id, $params)
    {
        $tour = parent::getInfo(['id' => $id, 'status' => BaseConstService::TOUR_STATUS_4], ['*'], false);
        if (empty($tour)) {
            return;
        }
        //站点处理
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_DELIVERING], ['*'], false);
        if (empty($batchList)) {
            return;
        }
        foreach ($batchList as $k => $v) {
            $batch = $this->getBatchService()->getInfo(['batch_no' => $v['batch_no']], ['*'], false)->toArray();
            if (empty($batch)) {
                throw new BusinessLogicException('数据不存在');
            }
            $row = $this->getBatchService()->update(['batch_no' => $v['batch_no']], [
                'expect_arrive_time' => Carbon::create($batch['expect_arrive_time'])->addMinutes(intval($params['delay_time']))->format('Y-m-d H:i:s'),
                'expect_time' => $batch['expect_time'] + $params['delay_time'] * 60
            ]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        }
        //线路任务处理
        $row = parent::updateById($id, [
                'warehouse_expect_arrive_time' => Carbon::create($tour['warehouse_expect_arrive_time'])->addMinutes(intval($params['delay_time']))->format('Y-m-d H:i:s'),
                'warehouse_expect_time' => $tour['warehouse_expect_time'] + $params['delay_time'] * 60]
        );
        if ($row == false) {
            throw new BusinessLogicException('延迟处理失败');
        }
        //延迟记录
        $row = $this->getTourDelayService()->create([
            'company_id' => $tour['company_id'],
            'tour_no' => $tour['tour_no'],
            'execution_date' => $tour['execution_date'],
            'line_id' => $tour['line_id'],
            'line_name' => $tour['line_name'],
            'driver_id' => $tour['driver_id'],
            'driver_name' => $tour['driver_name'],
            'delay_time' => $params['delay_time'] * 60,
            'delay_type' => $params['delay_type'],
            'delay_remark' => $params['delay_remark']
        ]);
        if ($row == false) {
            throw new BusinessLogicException('延迟记录失败');
        }
        return;
    }


    /**
     * 站点加入线路任务
     * @param $batch
     * @param $line
     * @param $order
     * @param $tour
     * @return BaseService|array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function join($batch, $line, $order, $tour = [])
    {
        $tour = !empty($tour) ? $tour : $this->getTourInfo($batch, $line);
        //加入线路任务
        $quantity = (intval($order['type']) === BaseConstService::TRACKING_ORDER_TYPE_1) ? ['expect_pickup_quantity' => 1] : ['expect_pie_quantity' => 1];
        $tour = !empty($tour) ? $this->joinExistTour($tour, $quantity) : $this->joinNewTour($batch, $line, $quantity);
        return $tour;
    }

    /**
     * 加入新的线路任务
     * @param $line
     * @param $batch
     * @param $quantity
     * @return BaseService|array|\Illuminate\Database\Eloquent\Model|mixed
     * @throws BusinessLogicException
     */
    private function joinNewTour($batch, $line, $quantity)
    {
        //获取网点信息
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        $warehouse = $warehouse->toArray();
        $tourNo = $this->getOrderNoRuleService()->createTourNo();
        $tour = parent::create(
            array_merge([
                'tour_no' => $tourNo,
                'line_id' => $line['id'],
                'line_name' => $line['name'],
                'execution_date' => $batch['execution_date'],
                'warehouse_id' => $warehouse['id'],
                'warehouse_name' => $warehouse['name'],
                'warehouse_phone' => $warehouse['phone'],
                'warehouse_country' => $warehouse['country'],
                'warehouse_post_code' => $warehouse['post_code'],
                'warehouse_city' => $warehouse['city'],
                'warehouse_street' => $warehouse['street'],
                'warehouse_house_number' => $warehouse['house_number'],
                'warehouse_address' => $warehouse['address'],
                'warehouse_lon' => $warehouse['lon'],
                'warehouse_lat' => $warehouse['lat'],
                'merchant_id' => $batch['merchant_id'] ?? 0
            ], $quantity)
        );
        if ($tour === false) {
            throw new BusinessLogicException('站点加入线路任务失败，请重新操作');
        }
        return $tour->getOriginal();
    }

    /**
     * 加入已存在线路任务
     * @param $tour
     * @param $quantity
     * @return mixed
     * @throws BusinessLogicException
     */
    public function joinExistTour($tour, $quantity)
    {
        $data = [
            'expect_pickup_quantity' => !empty($quantity['expect_pickup_quantity']) ? $tour['expect_pickup_quantity'] + $quantity['expect_pickup_quantity'] : $tour['expect_pickup_quantity'],
            'expect_pie_quantity' => !empty($quantity['expect_pie_quantity']) ? $tour['expect_pie_quantity'] + $quantity['expect_pie_quantity'] : $tour['expect_pie_quantity'],
        ];
        $rowCount = parent::update(['id' => $tour['id'], 'driver_id' => ['all', null]], $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点加入线路任务失败，请重新操作');
        }
        $tour = array_merge($tour, $data);
        return $tour;
    }

    /**
     * 获取线路任务信息
     * @param $batch
     * @param $line
     * @param $isLock
     * @param $tourNo
     * @param $isAssign
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTourInfo($batch, $line, $isLock = true, $tourNo = null, $isAssign = false)
    {
        if (!empty($tourNo)) {
            $this->query->where('tour_no', '=', $tourNo);
        }
        //若不存在线路任务或者超过最大运单量,则新建线路任务
        if ((intval($batch['expect_pickup_quantity']) > 0) && ($isAssign == false)) {
            $this->query->where(DB::raw('expect_pickup_quantity+' . 1), '<=', $line['pickup_max_count']);
        }
        if ((intval($batch['expect_pie_quantity']) > 0) && ($isAssign == false)) {
            $this->query->where(DB::raw('expect_pie_quantity+' . 1), '<=', $line['pie_max_count']);
        }
        $where = ['driver_id' => ['all', null], 'line_id' => $line['id'], 'execution_date' => $batch['execution_date'], 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2]]];
        isset($batch['merchant_id']) && $where['merchant_id'] = $batch['merchant_id'];
        $tour = ($isLock === true) ? parent::getInfoLock($where, ['*'], false) : parent::getInfo($where, ['*'], false);
        return !empty($tour) ? $tour->toArray() : [];
    }


    /**
     * 统计运单数量
     *
     * @param $info
     * @param $line
     * @param int $type 1-取件2-派件3-取件和派件
     * @return array
     */
    public function sumOrderCount($info, $line, $type = 1)
    {
        $arrCount = [];
        if ($type === 1) {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date'], 'driver_id' => ['all', null]]);
        } elseif ($type === 2) {
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date'], 'driver_id' => ['all', null]]);
        } else {
            $arrCount['pickup_count'] = parent::sum('expect_pickup_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date'], 'driver_id' => ['all', null]]);
            $arrCount['pie_count'] = parent::sum('expect_pie_quantity', ['line_id' => $line['id'], 'execution_date' => $info['execution_date'], 'driver_id' => ['all', null]]);
        }
        return $arrCount;
    }


}
