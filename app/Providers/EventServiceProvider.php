<?php

namespace App\Providers;

use App\Events\AfterDriverLocationUpdated;
use App\Events\AfterTourInit;
use App\Events\AfterTourUpdated;
use App\Events\TourDriver\BackWarehouse;
use App\Events\TourDriver\BatchArrived;
use App\Events\TourDriver\BatchDepart;
use App\Events\TourDriver\OutWarehouse;
use App\Listeners\CountTourTimeAndDistance;
use App\Listeners\TourDriver;
use App\Listeners\UpdateDriverCountTime;
use App\Listeners\UpdateLineCountTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        //司机位置更新事件
        AfterDriverLocationUpdated::class => [
            UpdateDriverCountTime::class,
        ],
        //
        AfterTourInit::class    =>  [
            CountTourTimeAndDistance::class,
        ],
        //更新线路触发事件
        AfterTourUpdated::class => [
            UpdateLineCountTime::class,
        ],
        /*********************************取件线路-司机****************************************/
        //司机出库
        OutWarehouse::class => [
            TourDriver::class,
        ],
        //司机到达站点
        BatchArrived::class => [
            TourDriver::class,
        ],
        //司机从站点出发
        BatchDepart::class => [
            TourDriver::class,
        ],
        //司机回仓
        BackWarehouse::class => [
            TourDriver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
