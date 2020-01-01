<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/30
 * Time: 10:58
 */

namespace App\Services\Driver;


use App\Models\Order;
use App\Services\BaseService;

class OrderService extends BaseService
{
    public function __construct(Order $order)
    {
        $this->request = request();
        $this->model = $order;
        $this->query = $this->model::query();
    }
}