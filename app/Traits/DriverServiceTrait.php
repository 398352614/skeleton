<?php

namespace App\Traits;

use App\Services\Driver\BagService;
use App\Services\Driver\BaseLineService;
use App\Services\Driver\BaseWarehouseService;
use App\Services\Driver\BillService;
use App\Services\Driver\MemorandumService;
use App\Services\Driver\MerchantGroupLineService;
use App\Services\Driver\MerchantGroupService;
use App\Services\Driver\ShiftService;
use App\Services\Driver\StockExceptionService;
use App\Services\Driver\AdditionalPackageService;
use App\Services\Driver\BatchExceptionService;
use App\Services\Driver\BatchService;
use App\Services\Driver\CarService;
use App\Services\Driver\DeviceService;
use App\Services\Driver\LineAreaService;
use App\Services\Driver\LineRangeService;
use App\Services\Driver\LineService;
use App\Services\Driver\MaterialService;
use App\Services\Driver\MerchantGroupLineRangeService;
use App\Services\Driver\MerchantService;
use App\Services\Driver\OrderService;
use App\Services\Driver\PackageService;
use App\Services\Driver\RechargeService;
use App\Services\Driver\RechargeStatisticsService;
use App\Services\Driver\StockInLogService;
use App\Services\Driver\StockOutLogService;
use App\Services\Driver\StockService;
use App\Services\Driver\TourDelayService;
use App\Services\Driver\TourService;
use App\Services\Driver\TourTaskService;
use App\Services\Driver\TrackingOrderMaterialService;
use App\Services\Driver\TrackingOrderPackageService;
use App\Services\Driver\TrackingOrderService;
use App\Services\Driver\TrackingPackageService;
use App\Services\Driver\WareHouseService;
use App\Services\OrderNoRuleService;
use App\Services\PackageNoRuleService;

Trait DriverServiceTrait
{
    use FactoryInstanceTrait;

    /**
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * @return MerchantGroupLineService
     */
    public function getMerchantGroupLineService()
    {
        return self::getInstance(MerchantGroupLineService::class);
    }

    /**
     * 库存服务
     * @return StockService
     */
    public function getStockService()
    {
        return self::getInstance(StockService::class);
    }

    /**
     * 入库日志 服务
     * @return StockInLogService
     */
    public function getStockInLogService()
    {
        return self::getInstance(StockInLogService::class);
    }

    /**
     * 出库日志 服务
     * @return StockOutLogService
     */
    public function getStockOutLogService()
    {
        return self::getInstance(StockOutLogService::class);
    }


    /**
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
    }

    /**
     * @return BillService
     */
    public function getBillService()
    {
        return self::getInstance(BillService::class);
    }

    /**
     * @return RechargeStatisticsService
     */
    public function getRechargeStatisticsService()
    {
        return self::getInstance(RechargeStatisticsService::class);
    }

    /**
     * 充值服务
     * @return RechargeService
     */
    public function getRechargeService()
    {
        return self::getInstance(RechargeService::class);
    }

    /**
     * 设备 服务
     * @return DeviceService
     */
    public function getDeviceService()
    {
        return self::getInstance(DeviceService::class);
    }

    /**
     * 车辆 服务
     * @return CarService
     */
    public function getCarService()
    {
        return self::getInstance(CarService::class);
    }

    /**
     * 站点 服务
     * @return BatchService
     */
    public function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 站点异常 服务
     * @return BatchExceptionService
     */
    public function getBatchExceptionService()
    {
        return self::getInstance(BatchExceptionService::class);
    }

    /**
     * 运单 服务
     * @return TrackingOrderService
     */
    public function getTrackingOrderService()
    {
        return self::getInstance(TrackingOrderService::class);
    }

    /**
     * 转运单 服务
     * @return TrackingPackageService
     */
    public function getTrackingPackageService()
    {
        return self::getInstance(TrackingPackageService::class);
    }

    /**
     * 袋号 服务
     * @return BagService
     */
    public function getBagService()
    {
        return self::getInstance(BagService::class);
    }

    /**
     * 车次 服务
     * @return ShiftService
     */
    public function getShiftService()
    {
        return self::getInstance(ShiftService::class);
    }

    /**
     * 运单包裹表
     * @return TrackingOrderPackageService
     */
    public function getTrackingOrderPackageService()
    {
        return self::getInstance(TrackingOrderPackageService::class);
    }

    /**
     * 运单材料表
     * @return TrackingOrderMaterialService
     */
    public function getTrackingOrderMaterialService()
    {
        return self::getInstance(TrackingOrderMaterialService::class);
    }


    /**
     * 订单 服务
     * @return OrderService
     */
    public function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    /**
     * 包裹 服务
     * @return PackageService
     */
    public function getPackageService()
    {
        return self::getInstance(PackageService::class);
    }

    /**
     * 材料 服务
     * @return MaterialService
     */
    public function getMaterialService()
    {
        return self::getInstance(MaterialService::class);
    }

    /**
     * 顺带包裹 服务
     * @return AdditionalPackageService
     */
    public function getAdditionalPackageService()
    {
        return self::getInstance(AdditionalPackageService::class);
    }

    /**
     * 任务 服务
     * @return TourTaskService
     */
    public function getTourTaskService()
    {
        return self::getInstance(TourTaskService::class);
    }

    /**
     * 线路任务服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
    }

    /**
     * 备忘录服务
     * @return MemorandumService
     */
    public function getMemorandumService()
    {
        return self::getInstance(MemorandumService::class);
    }


    /**
     * 延迟服务
     * @return TourDelayService
     */
    public function getTourDelayService()
    {
        return self::getInstance(TourDelayService::class);
    }

    /**
     * 线路 服务
     * @return LineService
     */
    public function getLineService()
    {
        return self::getInstance(LineService::class);
    }


    /**
     * 邮编线路范围 服务
     * @return LineRangeService
     */
    public function getLineRangeService()
    {
        return self::getInstance(LineRangeService::class);
    }

    /**
     * 货主线路范围 服务
     * @return MerchantGroupLineRangeService
     */
    public function getMerchantGroupLineRangeService()
    {
        return self::getInstance(MerchantGroupLineRangeService::class);
    }

    /**
     * 线路区域 服务
     * @return LineAreaService
     */
    public function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
    }

    /**
     * 网点 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
    }

    /**
     * 网点基础服务
     * @return BaseWarehouseService
     */
    public function getBaseWarehouseService()
    {
        return self::getInstance(BaseWarehouseService::class);
    }

    /**
     * 运单编号规则
     * @return PackageNoRuleService
     */
    public function getPackageNoRuleService()
    {
        return self::getInstance(PackageNoRuleService::class);
    }


    /**
     * 入库异常 服务
     * @return StockExceptionService
     */
    public function getStockExceptionService()
    {
        return self::getInstance(StockExceptionService::class);
    }

    /**
     * 货主组 服务
     * @return StockExceptionService
     */
    public function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
    }

    /**
     * 基础线路 服务
     * @return BaseLineService
     */
    public function getBaseLineService()
    {
        return self::getInstance(BaseLineService::class);
    }
}
