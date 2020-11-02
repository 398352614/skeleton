<?php
/**
 * 订单轨迹
 * User: long
 * Date: 2020/11/2
 * Time: 14:53
 */

namespace App\Services;


use App\Models\OrderTrail;

class OrderTrailService extends BaseService
{
    public function __construct(OrderTrail $orderTrail, $resource = null, $infoResource = null)
    {
        parent::__construct($orderTrail, $resource, $infoResource);
    }

    public function index($orderNo)
    {
        return parent::getList(['order_no' => $orderNo], ['*'], false);
    }

}