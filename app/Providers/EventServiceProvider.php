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
use App\Listeners\SendNotify2Merchant;
use App\Listeners\SendOrderCancel;
use App\Listeners\SendOrderDelete;
use App\Listeners\SendOrderExecutionDate;
use App\Listeners\TourDriver;
use App\Listeners\UpdateDriverCountTime;
use App\Listeners\UpdateLineCountTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Telescope\Telescope;
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
        AfterTourInit::class => [
            CountTourTimeAndDistance::class,
        ],
        //更新线路触发事件
        AfterTourUpdated::class => [
            UpdateLineCountTime::class,
        ],
        /*********************************线路任务-司机****************************************/
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
        /*********************************线路任务消息通知****************************************/
        \App\Events\TourNotify\OutWarehouse::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\ActualOutWarehouse::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\NextBatch::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\ArrivedBatch::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\AssignBatch::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\CancelBatch::class => [
            SendNotify2Merchant::class
        ],
        \App\Events\TourNotify\BackWarehouse::class => [
            SendNotify2Merchant::class
        ],
        /*********************************取派日期修改通知****************************************/
        \App\Events\OrderExecutionDateUpdated::class => [
            SendOrderExecutionDate::class
        ],
        \App\Events\OrderCancel::class => [
            SendOrderCancel::class
        ],
        \App\Events\OrderDelete::class => [
            SendOrderDelete::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Event::listen('laravels.received_request', function ($request, $app) {
            $reflection = new \ReflectionClass(Telescope::class);
            $handlingApprovedRequest = $reflection->getMethod('handlingApprovedRequest');
            $handlingApprovedRequest->setAccessible(true);
            $handlingApprovedRequest->invoke(null, $app) ? Telescope::startRecording() : Telescope::stopRecording();
        });
    }
}
