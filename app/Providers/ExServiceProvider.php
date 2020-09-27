<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/9/22
 * Time: 15:43
 */

namespace App\Providers;


use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TenCentApiService;
use App\Services\RedisService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ExServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public function register()
    {
        $this->app->singleton('google-api', function ($app) {
            return new GoogleApiService();
        });

        $this->app->singleton('tencent-api', function ($app) {
            return new TenCentApiService();
        });

//        $this->app->singleton('message-service', function ($app) {
//            return new MessageService();
//        });

    }

    public function provides()
    {
        return ['google-api', 'tencent-api'];
    }

}