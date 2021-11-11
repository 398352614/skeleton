<?php

namespace App\Providers;

use App\Services\Auth\AdminApiGuard;
use App\Services\Auth\EloquentAdminApiProvider;
use App\Services\Auth\EloquentMerchantApiProvider;
use App\Services\Auth\MerchantApiGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Auth::extend('merchant_api', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new MerchantApiGuard(Auth::createUserProvider($config['provider']), $this->app['request']);
        });

        Auth::provider('eloquent_merchant_api', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new EloquentMerchantApiProvider(new \App\Hash\MerchantApi(), $config['model']);
        });

        Auth::extend('admin_api', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new AdminApiGuard(Auth::createUserProvider($config['provider']), $this->app['request']);
        });

        Auth::provider('eloquent_admin_api', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new EloquentAdminApiProvider(new \App\Hash\MerchantApi(), $config['model']);
        });

        Auth::extend('merchant_h5', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return new MerchantApiGuard(Auth::createUserProvider($config['provider']), $this->app['request']);
        });
    }
}
