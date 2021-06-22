<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/29
 * Time: 16:06
 */

namespace App\Services\Driver;


use App\Exceptions\BusinessLogicException;
use App\Models\Stock;
use App\Services\BaseConstService;
use App\Services\PackageTrailService;
use App\Traits\CompanyTrait;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService extends BaseService
{
    public $filterRules = [
    ];

    public function __construct(Stock $stock, $resource = null, $infoResource = null)
    {
        parent::__construct($stock, $resource, $infoResource);
    }

    /**
     * 包裹出库
     * @param $packageList
     * @param $tour
     * @throws BusinessLogicException
     */
    public function outWarehouse($packageList, $tour)
    {
        $packageList = array_create_index($packageList, 'express_first_no');
        $dbPackageList = parent::getList(['express_first_no' => ['in', array_column($packageList, 'express_first_no')]], ['express_first_no'], false)->toArray();
        $rowCount = parent::delete(['express_first_no' => ['in', array_column($packageList, 'express_first_no')]]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        if (empty($dbPackageList)) return;
        $stockDataList = [];
        foreach ($dbPackageList as $dbPackage) {
            $no = $dbPackage['express_first_no'];
            $stockDataList[] = [
                'line_id' => $tour['line_id'],
                'warehouse_id' => auth()->user()->warehouse_id,
                'line_name' => $tour['line_name'],
                'tracking_order_no' => $packageList[$no]['tracking_order_no'],
                'execution_date' => $tour['execution_date'],
                'operator' => auth()->user()->fullname,
                'operator_id' => auth()->user()->id,
                'order_no' => $packageList[$no]['order_no'],
                'express_first_no' => $no
            ];
        }
        $rowCount = $this->getStockOutLogService()->insertAll($stockDataList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 转运出库
     * @param $trackingPackageList
     * @param $shift
     * @throws BusinessLogicException
     */
    public function trackingPackageOutWarehouse($trackingPackageList, $shift)
    {
        $trackingPackageList = array_create_index($trackingPackageList, 'express_first_no');
        $dbPackageList = parent::getList(['express_first_no' => ['in', array_column($trackingPackageList, 'express_first_no')]], ['express_first_no'], false)->toArray();
        parent::delete(['express_first_no' => ['in', array_column($trackingPackageList, 'express_first_no')]]);
        if (empty($dbPackageList)) return;
        $stockDataList = [];
        foreach ($dbPackageList as $dbPackage) {
            $no = $dbPackage['express_first_no'];
            $stockDataList[] = [
                'line_id' => '',
                'warehouse_id' => auth()->user()->warehouse_id,
                'line_name' => '',
                'tracking_order_no' => '',
                'execution_date' => '',
                'operator' => auth()->user()->fullname,
                'operator_id' => auth()->user()->id,
                'order_no' => $trackingPackageList[$no]['order_no'],
                'express_first_no' => $no
            ];
        }
        $rowCount = $this->getStockOutLogService()->insertAll($stockDataList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }


    /**
     * 分拨
     * @param $packageNo
     * @param int $ignoreRule
     * @return array
     * @throws BusinessLogicException
     */
    public function allocate($packageNo, $ignoreRule = BaseConstService::NO)
    {
        //存在验证
        $package = $this->getPackageService()->getInfo(['express_first_no' => $packageNo], ['*'], false, ['created_at' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('当前包裹不存在系统中');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $package->order_no], ['*'], false)->toArray();
        $type = $this->getOrderService()->getTrackingOrderType($order);//异常验证
        $this->check($package, $order, $type);
        $warehouse = $this->getWareHouseService()->getInfo(['id' => auth()->user()->warehouse_id], ['*'], false)->toArray();
        $pieWarehouse = $this->getBaseWarehouseService()->getPieWarehouseByOrder($order);
        $pieCenter = $this->getBaseWarehouseService()->getCenter($pieWarehouse);
        $pickupWarehouse = $this->getBaseWarehouseService()->getPickupWarehouseByOrder($order);
        $pickupCenter = $this->getBaseWarehouseService()->getCenter($pickupWarehouse);
        if ($package['stage'] == BaseConstService::PACKAGE_STAGE_1 && $pickupWarehouse['id'] !== $warehouse['id'] && $ignoreRule == BaseConstService::NO) {
            throw new BusinessLogicException('该包裹不应在此网点入库', 5008);
        }
        if (auth()->user()->warehouse_id == $pieWarehouse['id']) {
            //如果本网点为该包裹的派件网点，则生成派件运单进行派送
//            $dbTrackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $package['order_no'],
//                'type' => BaseConstService::TRACKING_ORDER_TYPE_2,
//                'status' => ['<>', [BaseConstService::TRACKING_ORDER_STATUS_5, BaseConstService::TRACKING_ORDER_STATUS_6, BaseConstService::TRACKING_ORDER_STATUS_7]]], ['*'], false);
//            if (empty($dbTrackingOrder)) {
            return $this->createTrackingOrder($package, $order, $type);
//            } else {
//                //如果已有派件运单则加入派件运单
//                return $this->joinTrackingOrder($package, $dbTrackingOrder);
//            }
        } else {
            if (auth()->user()->warehouse_id == $pieCenter['id']) {
                //如果本网点为该包裹的派件网点所属的分拨中心，则生成分拨转运单
                return $this->createTrackingPackage($package, $warehouse, $pieWarehouse, BaseConstService::TRACKING_PACKAGE_TYPE_1);
            } elseif ($warehouse['is_center'] == BaseConstService::YES || $warehouse['parent'] == 0) {
                //如果本网点为其他分拨中心，则生成中转转运单
                return $this->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
            } elseif ($pieWarehouse['id'] == $pickupWarehouse['id']) {
                //如果本网点为同分拨中心的网点，则生成短途中转转运单
                return $this->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2, BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_2);
            } else {
                //如果本网点为其他分拨中心的网点，则生成长途中国转转运单
                return $this->createTrackingPackage($package, $warehouse, $pickupCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
            }
        }
    }

    /**
     * 创建派件运单
     * @param $package
     * @param $order
     * @param $type
     * @return array
     * @throws BusinessLogicException
     */
    public function createTrackingOrder($package, $order, $type)
    {
        $trackingOrder = $tour = $line = [];
        //获取日期1取2派
        $executionDate = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['execution_date'] : $order['second_execution_date'];
        //若日期为空则自动拉取最近可选日期
        if (empty($executionDate) || Carbon::today()->gte($executionDate . ' 00:00:00')) {
            $placeCode = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['place_post_code'] : $order['second_place_post_code'];
            list($executionDate, $line) = $this->getLineService()->getCurrentDate(['place_post_code' => $placeCode, 'type' => $type], $order['merchant_id']);
        }
        //有效期验证,未超期的自动生成派件运单
        if (!empty($package['expiration_date']) && $executionDate > $package['expiration_date']) {
            $package['expiration_status'] = BaseConstService::EXPIRATION_STATUS_2;
            $row = $this->getPackageService()->updateById($package['id'], ['expiration_status' => BaseConstService::EXPIRATION_STATUS_2]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
            if (!empty($this->getTrackingOrderPackageService()->getInfo(['id' => $package['id']], ['*'], false))) {
                $row = $this->getTrackingOrderPackageService()->updateById($package['id'], ['expiration_status' => BaseConstService::EXPIRATION_STATUS_2]);
            }
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
        } else {
            //格式处理
            $trackingOrder = $this->form($order, $executionDate, $type);
            //生成运单号
            $trackingOrder['tracking_order_no'] = $this->getOrderNoRuleService()->createTrackingOrderNo();
            try {
                DB::beginTransaction();
                $tour = $this->getTrackingOrderService()->store($trackingOrder, $order['order_no'], $line, true);
                DB::commit();
            } catch (BusinessLogicException $e) {
                DB::rollBack();
                if ($e->getCode() == 5010) {
                    $placeCode = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['place_post_code'] : $order['second_place_post_code'];
                    list($executionDate, $line) = $this->getLineService()->getCurrentDate(['place_post_code' => $placeCode, 'type' => $type], $order['merchant_id']);
                    $tour = $this->getTrackingOrderService()->store($trackingOrder, $order['order_no'], $line, true);
                } else {
                    throw $e;
                }
            }
        }
        //更改包裹阶段
        $this->getPackageService()->updateById($package['id'], ['stage' => BaseConstService::PACKAGE_STAGE_3]);
        //包裹入库
        $stock = $this->trackingOrderStockIn($package, $tour, $trackingOrder);
        $stock['warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $stock['warehouse_id']], ['*'], false)['name'] ?? '';
        PackageTrailService::storeByTrackingOrderList([$package], BaseConstService::PACKAGE_TRAIL_ALLOCATE, $stock);
        if ($package['expiration_status'] == BaseConstService::EXPIRATION_STATUS_2) {
            return [
                'express_first_no' => $package['express_first_no'],
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
                'execution_date' => $executionDate,
                'feature_logo' => $package['feature_logo'],
                'expiration_date' => $package['expiration_date'] ?? '',
            ];
        } else {
            return [
                'express_first_no' => $package['express_first_no'],
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
                'execution_date' => $executionDate,
                'expiration_date' => '',
                'feature_logo' => $package['feature_logo'],
            ];
        }
    }

    /**
     * @param $package
     * @param $trackingOrder
     * @return array
     * @throws BusinessLogicException
     */
    public function joinTrackingOrder($package, $trackingOrder)
    {
        $tour = $line = [];
        //获取日期1取2派
        $executionDate = $trackingOrder['execution_date'];
        //有效期验证,未超期的自动生成派件运单
        if (!empty($package['expiration_date']) && $executionDate > $package['expiration_date']) {
            $package['expiration_status'] = BaseConstService::EXPIRATION_STATUS_2;
            $row = $this->getPackageService()->updateById($package['id'], ['expiration_status' => BaseConstService::EXPIRATION_STATUS_2]);
            if ($row == false) {
                throw new BusinessLogicException('操作失败');
            }
            if (!empty($this->getTrackingOrderPackageService()->getInfo(['express_first_no' => $package['express_first_no']], ['*'], false, ['id' => 'desc']))) {
                $row = $this->getTrackingOrderPackageService()->update(['express_first_no' => $package['express_first_no']], ['expiration_status' => BaseConstService::EXPIRATION_STATUS_2]);
                if ($row == false) {
                    throw new BusinessLogicException('操作失败');
                }
            }
        }
        //包裹入库
        $stock = $this->trackingOrderStockIn($package, $tour, $trackingOrder);
        $stock['warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $stock['warehouse_id']], ['*'], false)['name'] ?? '';
        PackageTrailService::storeByTrackingOrderList([$package], BaseConstService::PACKAGE_TRAIL_ALLOCATE, $stock);
        if ($package['expiration_status'] == BaseConstService::EXPIRATION_STATUS_2) {
            return [
                'express_first_no' => $package['express_first_no'],
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
                'execution_date' => $executionDate,
                'feature_logo' => $package['feature_logo'],
                'expiration_date' => $package['expiration_date'] ?? '',
            ];
        } else {
            return [
                'express_first_no' => $package['express_first_no'],
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
                'execution_date' => $executionDate,
                'expiration_date' => '',
                'feature_logo' => $package['feature_logo'],
            ];
        }
    }

    /**
     * 创建转运单
     * @param $package
     * @param $warehouse
     * @param $nextWarehouse
     * @param $trackingPackageType
     * @param int $trackingPackageDistanceType
     * @return array
     * @throws BusinessLogicException
     */
    public function createTrackingPackage($package, $warehouse, $nextWarehouse, $trackingPackageType, $trackingPackageDistanceType = BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_1)
    {
        $trackingPackage = $this->getTrackingPackageService()->create([
            'tracking_package_no' => $this->getOrderNoRuleService()->createTrackingPackageNo(),
            'merchant_id' => $package['merchant_id'],
            'express_first_no' => $package['express_first_no'],
            'order_no' => $package['order_no'],
            'bag_no' => '',
            'shift_no' => '',
            'status' => BaseConstService::TRACKING_PACKAGE_STATUS_1,
            'type' => $trackingPackageType,
            'distance_type' => $trackingPackageDistanceType,
            'weight' => $package['weight'],
            'warehouse_id' => $warehouse['id'],
            'warehouse_name' => $warehouse['name'],
            'next_warehouse_id' => $nextWarehouse['id'],
            'next_warehouse_name' => $nextWarehouse['name'],
            'pack_time' => null,
            'pack_operator' => '',
            'pack_operator_id' => null,
            'unpack_time' => null,
            'unpack_operator' => '',
            'unpack_operator_id' => null
        ]);
        //更改包裹阶段
        $this->getPackageService()->updateById($package['id'], ['stage' => BaseConstService::PACKAGE_STAGE_2]);
        $stock = $this->trackingPackageStockIn($package, $trackingPackage);
        $stock['warehouse_name'] = $this->getWareHouseService()->getInfo(['id' => $stock['warehouse_id']], ['*'], false)['name'] ?? '';
        PackageTrailService::storeByTrackingPackageList([$package], BaseConstService::PACKAGE_TRAIL_ALLOCATE, $stock);
        return [
            'express_first_no' => $package['express_first_no'],
            'type' => $trackingPackageType,
            'next_warehouse_id' => $nextWarehouse['id'],
            'next_warehouse_name' => $nextWarehouse['name']
        ];
    }

    /**
     * 转运单入库
     * @param $package
     * @param $trackingPackage
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingPackageStockIn($package, $trackingPackage)
    {
        //加入库存
        $stockData = [
            'line_id' => null,
            'line_name' => '',
            'warehouse_id' => auth()->user()->warehouse_id,
            'tracking_order_no' => '',
            'expiration_date' => null,
            'expiration_status' => 1,
            'operator' => auth()->user()->fullname,
            'operator_id' => auth()->user()->id,
            'order_no' => $package['order_no'],
            'express_first_no' => $package['express_first_no']
        ];
        //生成入库日志
        $rowCount = $this->getStockInLogService()->create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //推送入库信息
        dispatch(new \App\Jobs\PackagePickOut([$package]));
        return $rowCount->getAttributes();
    }

    /**
     * 运单入库
     * @param $package
     * @param $tour
     * @param $trackingOrder
     * @return mixed
     * @throws BusinessLogicException
     */
    public function trackingOrderStockIn($package, $tour, $trackingOrder)
    {
        //加入库存
        $stockData = [
            'line_id' => $tour['line_id'] ?? null,
            'line_name' => $tour['line_name'] ?? '',
            'warehouse_id' => auth()->user()->warehouse_id,
            'tracking_order_no' => $trackingOrder['tracking_order_no'] ?? '',
            'execution_date' => $trackingOrder['execution_date'] ?? '',
            'expiration_date' => $package['expiration_date'] ?? '',
            'expiration_status' => $package['expiration_status'] ?? 1,
            'operator' => auth()->user()->fullname,
            'operator_id' => auth()->user()->id,
            'order_no' => $package['order_no'],
            'express_first_no' => $package['express_first_no']
        ];
        //生成入库日志
        $rowCount = $this->getStockInLogService()->create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //推送入库信息
        dispatch(new \App\Jobs\PackagePickOut([$package]));
        return $rowCount->getAttributes();
    }

    /**
     * 库存已在库验证
     * @param $package
     * @throws BusinessLogicException
     */
    public function stockExistCheck($package)
    {
        $stock = $this->getStockService()->getInfo(['express_first_no' => $package['express_first_no']], ['*'], false);
        if (!empty($stock)) {
            $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $package['order_no'], 'driver_id' => ['all', null]], ['*'], false, ['id' => 'desc']);
            if (!empty($trackingOrder)) {
                $lineName = $trackingOrder['line_name'] ?? '';
                $date = $trackingOrder['execution_date'] ?? null;
            } else {
                $lineName = $stock['line_name'];
                $date = $stock['execution_date'];
            }
            throw new BusinessLogicException('包裹已入库，当前线路[:line_name]，派送日期[:execution_date]', 1000, ['line_name' => $lineName, 'execution_date' => $date]);
        }
    }

    /**
     * 异常验证
     * @param $order
     * @throws BusinessLogicException
     */
    public function stockExceptionCheck($order)
    {
        $trackingOrder = $this->getTrackingOrderService()->getInfo(['order_no' => $order['order_no']], ['*'], false, ['id' => 'desc']);
        //取派订单的取件失败，但是包裹拿到了（司机忘签了）
        if (in_array($order['status'], [BaseConstService::ORDER_STATUS_1, BaseConstService::ORDER_STATUS_2, BaseConstService::ORDER_STATUS_4])
            && $order['type'] == BaseConstService::ORDER_TYPE_3
            && !empty($trackingOrder)
            && $trackingOrder['type'] == BaseConstService::TRACKING_ORDER_TYPE_1
            && $trackingOrder['status'] == BaseConstService::TRACKING_ORDER_STATUS_6
            && !empty(CompanyTrait::getCompany()['stock_exception_verify'])
        ) {
            if (CompanyTrait::getCompany()['stock_exception_verify'] == BaseConstService::STOCK_EXCEPTION_VERIFY_2) {
                //未开启审核，自动入库，返回线路日期
                throw new BusinessLogicException('当前包裹不能生成对应派件运单，请进行异常入库处理', 5005);
            } else {
                //开启审核，不返回值
                throw new BusinessLogicException('当前包裹不能生成对应派件运单，请进行异常入库处理', 5004);
            }
        }
    }

    /**
     * 格式化
     * @param $order
     * @param $executionDate
     * @param $type
     * @return array
     */
    public function form($order, $executionDate, $type)
    {
        if ($order['type'] == BaseConstService::ORDER_TYPE_2) {
            $trackingOrder = Arr::only($order, ['place_fullname', 'place_phone', 'place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address', 'place_lat', 'place_lon']);
        } else {
            $trackingOrder = [
                'place_fullname' => $order['second_place_fullname'],
                'place_phone' => $order['second_place_phone'],
                'place_country' => $order['second_place_country'],
                'place_province' => $order['second_place_province'],
                'place_post_code' => $order['second_place_post_code'],
                'place_house_number' => $order['second_place_house_number'],
                'place_city' => $order['second_place_city'],
                'place_district' => $order['second_place_district'],
                'place_street' => $order['second_place_street'],
                'place_address' => $order['second_place_address'],
                'place_lat' => $order['second_place_lat'],
                'place_lon' => $order['second_place_lon']
            ];
        }
        $trackingOrder['execution_date'] = $executionDate;
        $trackingOrder['type'] = $type;
        return array_merge($trackingOrder, Arr::only($order, ['merchant_id', 'order_no', 'out_user_id', 'out_order_no', 'mask_code', 'special_remark']));
    }

    /**
     * 检查
     * @param $package
     * @param $order
     * @param $type
     * @throws BusinessLogicException
     */
    public function check($package, $order, $type)
    {
        $this->stockExistCheck($package);
        $this->stockExceptionCheck($order);
        //中转取派中
        if ($package->stage == BaseConstService::PACKAGE_STAGE_2 && $package->status == BaseConstService::PACKAGE_STATUS_2) {
            throw new BusinessLogicException('当前包裹状态为[:status_name],不能分拣入库', 1000, ['status_name' => $package->status_name]);
        }
        if (empty($type) || ($type != BaseConstService::TRACKING_ORDER_TYPE_2)) {
            throw new BusinessLogicException('当前包裹不能生成对应派件运单或已生成派件运单');
        }
//    }
    }
}
