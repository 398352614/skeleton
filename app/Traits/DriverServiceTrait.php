<?php


namespace App\Traits;


use App\Services\Admin\AdditionalPackageService;
use App\Services\Admin\TourDelayService;
use App\Services\Driver\BatchExceptionService;
use App\Services\Driver\BatchService;
use App\Services\Driver\CarService;
use App\Services\Driver\DeviceService;
use App\Services\Driver\LineService;
use App\Services\Driver\MaterialService;
use App\Services\Driver\MerchantService;
use App\Services\Driver\OrderService;
use App\Services\Driver\PackageService;
use App\Services\Driver\RechargeService;
use App\Services\Driver\RechargeStatisticsService;
use App\Services\Driver\TourService;
use App\Services\Driver\TourTaskService;
use App\Services\OrderNoRuleService;

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
     * @return OrderNoRuleService
     */
    public function getOrderNoRuleService()
    {
        return self::getInstance(OrderNoRuleService::class);
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
     * 任务 服务
     * @return TourTaskService
     */
    public function getTourTaskService()
    {
        return self::getInstance(TourTaskService::class);
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
     * 取件线路服务
     * @return TourService
     */
    public function getTourService()
    {
        return self::getInstance(\App\Services\Admin\TourService::class);
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

}
