<?php

namespace App\Listeners;

use App\Events\AfterTourInit;
use App\Services\ApiServices\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CountTourTimeAndDistance implements ShouldQueue
{
    /**
     * @var GoogleApiService
     */
    public $apiClient;

    /**
     * Create the event listener.
     *
     * @param GoogleApiService $client
     */
    public function __construct(GoogleApiService $client)
    {
        $this->apiClient = $client;
    }

    /**
     * Handle the event.
     *
     * @param  AfterTourInit  $event
     * @return void
     */
    public function handle($event)
    {

        app('log')->info('初始化tour出发事件进入此处');
        $tour = $event->tour; // 获取事件对应的线路
        app('log')->info('线路标识为:' . $tour->tour_no);
        $res = $this->apiClient->InitTour($tour);
        app('log')->info('初始化tour的返回结果为:', $res);
    }
}
