<?php

namespace App\Events\Interfaces;

use App\Models\Order;

interface ITourDriver
{
    /**
     * 获取线路司机事件文本
     */
    public function getContent(): string;

    /**
     * 获取当前司机事件的司机位置
     */
    public function getLocation(): array;

    /**
     * 获取当前司机事件的 tour_no
     */
    public function getTourNo(): string;
}
