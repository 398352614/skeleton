<?php

/**
 * 取件线路 服务
 * User: long
 * Date: 2019/12/30
 * Time: 11:55
 */

namespace App\Services\Driver;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TourBatchResource;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Tour;
use App\Models\TourLog;
use App\Models\TourMaterial;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Services\OrderNoRuleService;
use App\Traits\TourTrait;
use Illuminate\Support\Arr;
use App\Services\OrderTrailService;
use App\Services\Traits\TourRedisLockTrait;
use Illuminate\Support\Facades\DB;

/**
 * Class TourService
 * @package App\Services\Driver
 * @property  TourMaterial $tourMaterialModel
 * 取件线路流程
 * 1.开始装货 取件线路状态 已分配-待出库
 * 2.出仓库   取件线路状态 待出库-取派中
 * 3.到达站点 取件线路状态 取派中  有三种情况:1-签收 2-异常上报 3-取消取派
 * 4.回仓库   取件线路状态 取派中-已完成
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
     * 车辆 服务
     * @return CarService
     */
    private function getCarService()
    {
        return self::getInstance(CarService::class);
    }

    /**
     * 站点 服务
     * @return BatchService
     */
    private function getBatchService()
    {
        return self::getInstance(BatchService::class);
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

    /**
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    private function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
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
            throw new BusinessLogicException('取件线路当前状态不允许装货');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('取件线路待分配车辆,请先分配车辆');
        }
        //取件线路 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取件线路锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_ASSIGNED], ['status' => BaseConstService::BATCH_WAIT_OUT]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点锁定失败，请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_2], ['status' => BaseConstService::ORDER_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单锁定失败，请重新操作');
        }
        //包裹 处理
        $rowCount = $this->getPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::PACKAGE_STATUS_2], ['status' => BaseConstService::PACKAGE_STATUS_3]);
        if ($rowCount === false) {
            throw new BusinessLogicException('包裹锁定失败,请重新操作');
        }
        OrderTrailService::storeByTourNo($tour['tour_no'], BaseConstService::ORDER_TRAIL_LOCK);
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
            throw new BusinessLogicException('取件线路当前状态不允许取消锁定');
        }
        //取件线路 处理
        $rowCount = parent::updateById($id, ['status' => BaseConstService::TOUR_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取件线路取消锁定失败，请重新操作');
        }
        //站点 处理
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_WAIT_OUT], ['status' => BaseConstService::BATCH_ASSIGNED]);
        if ($rowCount === false) {
            throw new BusinessLogicException('站点取消锁定失败，请重新操作');
        }
        //订单 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_3], ['status' => BaseConstService::ORDER_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('订单取消锁定失败，请重新操作');
        }
        //包裹 处理
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::PACKAGE_STATUS_3], ['status' => BaseConstService::PACKAGE_STATUS_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆取消分配失败，请重新操作');
        }
        OrderTrailService::storeByTourNo($tour['tour_no'], BaseConstService::ORDER_TRAIL_UN_LOCK);
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
        $tour = parent::getInfo(['id' => $id, 'status' => ['in', [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]]], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在或当前状态不允许分配车辆');
        }
        $tour = $tour->toArray();
        //查看当前车辆是否已被分配给其他取件线路
        $otherTour = parent::getInfo(['id' => ['<>', $id], 'car_id' => $params['car_id'], 'execution_date' => $tour['execution_date'], 'status' => ['<>', BaseConstService::TOUR_STATUS_5]], ['*'], false);
        if (!empty($otherTour)) {
            throw new BusinessLogicException('当前车辆已被分配，请选择其他车辆');
        }
        //获取车辆
        $car = $this->getCarService()->getInfo(['id' => $params['car_id'], 'is_locked' => BaseConstService::CAR_TO_NORMAL], ['*'], false);
        if (empty($car)) {
            throw new BusinessLogicException('车辆不存在或已被锁定');
        }
        //分配
        $car = $car->toArray();
        $rowCount = $this->updateTourAll($tour, ['car_id' => $car['id'], 'car_no' => $car['car_no']]);
        if ($rowCount === false) {
            throw new BusinessLogicException('车辆分配失败，请重新操作');
        }
    }

    /**
     * 修改取件线路-站点-订单
     * @param $tour
     * @param $data
     * @return bool
     */
    private function updateTourAll($tour, $data)
    {
        //取件线路
        $rowCount = parent::updateById($tour['id'], $data);
        if ($rowCount === false) return false;
        //站点
        $rowCount = $this->getBatchService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;
        //订单
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no']], $data);
        if ($rowCount === false) return false;

        return true;
    }


    /**
     * 出库
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function outWarehouse($id, $params)
    {
        $tour = $this->checkOutWarehouse($id, $params);
        $params = Arr::only($params, ['material_list', 'cancel_order_id_list', 'begin_signature', 'begin_signature_remark', 'begin_signature_first_pic', 'begin_signature_second_pic', 'begin_signature_third_pic']);
        $params = Arr::add($params, 'status', BaseConstService::TOUR_STATUS_4);
        $params = Arr::add($params, 'begin_time', now());
        //取件线路更换状态
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
        $cancelOrderList = [];
        if (!empty($params['cancel_order_id_list'])) {
            $cancelOrderIdList = array_filter(explode(',', $params['cancel_order_id_list']), function ($value) {
                return is_numeric($value);
            });
            if (!empty($cancelOrderIdList)) {
                $rowCount = $this->getOrderService()->update(['id' => ['in', $cancelOrderIdList], 'tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_3], ['status' => BaseConstService::ORDER_STATUS_6]);
                if ($rowCount === false) {
                    throw new BusinessLogicException('出库失败');
                }
                $cancelOrderList = $this->getOrderService()->getList(['id' => ['in', $cancelOrderIdList]], ['*'], false)->toArray();
                //更换包裹状态
                $rowCount = $this->getPackageService()->update(['order_no' => ['in', array_column($cancelOrderList, 'order_no'), 'status' => BaseConstService::PACKAGE_STATUS_3]], ['status' => BaseConstService::PACKAGE_STATUS_6]);
                if ($rowCount === false) {
                    throw new BusinessLogicException('出库失败');
                }
            }
        }
        //订单更换状态
        $rowCount = $this->getOrderService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::ORDER_STATUS_3], ['status' => BaseConstService::ORDER_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //更换包裹状态
        $rowCount = $this->getPackageService()->update(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::PACKAGE_STATUS_3], ['status' => BaseConstService::PACKAGE_STATUS_4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('出库失败');
        }
        //插入取件线路材料
        !empty($params['material_list']) && $this->insertMaterialList($tour, $params['material_list']);

        TourTrait::afterOutWarehouse($tour, $cancelOrderList);
    }

    /**
     * 包裹取消取派
     *
     * @param $tour
     * @param $params
     * @throws BusinessLogicException
     */
    public function cancelPackageList($tour, $params)
    {
        $cancelPackageIdList = explode(',', $params['cancel_package_id_list']);
        $rowCount = $this->getPackageService()->update(['tour_no' => $tour['tour_no'], 'id' => ['in', $cancelPackageIdList]], ['status' => BaseConstService::PACKAGE_STATUS_6]);
        if ($rowCount === false) {
            throw new BusinessLogicException('未扫描包裹取消取派失败');
        }
        //若一个订单下面的所有包裹都取消取派了，订单变为取消取派
        $cancelPackageList = $this->getPackageService()->getList(['tour_no' => $tour['tour_no'], 'id' => ['in', $cancelPackageIdList]], ['order_no', DB::raw('COUNT(id) AS quantity')], false, ['order_no'])->toArray();
        $orderNoList = array_column($cancelPackageList, 'order_no');
        $allPackageList = $this->getPackageService()->getList(['tour_no' => $tour['tour_no'], 'order_no' => ['in', $orderNoList]], ['order_no', DB::raw('COUNT(id) AS quantity')], false, ['order_no'])->toArray();

        $cancelPackageList = array_create_index($cancelPackageList, 'order_no');
        $allPackageList = array_create_index($allPackageList, 'order_no');
        $cancelPackageList = Arr::where($cancelPackageList, function ($cancelPackage, $orderNo) use ($allPackageList) {
            return ($cancelPackage['quantity'] >= $allPackageList[$orderNo]['quantity']);
        });
        if (!empty($cancelPackageList)) {
            $cancelOrderNoList = array_column($cancelPackageList, 'order_no');
            $rowCount = $this->getOrderService()->update(['order_no' => ['in', $cancelOrderNoList]], ['status' => BaseConstService::ORDER_STATUS_6]);
            if ($rowCount === false) {
                throw new BusinessLogicException('未扫描包裹取消取派失败');
            }
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
        $codeList = array_column($materialList, 'code');
        data_fill($materialList, '*.name', '');
        if (count($codeList) != count(array_unique($codeList))) {
            throw new BusinessLogicException('材料有重复,请先合并');
        }
        $materialList = collect($materialList)->map(function ($material, $key) use ($tour) {
            $material = Arr::only($material, ['expect_quantity', 'actual_quantity', 'code', 'name']);
            $material = Arr::add($material, 'tour_no', $tour['tour_no']);
            $material = Arr::add($material, 'surplus_quantity', $material['actual_quantity']);
            return collect($material);
        })->toArray();
        $rowCount = $this->tourMaterialModel->insertAll($materialList);
        if ($rowCount === false) {
            throw new BusinessLogicException('材料新增失败');
        }
    }


    /**
     * 验证-出库
     * @param $id
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    private function checkOutWarehouse($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_3) {
            throw new BusinessLogicException('取件线路当前状态不允许出库');
        }
        if (empty($tour['car_id']) || empty($tour['car_no'])) {
            throw new BusinessLogicException('当前待分配车辆,请先分配车辆');
        }
        //验证订单数量
        $orderCount = $this->getOrderService()->count(['tour_no' => $tour['tour_no']]);
        if ($orderCount != $params['order_count']) {
            throw new BusinessLogicException('当前取件线路的订单数量不正确');
        }
        return $tour;
    }


    /**
     * 取件线路中的站点列表
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchList($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $batchFields = [
            'id', 'batch_no', 'tour_no', 'status',
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address',
            'expect_arrive_time', 'actual_arrive_time', 'expect_pickup_quantity', 'actual_pickup_quantity', 'expect_pie_quantity', 'actual_pie_quantity', 'receiver_lon', 'receiver_lat'
        ];
        $batchList = $this->getBatchService()->getList(['tour_no' => $tour['tour_no']], $batchFields, false, [], ['sort_id' => 'asc', 'created_at' => 'asc'])->toArray();
        $packageList = $this->getPackageService()->getList(['tour_no' => $tour['tour_no']], ['batch_no', 'type', DB::raw('SUM(`expect_quantity`) as expect_quantity'), DB::raw('SUM(`actual_quantity`) as actual_quantity')], false, ['batch_no', 'type'])->toArray();
        $packageList = collect($packageList)->groupBy('batch_no')->map(function ($itemPackageList) {
            return collect($itemPackageList)->keyBy('type');
        })->toArray();
        $batchList = array_map(function ($batch) use ($packageList) {
            $batch['expect_pickup_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_1]['expect_quantity'] ?? "0";
            $batch['actual_pickup_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_1]['actual_quantity'] ?? "0";
            $batch['expect_pie_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_2]['expect_quantity'] ?? "0";
            $batch['actual_pie_package_quantity'] = $packageList[$batch['batch_no']][BaseConstService::ORDER_TYPE_2]['actual_quantity'] ?? "0";
            return $batch;
        }, $batchList);
        $tour['batch_count'] = count($batchList);
        $tour['actual_batch_count'] = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => BaseConstService::BATCH_CHECKOUT]);
        $tour['batch_list'] = $batchList;
        return TourBatchResource::make($tour)->toArray(request());
    }

    /**
     * 达到时-获取站点的订单列表
     * @param $id
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchOrderList($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        //获取所有订单列表
        $orderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'execution_date', 'type', 'batch_no', 'order_no', 'status'], false)->toArray();
        //$orderList = array_create_group_index($orderList, 'type');
        //获取所有包裹列表
        $packageList = $this->getPackageService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        //获取所有材料列表
        $materialList = $this->getMaterialService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $batch['order_list'] = $orderList;
        $batch['package_list'] = $packageList;
        $batch['material_list'] = $materialList;
        return $batch;
    }


    /**
     * 站点到达 主要处理到达时间和里程
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchArrive($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $now = now();
        $actualTime = strtotime($now) - strtotime($tour['begin_time']);
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['actual_arrive_time' => $now, 'actual_time' => $actualTime]);
        if ($rowCount === false) {
            throw new BusinessLogicException('更新到达时间失败，请重新操作');
        }
        TourTrait::afterBatchArrived($tour, $batch);
    }


    /**
     * 到达后-站点详情
     * @param $id
     * @param $params
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getBatchInfo($id, $params)
    {
        list($tour, $batch) = $this->checkBatch($id, $params);
        $orderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no']], ['id', 'order_no', 'type', 'batch_no', 'status'], false);
        $orderList = collect($orderList)->map(function ($order, $key) {
            /**@var Order $order */
            return collect(Arr::add($order->toArray(), 'status_name', $order->status_name));
        })->toArray();
        //获取所有包裹列表
        $packageList = $this->getPackageService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $packageList = array_create_group_index($packageList, 'order_no');
        //将包裹列表和材料列表放在对应订单下
        $orderList = array_map(function ($order) use ($packageList) {
            $order['package_list'] = $packageList[$order['order_no']] ?? [];
            return $order;
        }, $orderList);
        //获取站点中过所有材料
        $materialList = $this->getMaterialService()->getList(['batch_no' => $batch['batch_no']], ['*'], false)->toArray();
        $batch['order_list'] = $orderList;
        $batch['material_list'] = $materialList;
        $batch['tour_id'] = $tour['id'];
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
            throw new BusinessLogicException('取件线路当前状态不允许上报异常');
        }
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能上报异常');
        }
        //生成站点异常
        $batchExceptionNo = $this->getOrderNoRuleService()->createBatchExceptionNo();
        $data = [
            'batch_exception_no' => $batchExceptionNo,
            'batch_no' => $batch['batch_no'],
            'receiver' => $batch['receiver'],
            'source' => __('司机来源'),
            'stage' => $params['stage'],
            'type' => $params['type'],
            'remark' => $params['exception_remark'],
            'picture' => $params['picture'],
            'driver_name' => auth()->user()->fullname
        ];
        $rowCount = $this->getBatchExceptionService()->create($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败，请重新操作');
        }
        //站点异常
        $rowCount = $this->getBatchService()->updateById($batch['id'], ['exception_label' => BaseConstService::ORDER_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败，请重新操作');
        }
        //订单异常
        $rowCount = $this->getOrderService()->update(['batch_no' => $batch['batch_no']], ['exception_label' => BaseConstService::BATCH_EXCEPTION_LABEL_2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('上报异常失败，请重新操作');
        }
    }


    /**
     * 站点取消取派
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchCancel($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许站点取消取派');
        }
        //异常站点和取派中的站点都可以取消取派
        if (intval($batch['status']) !== BaseConstService::BATCH_DELIVERING) {
            throw new BusinessLogicException('站点当前状态不能取消取派');
        }
        //站点取消取派
        $data = Arr::only($params, ['cancel_type', 'cancel_remark', 'cancel_picture']);
        $rowCount = $this->getBatchService()->updateById($batch['id'], Arr::add($data, 'status', BaseConstService::BATCH_CANCEL));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //订单取消取派
        $cancelOrderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::ORDER_STATUS_4], ['*'], false)->toArray();
        $rowCount = $this->getOrderService()->update(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::ORDER_STATUS_4], Arr::add($data, 'status', BaseConstService::ORDER_STATUS_6));
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        //包裹取消取派
        $rowCount = $this->getPackageService()->update(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::PACKAGE_STATUS_4], ['status' => BaseConstService::PACKAGE_STATUS_6]);
        if ($rowCount === false) {
            throw new BusinessLogicException('取消取派失败，请重新操作');
        }
        TourTrait::afterBatchCancel($tour, $batch, $cancelOrderList);
    }

    /**
     * 站点签收
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function batchSign($id, $params)
    {
        list($tour, $batch) = $this->checkBatchLock($id, $params);
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许站点签收');
        }
        //异常站点和取派中的站点都可以签收
        if (intval($batch['status'] !== BaseConstService::BATCH_DELIVERING)) {
            throw new BusinessLogicException('站点当前状态不能签收');
        }
        /*******************************************1.处理站点下的材料*************************************************/
        !empty($params['material_list']) && $this->dealMaterialList($tour, $params['material_list']);
        /*******************************************1.处理站点下的包裹*************************************************/
        $totalStickerAmount = 0.00;
        !empty($params['package_list']) && $totalStickerAmount = $this->dealPackageList($batch, $params['package_list']);
        /****************************************2.处理站点下的所有订单************************************************/
        $pickupCount = $pieCount = 0;
        $dbOrderList = $this->getOrderService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::ORDER_STATUS_4], ['*'], false)->toArray();
        foreach ($dbOrderList as $dbOrder) {
            if (intval($dbOrder['type']) === BaseConstService::ORDER_TYPE_1) {
                $pickupCount += 1;
            } else {
                $pieCount += 1;
            }
        }
        $rowCount = $this->getOrderService()->update(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::ORDER_STATUS_4], ['status' => BaseConstService::ORDER_STATUS_5, 'sticker_amount' => $totalStickerAmount]);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        /********************************************3.处理站点********************************************************/
        $batchData = [
            'status' => BaseConstService::BATCH_CHECKOUT,
            'actual_pickup_quantity' => $pickupCount,
            'actual_pie_quantity' => $pieCount,
            'sticker_amount' => $totalStickerAmount,
            'signature' => $params['signature'],
            'pay_type' => $params['pay_type'],
            'pay_picture' => $params['pay_picture']
        ];
        $rowCount = $this->getBatchService()->updateById($batch['id'], $batchData);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        /*****************************************4.更新取件线路信息***************************************************/
        $tourData = [
            'actual_pickup_quantity' => intval($tour['actual_pickup_quantity']) + $pickupCount,
            'actual_pie_quantity' => intval($tour['actual_pie_quantity']) + $pieCount,
            'sticker_amount' => $tour['sticker_amount'] + $totalStickerAmount,
            //'replace_amount' => $tour['replace_amount'] + $batch['replace_amount'],
            //'settlement_amount' => $tour['settlement_amount'] + $batch['settlement_amount'],
        ];
        $rowCount = parent::updateById($id, $tourData);
        if ($rowCount === false) {
            throw new BusinessLogicException('签收失败');
        }
        TourTrait::afterBatchSign($tour, $batch);
    }

    /**
     * 处理签收时的材料
     * @param $tour
     * @param $materialList
     * @throws BusinessLogicException
     */
    private function dealMaterialList($tour, $materialList)
    {
        foreach ($materialList as $material) {
            $rowCount = $this->getMaterialService()->update(['order_no' => $material['order_no'], 'code' => $material['code']], ['actual_quantity' => $material['actual_quantity']]);
            if ($rowCount === false) {
                throw new BusinessLogicException('材料处理失败');
            }
        }
        $materialList = collect($materialList)->groupBy('code')->toArray();
        foreach ($materialList as $materialItemList) {
            $quantity = collect($materialItemList)->sum('actual_quantity');
            $rowCount = $this->tourMaterialModel->newQuery()
                ->where('tour_no', '=', $tour['tour_no'])
                ->where('code', '=', $materialItemList[0]['code'])
                ->update(['finish_quantity' => DB::raw("finish_quantity+$quantity"), 'surplus_quantity' => DB::raw("surplus_quantity-$quantity")]);
            if ($rowCount === false) {
                throw new BusinessLogicException('材料处理失败');
            }
        }
    }

    /**
     * 处理签收时的包裹列表
     * @param $batch
     * @param $packageList
     * @return float
     * @throws BusinessLogicException
     */
    private function dealPackageList($batch, $packageList)
    {
        /***************************************2.处理站点下的所有包裹*************************************************/
        $packageList = collect($packageList)->unique('id')->keyBy('id')->toArray();
        $packageIdList = array_keys($packageList);
        $totalStickerAmount = 0.00;
        $dbPackageList = $this->getPackageService()->getList(['batch_no' => $batch['batch_no'], 'status' => BaseConstService::PACKAGE_STATUS_4], ['id', 'order_no', 'batch_no', 'type'], false)->toArray();
        foreach ($dbPackageList as $dbPackage) {
            //判断是否签收
            if (in_array(intval($dbPackage['id']), $packageIdList)) {
                $status = BaseConstService::ORDER_STATUS_5;
                //判断取件或派件
                if (intval($dbPackage['type']) === BaseConstService::ORDER_TYPE_1) {
                    $totalStickerAmount += BaseConstService::STICKER_AMOUNT;
                    $packageData = ['actual_quantity' => 1, 'status' => $status, 'sticker_amount' => BaseConstService::STICKER_AMOUNT, 'sticker_no' => $packageList[$dbPackage['id']]['sticker_no'] ?? ''];
                } else {
                    $packageData = ['actual_quantity' => 1, 'status' => $status];
                }
            } else {
                $packageData = ['status' => BaseConstService::ORDER_STATUS_6];
            }
            $rowCount = $this->getPackageService()->update(['id' => $dbPackage['id']], $packageData);
            if ($rowCount === false) {
                throw new BusinessLogicException('签收失败');
            }
        }
        return $totalStickerAmount;
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
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfo(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前取件线路');
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
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        $batch = $this->getBatchService()->getInfoLock(['id' => $params['batch_id']], ['*'], false);
        if (empty($batch)) {
            throw new BusinessLogicException('站点不存在');
        }
        $batch = $batch->toArray();
        if ($batch['tour_no'] != $tour['tour_no']) {
            throw new BusinessLogicException('当前站点不属于当前取件线路');
        }
        return [$tour, $batch];
    }

    /**
     * 获取取件线路统计数据
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTotalInfo($id)
    {
        $tour = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        //包裹信息
        $tour['pickup_package_expect_count'] = $this->getPackageService()->sum('expect_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::ORDER_TYPE_1]);
        $tour['pickup_package_actual_count'] = $this->getPackageService()->sum('actual_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::ORDER_TYPE_1, 'status' => BaseConstService::PACKAGE_STATUS_5]);
        $tour['pie_package_expect_count'] = $this->getPackageService()->sum('expect_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::ORDER_TYPE_2]);
        $tour['pie_package_actual_count'] = $this->getPackageService()->sum('actual_quantity', ['tour_no' => $tour['tour_no'], 'type' => BaseConstService::ORDER_TYPE_2, 'status' => BaseConstService::PACKAGE_STATUS_5]);
        //材料信息
        $tour['material_expect_count'] = $this->tourMaterialModel->newQuery()->where('tour_no', $tour['tour_no'])->sum('expect_quantity');
        $tour['material_actual_count'] = $this->tourMaterialModel->newQuery()->where('tour_no', $tour['tour_no'])->sum('finish_quantity');
        //总费用计算
        $tour['total_amount'] = $tour['sticker_amount'] + $tour['replace_amount'] + $tour['settlement_amount'];
        return $tour;

    }

    /**
     * 司机入库
     * @param $id
     * @param $params
     * @throws BusinessLogicException
     */
    public function inWarehouse($id, $params)
    {
        $tour = parent::getInfoLock(['id' => $id], ['*'], false);
        if (empty($tour)) {
            throw new BusinessLogicException('取件线路不存在');
        }
        $tour = $tour->toArray();
        if (intval($tour['status']) !== BaseConstService::TOUR_STATUS_4) {
            throw new BusinessLogicException('取件线路当前状态不允许回仓库');
        }
        $batchCount = $this->getBatchService()->count(['tour_no' => $tour['tour_no'], 'status' => ['not in', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL]]]);
        if ($batchCount !== 0) {
            throw new BusinessLogicException('当前取件线路还有未完成站点，请先处理');
        }
        $data = Arr::only($params, ['end_signature', 'end_signature_remark']);
        $data = Arr::add($data, 'end_time', now());
        $rowCount = parent::updateById($tour['id'], Arr::add($data, 'status', BaseConstService::TOUR_STATUS_5));
        if ($rowCount === false) {
            throw new BusinessLogicException('司机入库失败，请重新操作');
        }
        TourTrait::afterBackWarehouse($tour);
    }

    /**
     * 更新批次配送顺序
     */
    public function updateBatchIndex()
    {
        // * @apiParam {String}   batch_ids                  有序的批次数组
        // * @apiParam {String}   tour_no                    在途编号
        set_time_limit(240);

        app('log')->info('更新线路传入的参数为:', $this->formData);

        $tour = Tour::where('tour_no', $this->formData['tour_no'])->firstOrFail();
        throw_if(
            $tour->batchs->count() != count($this->formData['batch_ids']),
            new BusinessLogicException('线路的站点数量不正确')
        );

        //此处的所有 batchids 应该经过验证!
        $nextBatch = $this->getNextBatchAndUpdateIndex($this->formData['batch_ids']);

        TourLog::create([
            'tour_no' => $this->formData['tour_no'],
            'action' => BaseConstService::TOUR_LOG_UPDATE_LINE,
            'status' => BaseConstService::TOUR_LOG_PENDING,
        ]);

        event(new AfterTourUpdated($tour, $nextBatch->batch_no));

        //0.5s执行一次
        return '修改线路成功';
    }

    /**
     * 此处要求batchIds 为有序,并且已完成或者异常的 batch 在前方,未完成的 batch 在后方
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
}
