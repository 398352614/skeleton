<?php

namespace App\Providers;

use App\Events\AfterDriverLocationUpdated;
use App\Events\AfterTourBatchAssign;
use App\Events\AfterTourInit;
use App\Events\AfterTourUpdated;
use App\Events\DriverArriveBatch;
use App\Listeners\CountTourTimeAndDistance;
use App\Listeners\CreateTourDriverEvent;
use App\Listeners\UpdateDriverCountTime;
use App\Listeners\UpdateLineCountTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        DriverArriveBatch::class => [
            CreateTourDriverEvent::class,
        ],
        AfterTourBatchAssign::class => [
            CreateTourDriverEvent::class,
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

        //
    }
}
