<?php

namespace App\Traits;

use App\Services\Admin\OrderAmountService;
use App\Services\Admin\OrderDefaultConfigService;
use App\Services\Merchant\AddressService;
use App\Services\Merchant\BatchExceptionService;
use App\Services\Merchant\BatchService;
use App\Services\Merchant\CarService;
use App\Services\Merchant\DriverService;
use App\Services\Merchant\LineAreaService;
use App\Services\Merchant\LineRangeService;
use App\Services\Merchant\LineService;
use App\Services\Merchant\MaterialService;
use App\Services\Merchant\MerchantGroupLineService;
use App\Services\Merchant\MerchantGroupService;
use App\Services\Merchant\MerchantGroupLineRangeService;
use App\Services\Merchant\MerchantService;
use App\Services\Merchant\OrderService;
use App\Services\Merchant\PackageService;
use App\Services\Merchant\RouteTrackingService;
use App\Services\Merchant\TourDriverService;
use App\Services\Merchant\TourService;
use App\Services\Merchant\TrackingOrderMaterialService;
use App\Services\Merchant\TrackingOrderPackageService;
use App\Services\Merchant\TrackingOrderService;
use App\Services\Merchant\TransportPriceService;
use App\Services\Merchant\UploadService;
use App\Services\Merchant\WareHouseService;
use App\Services\OrderNoRuleService;
use App\Services\PackageNoRuleService;

Trait MerchantServiceTrait
{
    use FactoryInstanceTrait;

    /**
     * 商家 服务
     * @return MerchantService
     */
    public function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
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
     * 仓库 服务
     * @return WareHouseService
     */
    public function getWareHouseService()
    {
        return self::getInstance(WareHouseService::class);
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
