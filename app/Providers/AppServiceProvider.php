<?php

namespace App\Providers;

use App\Services\CurlClient;
use App\Services\GoogleApiService;
use App\Services\ReisService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('reis', function($app){
            return new ReisService($app);
        });

        $this->app->singleton('curl', function($app){
            return new CurlClient($app);
        });

        $this->app->bind(CurlClient::class, function($app){
            return new CurlClient($app);
        });

        $this->app->bind(ReisService::class, function($app){
            return new ReisService($app);
        });

        $this->app->bind(GoogleApiService::class, function($app){
            return new GoogleApiService($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //验证类扩展
        Validator::extend('uniqueIgnore', 'App\\Http\\Validate\\BaseValidate@uniqueIgnore');
    }
}
