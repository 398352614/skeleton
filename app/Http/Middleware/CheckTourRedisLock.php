<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use App\Services\Traits\TourRedisLockTrait;
use Closure;

class CheckTourRedisLock
{
    use TourRedisLockTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->tour_no) {
            throw new BusinessLogicException('未传入 tour_no');
        }
        if (self::getTourLock($request->tour_no)) {
            throw new BusinessLogicException('当前 tour 已锁定,请稍后操作');
        }
        return $next($request);
    }
}
