<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use Closure;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard !== 'admin') return $next($request);

        $prefix = $request->route()->getPrefix();
        if (in_array($prefix, ['common', 'upload'])) return $next($request);

        $routeAs = $request->route()->getName();
        $isAuth = auth()->user()->can($routeAs);
        if ($isAuth === false) {
            throw new BusinessLogicException('当前用户没有该权限');
        }
        return $next($request);
    }
}
