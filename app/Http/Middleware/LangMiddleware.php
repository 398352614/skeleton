<?php

namespace App\Http\Middleware;

use Closure;

class LangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(app('request')->header('language') == 'nl'){
            app()->setLocale('nl');
        } elseif(app('request')->header('language') == 'en') {
            app()->setLocale('en');
        }else{
            app()->setLocale('cn');
        }
        return $next($request);
    }
}
