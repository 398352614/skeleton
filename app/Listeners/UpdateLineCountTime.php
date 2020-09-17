<?php

namespace App\Listeners;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TourOptimizationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateLineCountTime
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 更新取件线路
     *
     * @param AfterTourUpdated $event
     * @throws BusinessLogicException
     */
    public function handle(AfterTourUpdated $event)
    {
        app('log')->info('更新线路出发事件进入此处');
        TourOptimizationService::getOpInstance($event->tour->company_id)->updateTour($event->tour, $event->nextBatch);
    }
}
