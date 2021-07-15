<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/9/15
 * Time: 14:56
 */

namespace App\Services\ApiServices;

use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;

class TourOptimizationService
{
    use FactoryInstanceTrait;

    public static function getOpInstance($companyId)
    {
        $company = CompanyTrait::getCompany($companyId);
        if (!empty($company['map_config']['back_type']) && ($company['map_config']['back_type'] == BaseConstService::MAP_CONFIG_BACK_TYPE_3)) {
            return self::getInstance(TenCentApiService::class);
        } else {
            return self::getInstance(GoogleApiService2::class);
        }
    }

    public static function getDistanceInstance($companyId)
    {
        $company = CompanyTrait::getCompany($companyId);
        if (!empty($company['map_config']['back_type']) && ($company['map_config']['back_type'] == BaseConstService::MAP_CONFIG_BACK_TYPE_3)) {
            return self::getInstance(TenCentApiDistanceService::class);
        } else {
            return self::getInstance(GoogleApiDistanceService::class);
        }
    }
}
