<?php
/**
 * 运单材料表
 * User: long
 * Date: 2020/12/1
 * Time: 17:03
 */

namespace App\Services\Merchant;

use App\Models\TrackingOrderMaterial;

class TrackingOrderMaterialService extends BaseService
{
    public function __construct(TrackingOrderMaterial $trackingOrderMaterial, $resource = null, $infoResource = null)
    {
        parent::__construct($trackingOrderMaterial, $resource, $infoResource);
    }
}