<?php

namespace App\Traits;

use App\Services\Admin\AdditionalPackageService;
use App\Services\Admin\AddressService;
use App\Services\Admin\AddressTemplateService;
use App\Services\Admin\ApiTimesService;
use App\Services\Admin\BaseLineService;
use App\Services\Admin\BatchExceptionService;
use App\Services\Admin\BatchService;
use App\Services\Admin\CarBrandService;
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
use App\Services\Admin\MerchantGroupService;
use App\Services\Admin\MerchantLineRangeService;
use App\Services\Admin\MerchantRechargeService;
use App\Services\Admin\MerchantService;
use App\Services\Admin\OrderService;
use App\Services\Admin\PackageService;
use App\Services\Admin\PrintTemplateService;
use App\Services\Admin\RechargeService;
use App\Services\Admin\RechargeStatisticsService;
use App\Services\Admin\StockInLogService;
use App\Services\Admin\StockOutLogService;
use App\Services\Admin\StockService;
use App\Services\Admin\TourDriverService;
use App\Services\Admin\TourService;
use App\Services\Admin\TrackingOrderService;
use App\Services\Admin\TransportPriceService;
use App\Services\Admin\UploadService;
use App\Services\Admin\WareHouseService;
use App\Services\OrderNoRuleService;

trait AdminServiceTrait
{
    use FactoryInstanceTrait;

    public function getCompanyService()
    {
        return self::getInstance(CompanyService::class);
    }

    /**
     * 商户服务
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
     * 商户线路范围 服务
     * @return MerchantLineRangeService
     */
    public function getMerchantLineRangeService()
    {
        return self::getInstance(MerchantLineRangeService::class);
    }

    /**
     * 仓库 服务
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
     * @return mixed
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
     * 商户api 服务
     * @return MerchantApiService
     */
    public function getMerchantApiService()
    {
        return parent::getInstance(MerchantApiService::class);
    }

    /**
     * 商户充值api 服务
     * @return MerchantRechargeService
     */
    public function getMerchantRechargeService()
    {
        return parent::getInstance(MerchantRechargeService::class);
    }

    /**
     * 商户组管理 服务
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
     * @return RechargeService
     */
    public function getRechargeService()
    {
        return self::getInstance(RechargeService::class);
    }

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
     * 三方请求计数服务
     * @return ApiTimesService
     */
    public function getApiTimesService()
    {
        return self::getInstance(ApiTimesService::class);
    }
}
