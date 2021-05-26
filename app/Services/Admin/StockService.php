<?php
/**
 * 库存 服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\StockResource;
use App\Models\Stock;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class StockService extends BaseService
{
    public $filterRules = [
        'execution_date' => ['between', ['begin_date', 'end_date']],
        'express_first_no,order_no' => ['like', 'keyword'],
        'express_first_no' => ['like', 'express_first_no'],
        'order_no' => ['like', 'order_no'],
        'line_id' => ['=', 'line_id'],
        'line_name' => ['like', 'line_name'],
        'expiration_status' => ['=', 'expiration_status']
    ];

    public function __construct(Stock $stock)
    {
        parent::__construct($stock, StockResource::class);
    }

    /**
     * 分拨
     * @param $packageNo
     * @param $warehouseId
     * @param bool $check
     * @return array
     * @throws BusinessLogicException
     */
    public function allocate($packageNo, $warehouseId, $check = true)
    {
        //存在验证
        $package = $this->getPackageService()->getInfo(['express_first_no' => $packageNo], ['*'], false, ['created_at' => 'desc']);
        if (empty($package)) {
            throw new BusinessLogicException('当前包裹不存在系统中');
        }
        $order = $this->getOrderService()->getInfo(['order_no' => $package->order_no], ['*'], false)->toArray();
        $type = $this->getOrderService()->getTrackingOrderType($order);
        if ($check == true) {
            $this->check($package, $order, $type);
        }
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $warehouseId], ['*'], false)->toArray();
        $pickupWarehouse = $this->getBaseWarehouseService()->getPickupWarehouseByOrder($order);
        $pieWarehouse = $this->getBaseWarehouseService()->getPieWarehouseByOrder($order);
        $pieCenter = $this->getBaseWarehouseService()->getCenter($pieWarehouse);
        if ($warehouseId == $pieWarehouse['id']) {
            //如果本网点为该包裹的派件网点，则生成派件运单进行派送
            return $this->createTrackingOrder($package, $order, $type);
        } elseif ($warehouseId == $pieCenter['id']) {
            //如果本网点为该包裹的派件网点所属的分拨中心，则生成分拨转运单
            return $this->createTrackingPackage($package, $warehouse, $pieWarehouse, BaseConstService::TRACKING_PACKAGE_TYPE_1);
        } elseif ($warehouse['is_center'] == BaseConstService::YES) {
            //如果本网点为其他分拨中心，则生成中转转运单
            return $this->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
        } elseif ($pieWarehouse['id'] == $pickupWarehouse['id']) {
            //如果本网点为同分拨中心的网点，则生成短途中转转运单
            return $this->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2, BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_2);
        } else {
            //如果本网点为其他分拨中心的网点，则生成长途中国转转运单
            return $this->createTrackingPackage($package, $warehouse, $pieCenter, BaseConstService::TRACKING_PACKAGE_TYPE_2);
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
        //获取最近可选日期
        $executionDate = ($order['type'] == BaseConstService::ORDER_TYPE_2) ? $order['execution_date'] : $order['second_execution_date'];
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
            $tour = $this->getTrackingOrderService()->store($trackingOrder, $order['order_no'], true);
        }
        //更改包裹阶段
        $this->getPackageService()->updateById($package['id'], ['stage' => BaseConstService::PACKAGE_STAGE_3]);
        //包裹入库
        $package['execution_date'] = $executionDate;
        $this->trackingOrderStockIn($package, $tour, $trackingOrder);
        if ($package['expiration_status'] == BaseConstService::EXPIRATION_STATUS_2) {
            return [
                'type' => BaseConstService::TRACKING_PACKAGE_TYPE_3,
                'express_first_no' => $package['express_first_no'],
                'line_id' => $tour['line_id'] ?? '',
                'line_name' => $tour['line_name'] ?? '',
                'execution_date' => $executionDate,
                'feature_logo' => $package['feature_logo'],
                'expiration_date' => $package['expiration_date'] ?? '',
            ];
        } else {
            return [
                'type' => BaseConstService::TRACKING_PACKAGE_TYPE_3,
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
    public function createTrackingPackage($package, $warehouse, $nextWarehouse, $trackingPackageType, $trackingPackageDistanceType = BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_2)
    {
        $trackingPackage = $this->getTrackingPackageService()->create([
            'tracking_package_no' => $this->getOrderNoRuleService()->createTrackingPackageNo(),
            'express_first_no' => $package['express_first_no'],
            'order_no' => $package['express_first_no'],
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
        $this->getPackageService()->updateById($package['id'], ['stage' => BaseConstService::PACKAGE_STAGE_3]);
        $this->trackingPackageStockIn($package, $trackingPackage);
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
     * @throws BusinessLogicException
     */
    public function trackingPackageStockIn($package, $trackingPackage)
    {
        $dbPackage = parent::getInfoLock(['express_first_no' => $package['express_first_no']], ['*'], false);
        if (!empty($dbPackage)) {
            throw  new BusinessLogicException('当前包裹已入库');
        }
        //加入库存
        $stockData = [
            'line_id' => null,
            'line_name' => '',
            'tracking_order_no' => '',
            'expiration_date' => null,
            'expiration_status' => 1,
            'operator' => auth()->user()->fullname,
            'operator_id' => auth()->user()->id,
            'order_no' => $package['order_no'],
            'express_first_no' => $package['express_first_no']
        ];
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //生成入库日志
        $rowCount = $this->getStockInLogService()->create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //推送入库信息
        dispatch(new \App\Jobs\PackagePickOut([$package]));
    }

    /**
     * 运单入库
     * @param $package
     * @param $tour
     * @param $trackingOrder
     * @throws BusinessLogicException
     */
    public function trackingOrderStockIn($package, $tour, $trackingOrder)
    {
        $dbPackage = parent::getInfoLock(['express_first_no' => $package['express_first_no']], ['*'], false);
        if (!empty($dbPackage)) {
            throw  new BusinessLogicException('当前包裹已入库');
        }
        //加入库存
        $stockData = [
            'line_id' => $tour['line_id'] ?? null,
            'line_name' => $tour['line_name'] ?? '',
            'tracking_order_no' => $trackingOrder['tracking_order_no'] ?? '',
            'execution_date' => $package['execution_date'],
            'expiration_date' => $package['expiration_date'] ?? '',
            'expiration_status' => $package['expiration_status'] ?? 1,
            'operator' => auth()->user()->fullname,
            'operator_id' => auth()->user()->id,
            'order_no' => $package['order_no'],
            'express_first_no' => $package['express_first_no']
        ];
        $rowCount = parent::create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //生成入库日志
        $rowCount = $this->getStockInLogService()->create($stockData);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
        //推送入库信息
        dispatch(new \App\Jobs\PackagePickOut([$package]));
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
        //$this->stockExistCheck($package);
        $this->stockExceptionCheck($order);
        if (!in_array($package->status, [BaseConstService::PACKAGE_STATUS_1, BaseConstService::PACKAGE_STATUS_2])) {
            throw new BusinessLogicException('当前包裹状态为[:status_name],不能分拣入库', 1000, ['status_name' => $package->status_name]);
        }
        if (empty($type) || ($type != BaseConstService::TRACKING_ORDER_TYPE_2)) {
            throw new BusinessLogicException('当前包裹不能生成对应派件运单或已生成派件运单');
        }
    }
}
