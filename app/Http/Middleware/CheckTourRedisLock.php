<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use App\Traits\TourRedisLockTrait;
use Closure;

class CheckTourRedisLock
{
    use TourRedisLockTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, Closure $next)
    {
        if (!$request->tour_no) {
            throw new BusinessLogicException('未传入取件线路编号');
        }
        app('log')->debug('经过中间件,当前的 :tour_no 为:' . ($request->tour_no ?? ''));
        if (self::getTourLock($request->tour_no)) {
            throw new BusinessLogicException('当前取件线路已锁定,请稍后操作');
        }
        try {
            $response = $next($request);
        } catch (\Exception $e) {
            self::setTourLock($request->tour_no, 0);
            throw $e;
        }
        if (method_exists($response, 'getData') && isset($response->getData()->code) && $response->getData()->code != 200) {
            self::setTourLock($request->tour_no, 0);
        }
        return $response;
    }
}
