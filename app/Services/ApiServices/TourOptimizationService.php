<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/9/15
 * Time: 14:56
 */

namespace App\Services\ApiServices;

use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;

class TourOptimizationService
{
    use FactoryInstanceTrait;

    public static function getOpInstance($companyId)
    {
        $company = CompanyTrait::getCompany($companyId);
        if (!empty($company['map']) && ($company['map'] == 'google')) {
            return self::getInstance(GoogleApiService2::class, $companyId);
        } else {
            return self::getInstance(TenCentApiService::class, $companyId);
        }
    }

    public static function getDistanceInstance($companyId)
    {
        $company = CompanyTrait::getCompany($companyId);
        if (!empty($company['map']) && ($company['map'] == 'google')) {
            return self::getInstance(GoogleApiDistanceService::class);
        } else {
            return self::getInstance(TenCentApiDistanceService::class);
        }
    }
}
