<?php

namespace App\Http\Middleware;

use App\Exceptions\BusinessLogicException;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public $except = [
        'driver.logout'
    ];

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string[] ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $routeName = $request->route()->getName();
        if (!in_array($routeName, $this->except)) {
            $this->authenticate($request, $guards);
        }
        return $next($request);
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws BusinessLogicException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new BusinessLogicException('用户认证失败', 2001);
    }
}
