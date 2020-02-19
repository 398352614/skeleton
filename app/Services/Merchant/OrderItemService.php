<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;


use App\Models\OrderItem;
use App\Services\BaseService;

class OrderItemService extends BaseService
{
    public function __construct(OrderItem $orderItem)
    {
        $this->model = $orderItem;
        $this->query = $this->model::query();
    }
}
