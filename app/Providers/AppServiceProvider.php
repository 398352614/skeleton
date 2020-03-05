<?php

namespace App\Providers;

use App\Services\CurlClient;
use App\Services\GoogleApiService;
use App\Services\ReisService;
use Illuminate\Support\Facades\DB;
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
        $this->app->singleton('reis', function ($app) {
            return new ReisService($app);
        });

        $this->app->singleton('curl', function ($app) {
            return new CurlClient($app);
        });

        $this->app->bind(CurlClient::class, function ($app) {
            return new CurlClient($app);
        });

        $this->app->bind(ReisService::class, function ($app) {
            return new ReisService($app);
        });

        $this->app->bind(GoogleApiService::class, function ($app) {
            return new GoogleApiService($app);
        });

        if ($this->app->isLocal() || $this->app->environment() === 'development') {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') === 'local') {
            DB::listen(
                function ($sql) {
                    foreach ($sql->bindings as $i => $binding) {
                        if ($binding instanceof \DateTime) {
                            $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                        } else {
                            if (is_string($binding)) {
                                $sql->bindings[$i] = "'$binding'";
                            }
                        }
                    }

                    // Insert bindings into query
                    $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);

                    $query = vsprintf($query, $sql->bindings);

                    // Save the query to file
                    $logFile = fopen(
                        storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_query.log'),
                        'a+'
                    );
                    fwrite($logFile, date('Y-m-d H:i:s') . ': ' . $query . PHP_EOL);
                    fclose($logFile);
                }
            );
        }

        //验证类扩展
        Validator::extend('uniqueIgnore', 'App\\Http\\Validate\\BaseValidate@uniqueIgnore');
    }
}
