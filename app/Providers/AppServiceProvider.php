<?php

namespace App\Providers;

use App\Channels\Notifications\JPushChannel;
use App\Services\RedisService;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use JPush\Client as JPushClient;
use Tymon\JWTAuth\JWTGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('redis-service', function ($app) {
            return new RedisService();
        });

        $this->app->singleton(JPushClient::class, function ($app) {
            $options = [
                $app->config->get('jpush.key'),
                $app->config->get('jpush.secret'),
                $app->config->get('jpush.log')
            ];
            return new JPushClient(...$options);
        });

        if ($this->app->isLocal() || ($this->app->environment() === 'development')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        /*
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
        */

        //验证类扩展
        Validator::extend('uniqueIgnore', 'App\\Http\\Validate\\BaseValidate@uniqueIgnore');
        Validator::extend('checkIdList', 'App\\Http\\Validate\\BaseValidate@checkIdList');
        Validator::extend('checkAddress', 'App\\Http\\Validate\\BaseValidate@checkAddress');
        Validator::extend('checkSpecialChar', 'App\\Http\\Validate\\BaseValidate@checkSpecialChar');

        JWTGuard::macro('setUserNull', function () {
            $this->user = null;
            return null;
        });

        if (Str::startsWith(env('APP_URL'), 'https')) {
            $url->forceScheme('https');
        }
    }
}
