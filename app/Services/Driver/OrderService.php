<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:58
 */

namespace App\Services\Driver;


use App\Models\Order;


class OrderService extends BaseService
{
    public function __construct(Order $order)
    {
        parent::__construct($order);

    }
}
