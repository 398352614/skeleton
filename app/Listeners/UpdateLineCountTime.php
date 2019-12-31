<?php

namespace App\Listeners;

use App\Events\AfterTourUpdated;
use App\Services\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLineCountTime 
{
    /**
     * @var GoogleApiService
     */
    public $apiClient;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(GoogleApiService $client)
    {
        $this->apiClient = $client;
    }

    /**
     * Handle the event.
     *
     * @param  AfterTourUpdated  $event
     * @return void
     */
    public function handle($event)
    {
        app('log')->info('更新线路出发事件进入此处');
        $tour = $event->tour; // 获取事件对应的线路
        $res = $this->apiClient->UpdateTour($tour, $event->nextBatch);
        app('log')->info('更新线路的返回结果为:', $res);
    }
}
