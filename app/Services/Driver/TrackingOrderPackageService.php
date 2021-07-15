<?php
/**
 * 运单包裹表
 * User: long
 * Date: 2020/12/1
 * Time: 16:59
 */

namespace App\Services\Driver;

use App\Models\TrackingOrderPackage;

class TrackingOrderPackageService extends BaseService
{
    public function __construct(TrackingOrderPackage $trackingOrderPackage, $resource = null, $infoResource = null)
    {
        parent::__construct($trackingOrderPackage, $resource, $infoResource);
    }

}
