<?php

namespace App\Traits;

use App\Services\Admin\AdditionalPackageService;
use App\Services\Admin\AddressService;
use App\Services\Admin\AddressTemplateService;
use App\Services\Admin\ApiTimesService;
use App\Services\Admin\BaseLineService;
use App\Services\Admin\BaseWarehouseService;
use App\Services\Admin\BatchExceptionService;
use App\Services\Admin\BatchService;
use App\Services\Admin\CarBrandService;
use App\Services\Admin\CarMaintainDetailService;
use App\Services\Admin\CarService;
use App\Services\Admin\CompanyService;
use App\Services\Admin\DriverService;
use App\Services\Admin\EmployeeService;
use App\Services\Admin\FeeService;
use App\Services\Admin\LineAreaService;
use App\Services\Admin\LineRangeService;
use App\Services\Admin\LineService;
use App\Services\Admin\MaterialService;
use App\Services\Admin\MerchantApiService;
use App\Services\Admin\MerchantGroupLineRangeService;
use App\Services\Admin\MerchantGroupLineService;
use App\Services\Admin\MerchantGroupService;
use App\Services\Admin\MerchantRechargeService;
use App\Services\Admin\MerchantService;
use App\Services\Admin\OrderAmountService;
use App\Services\Admin\OrderDefaultConfigService;
use App\Services\Admin\OrderService;
use App\Services\Admin\OrderTemplateService;
use App\Services\Admin\PackageService;
use App\Services\Admin\PrintTemplateService;
use App\Services\Admin\RechargeService;
use App\Services\Admin\RechargeStatisticsService;
use App\Services\Admin\RoleService;
use App\Services\Admin\SparePartsStockService;
use App\Services\Admin\StockExceptionService;
use App\Services\Admin\StockInLogService;
use App\Services\Admin\StockOutLogService;
use App\Services\Admin\StockService;
use App\Services\Admin\TourDriverService;
use App\Services\Admin\TourService;
use App\Services\Admin\TrackingOrderMaterialService;
use App\Services\Admin\TrackingOrderPackageService;
use App\Services\Admin\TrackingOrderService;
use App\Services\Admin\TransportPriceService;
use App\Services\Admin\UploadService;
use App\Services\Admin\WareHouseService;
use App\Services\ApiServices\GoogleApiService;
use App\Services\Admin\TrackingPackageService;
use App\Services\OrderNoRuleService;
use App\Services\PackageNoRuleService;
use App\Services\TrackingOrderTrailService;

trait AdminServiceTrait
{
    use FactoryInstanceTrait;

    /**
     * @return mixed
     */
    public function getCompanyService()
    {
        return self::getInstance(CompanyService::class);
    }

    /**
     * 货主服务
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
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
     * 入库日志 服务
     * @return StockOutLogService
     */
    public function getStockOutLogService()
    {
        return self::getInstance(StockOutLogService::class);
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
     * 线路区域 服务
     * @return LineAreaService
     */
    public function getLineAreaService()
    {
        return self::getInstance(LineAreaService::class);
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
     * 订单 服务
     * @return OrderService
     */
    public function getOrderService()
    {
        return self::getInstance(OrderService::class);
    }

    /**
     * 基础网点 服务
     * @return BaseWarehouseService
     */
    public function getBaseWarehouseService()
    {
        return self::getInstance(BaseWarehouseService::class);
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
     * 订单费用 服务
     * @return OrderAmountService
     */
    public function getOrderAmountService()
    {
        return self::getInstance(OrderAmountService::class);
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
     * 订单模板 服务
     * @return OrderTemplateService
     */
    public function getOrderTemplateService()
    {
        return self::getInstance(OrderTemplateService::class);
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
     * 运单轨迹 服务
     * @return TrackingOrderTrailService
     */
    public function getTrackingOrderTrailService()
    {
        return self::getInstance(TrackingOrderTrailService::class);
    }

    /**
     * 商组线路范围 服务
     * @return MerchantGroupLineRangeService
     */
    public function getMerchantGroupLineRangeService()
    {
        return self::getInstance(MerchantGroupLineRangeService::class);
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
     * 顺带包裹 服务
     * @return AdditionalPackageService
     */
    public function getAdditionalPackageService()
    {
        return self::getInstance(AdditionalPackageService::class);
    }

    /**
     * 线路基础 服务
     * @return BaseLineService
     */
    public function getBaseLineService()
    {
        return self::getInstance(BaseLineService::class);
    }

    /**
     * 车辆品牌 服务
     * @return CarBrandService
     */
    public function getCarBrandService()
    {
        return self::getInstance(CarBrandService::class);
    }


    /**
     * 地址模板 服务
     * @return AddressTemplateService
     */
    public function getAddressTemplateService()
    {
        return self::getInstance(AddressTemplateService::class);
    }

    /**
     * 司机 服务
     * @return DriverService
     */
    public function getDriverService()
    {
        return self::getInstance(DriverService::class);
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
     * 充值统计 服务
     * @return RechargeStatisticsService
     */
    public function getRechargeStatisticsService()
    {
        return self::getInstance(RechargeStatisticsService::class);
    }

    /**
     * 上传服务
     * @return UploadService
     */
    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 运价管理 服务
     * @return TransportPriceService
     */
    public function getTransportPriceService()
    {
        return self::getInstance(TransportPriceService::class);
    }

    /**
     * 货主api 服务
     * @return MerchantApiService
     */
    public function getMerchantApiService()
    {
        return parent::getInstance(MerchantApiService::class);
    }

    /**
     * 货主充值api 服务
     * @return MerchantRechargeService
     */
    public function getMerchantRechargeService()
    {
        return parent::getInstance(MerchantRechargeService::class);
    }

    /**
     * 货主组管理 服务
     * @return MerchantGroupService
     */
    public function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
    }

    /**
     * 费用 服务
     * @return FeeService
     */
    public function getFeeService()
    {
        return self::getInstance(FeeService::class);
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
     * 打印模板 服务
     * @return PrintTemplateService
     */
    public function getPrintTemplateService()
    {
        return self::getInstance(PrintTemplateService::class);
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
     * 地址 服务
     * @return AddressService
     */
    public function getAddressService()
    {
        return self::getInstance(AddressService::class);
    }

    /**
     * 司机事件 服务
     * @return TourDriverService
     */
    public function getTourDriverService()
    {
        return self::getInstance(TourDriverService::class);
    }

    /**
     * 员工 服务
     * @return EmployeeService
     */
    public function getEmployeeService()
    {
        return self::getInstance(EmployeeService::class);
    }

    /**
     * 权限组 服务
     * @return RoleService
     */
    public function getRoleService()
    {
        return self::getInstance(RoleService::class);
    }

    /**
     * 三方请求计数服务
     * @return ApiTimesService
     */
    public function getApiTimesService()
    {
        return self::getInstance(ApiTimesService::class);
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
     * 获取谷歌服务
     * @return GoogleApiService
     */
    public function getGoogleApiService()
    {
        return self::getInstance(GoogleApiService::class);
    }

    /**
     * 货主组线路服务
     * @return MerchantGroupLineService
     */
    public function getMerchantGroupLineService()
    {
        return self::getInstance(MerchantGroupLineService::class);
    }

    /**
     * 获得车辆维修详情 Service
     * @return CarMaintainDetailService
     */
    public function getCarMaintainDetailService()
    {
        return self::getInstance(CarMaintainDetailService::class);
    }

    /**
     * 获取备品库存 Service
     * @return SparePartsStockService
     */
    public function getSparePartsStockService()
    {
        return self::getInstance(SparePartsStockService::class);
    }
}
