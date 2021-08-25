<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Permission
{

    /**
     * Handle an incoming request.
     *
     * @param
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     * @throws BusinessLogicException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        return $next($request);
        if ($guard !== 'admin') return $next($request);

        $prefix = $request->route()->getPrefix();
        if (in_array($prefix, ['api/admin/common', 'api/admin/upload'])) return $next($request);
        $routeAs = $request->route()->getName();
        if (empty($routeAs) || ($routeAs === 'common')) return $next($request);
        //Log::channel('roll')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'route-as', [$routeAs]);
        $isAuth = auth()->user()->can($routeAs);
        if ($isAuth === false) {
            throw new BusinessLogicException('当前用户没有该权限，请按F5刷新页面');
        }
        return $next($request);
    }
}
