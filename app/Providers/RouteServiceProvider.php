<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        Route::pattern('id', '[0-9]+');
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        //员工端
        $this->mapAdminApiRoute();
        //管理员端API授权
        $this->mapAdminApiApiRoute();
        //司机端
        $this->mapDriverApiRoute();
        //商家端
        $this->mapMerchantApiRoute();
        //商家API授权
        $this->mapMerchantApiApiRoute();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    public function mapAdminApiRoute()
    {
        Route::prefix('api/admin')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_admin.php'));
    }

    public function mapAdminApiApiRoute()
    {
        Route::prefix('api/admin_api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_admin_api.php'));
    }

    public function mapMerchantApiRoute()
    {
        Route::prefix('api/merchant')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_merchant.php'));
    }

    public function mapMerchantApiApiRoute()
    {
        Route::prefix('api/merchant_api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_merchant_api.php'));
    }

    public function mapDriverApiRoute()
    {
        Route::prefix('api/driver')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_driver.php'));
    }
}
