<?php

namespace App\Traits;


use Illuminate\Support\Facades\Cache;

trait OrderRedisLockTrait
{
    /**
     * 同步锁,同步操作同时只能存在一个!!!
     * @param string $orderNo
     * @return int
     */
    public static function getOrderLock(string $orderNo)
    {
        return Cache::get('synchronize' . $orderNo);
    }

    public static function setOrderLock(string $orderNo)
    {
        Cache::add('synchronize' . $orderNo, 1, 60);
    }
}
