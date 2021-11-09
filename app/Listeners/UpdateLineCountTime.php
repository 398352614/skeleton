<?php

namespace App\Listeners;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Services\ApiServices\TourOptimizationService;
use Illuminate\Support\Facades\Log;

class UpdateLineCountTime
{
    /**
     * 更新线路任务
     *
     * @param AfterTourUpdated $event
     * @throws BusinessLogicException
     */
    public function handle(AfterTourUpdated $event)
    {
        Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '更新线路出发事件');

        TourOptimizationService::getOpInstance($event->tour->company_id)->updateTour($event->tour, $event->nextBatch);
    }
}
