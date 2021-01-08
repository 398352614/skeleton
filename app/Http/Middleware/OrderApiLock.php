<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use Closure;
use Illuminate\Support\Facades\Cache;

class OrderApiLock
{

    public $times = 20;

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, Closure $next)
    {
        $outGroupOrderNo = $request->get('out_group_order_no');
        if (!empty($outGroupOrderNo)) {
            $lock = Cache::lock($outGroupOrderNo, 5);
            $value = null;
            for ($i = 1; $i <= $this->times; $i++) {
                $value = $lock->get();
                if (empty($value)) {
                    usleep(100);
                    continue;
                }
                break;
            }
            if (empty($value)) {
                throw new BusinessLogicException('系统繁忙，请稍后重试');
            }

            if ($lock->get()) {
                // 获取锁定10秒...
                $lock->release();
            }
        }
        return $next($request);
    }
}
