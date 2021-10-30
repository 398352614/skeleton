<?php

namespace App\Listeners;

use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Services\ApiServices\TourOptimizationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        if (empty($event->tour->company_id)) {
            $companyId = $event->tour['company_id'];
        } else {
            $companyId = $event->tour->company_id;
        }
        Log::info($companyId);
        TourOptimizationService::getOpInstance($event->tour->company_id)->updateTour($event->tour, $event->nextBatch);
    }
}
