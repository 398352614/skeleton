<?php

namespace App\Events\Interfaces;

use App\Models\Order;

interface OrderTrailChanged
{
    /**
     * 获取轨迹变化类型
     */
    public function getType(): int;

    public function getOrder(): Order;
}
