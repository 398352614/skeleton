<?php

namespace App\Traits;

trait TourRedisLockTrait
{
    /**
     * 在途锁,更新操作同时只能存在一个!!!
     */
    public static function getTourLock(string $tourNo): int
    {
        $lock = app('redis-service')->get('tourUpdateOpration' . $tourNo);
        if ($lock === null) {
            return 0;
        }
        return (int)$lock;
    }

    public static function setTourLock(string $tourNo, int $value)
    {
        return app('redis-service')->put('tourUpdateOpration' . $tourNo, $value);
    }
}
