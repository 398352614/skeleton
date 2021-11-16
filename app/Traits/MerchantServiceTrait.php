<?php

namespace App\Traits;

use App\Models\BillVerify;
use App\Services\Admin\BillVerifyService;
use App\Services\Admin\CompanyCustomizeService;
use App\Services\Merchant\AddressService;
use App\Services\Merchant\BaseLineService;
use App\Services\Merchant\BaseWarehouseService;
use App\Services\Merchant\BatchExceptionService;
use App\Services\Merchant\BatchService;
use App\Services\Merchant\BillService;
use App\Services\Merchant\CarService;
use App\Services\Merchant\CompanyService;
use App\Services\Merchant\CountryService;
use App\Services\Merchant\DriverService;
use App\Services\Merchant\FeeService;
use App\Services\Merchant\JournalService;
use App\Services\Merchant\LedgerLogService;
use App\Services\Merchant\LedgerService;
use App\Services\Merchant\LineAreaService;
use App\Services\Merchant\LineRangeService;
use App\Services\Merchant\LineService;
use App\Services\Merchant\MaterialService;
use App\Services\Merchant\MerchantApiService;
use App\Services\Merchant\MerchantGroupLineRangeService;
use App\Services\Merchant\MerchantGroupLineService;
use App\Services\Merchant\MerchantGroupService;
use App\Services\Merchant\MerchantService;
use App\Services\Merchant\OrderAmountService;
use App\Services\Merchant\OrderDefaultConfigService;
use App\Services\Merchant\OrderService;
use App\Services\Merchant\OrderTemplateService;
use App\Services\Merchant\OrderTrailService;
use App\Services\Merchant\PackageService;
use App\Services\Merchant\RouteTrackingService;
use App\Services\Merchant\StockService;
use App\Services\Merchant\TourDriverService;
use App\Services\Merchant\TourService;
use App\Services\Merchant\TrackingOrderMaterialService;
use App\Services\Merchant\TrackingOrderPackageService;
use App\Services\Merchant\TrackingOrderService;
use App\Services\Merchant\TrackingPackageService;
use App\Services\Merchant\TransportPriceService;
use App\Services\Merchant\UploadService;
use App\Services\Merchant\WareHouseService;
use App\Services\OrderNoRuleService;
use App\Services\PackageNoRuleService;

Trait MerchantServiceTrait
{
    use FactoryInstanceTrait;

    /**
     * 基础网点 服务
     * @return BaseWarehouseService
     */
    public function getBaseWarehouseService()
    {
        return self::getInstance(BaseWarehouseService::class);
    }

    /**
     * @return CompanyCustomizeService
     */
    public function getCompanyCustomizeService()
    {
        return self::getInstance(CompanyCustomizeService::class);

    }

    /**
     * @return MerchantApiService
     */
    public function getMerchantApiService()
    {
        return self::getInstance(MerchantApiService::class);
    }

    /**
     * @return JournalService
     */
    public function getJournalService()
    {
        return self::getInstance(JournalService::class);
    }

    /**
     * @return LedgerService
     */
    public function getLedgerService()
    {
        return self::getInstance(LedgerService::class);
    }

    /**
     * @return CompanyService
     */
    public function getCompanyService()
    {
        return self::getInstance(CompanyService::class);
    }

    /**
     * @return CountryService
     */
    public function getCountryService()
    {
        return self::getInstance(CountryService::class);
    }

    /**
     * @return LedgerLogService
     */
    public function getLedgerLogService()
    {
        return self::getInstance(LedgerLogService::class);
    }

    /**
     * @return BillService
     */
    public function getBillService()
    {
        return self::getInstance(BillService::class);
    }

    /**
     * @return BillVerifyService
     */
    public function getBillVerifyService()
    {
        return self::getInstance(BillVerifyService::class);
    }

    /**
     * @return FeeService
     */
    public function getFeeService()
    {
        return self::getInstance(FeeService::class);
    }

    /**
     * @return BaseLineService
     */
    public function getBaseLineService()
    {
        return self::getInstance(BaseLineService::class);
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
     * 库存 服务
     * @return StockService
     */
    public function getStockService()
    {
        return self::getInstance(StockService::class);
    }

    /**
     * 商家 服务
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * 订单轨迹 服务
     * @return OrderTrailService
     */
    public function getOrderTrailService()
    {
        return self::getInstance(OrderTrailService::class);
    }

    /**
     * 中转单 服务
     * @return TrackingPackageService
     */
    public function getTrackingPackageService()
    {
        return self::getInstance(TrackingPackageService::class);
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
     * 区域线路范围 服务
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
     * 线路任务 服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(TourService::class);
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
     * 单号规则 服务
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
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
     * 订单费用
     * @return OrderAmountService
     */
    public function getOrderAmountService()
    {
        return self::getInstance(OrderAmountService::class);
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
     * 订单默认配置 服务
     * @return OrderDefaultConfigService
     */
    public function getOrderDefaultConfigService()
    {
        return self::getInstance(OrderDefaultConfigService::class);
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
     * 站点(取件批次) 服务
     * @return BatchService
     */
    public function getBatchService()
    {
        return self::getInstance(BatchService::class);
    }

    /**
     * 上传 服务
     * @return mixed
     */
    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 线路追踪 服务
     * @return RouteTrackingService
     */
    public function getRouteTrackingService()
    {
        return self::getInstance(RouteTrackingService::class);
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
     * 货主组 服务
     * @return MerchantGroupService
     */
    public function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
    }

    /**
     * 地址服务
     * @return AddressService
     */
    public function getAddressService()
    {
        return self::getInstance(AddressService::class);
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
     * 运价方案 服务
     * @return TransportPriceService
     */
    public function getTransportPriceService()
    {
        return self::getInstance(TransportPriceService::class);
    }

    /**
     * 货主组线路 服务
     * @return MerchantGroupLineService
     */
    public function getMerchantGroupLineService()
    {
        return self::getInstance(MerchantGroupLineService::class);
    }
}
