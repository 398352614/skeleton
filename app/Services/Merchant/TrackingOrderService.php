<?php
/**
 * 运单 服务
 * User: long
 * Date: 2020/10/28
 * Time: 11:33
 */

namespace App\Services\Merchant;


use App\Models\TrackingOrder;
use App\Services\BaseService;

class TrackingOrderService extends BaseService
{
    public function __construct(TrackingOrder $trackingOrder, $resource = null, $infoResource = null)
    {
        parent::__construct($trackingOrder, $resource, $infoResource);
    }
}