<?php

namespace App\Listeners;

use App\Events\AfterTourInit;
use App\Services\ApiServices\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

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
        Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '初始化线路');
        $tour = $event->tour; // 获取事件对应的线路
        $res = $this->apiClient->InitTour($tour);
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . '初始化线路返回结果',$res);
    }
}
