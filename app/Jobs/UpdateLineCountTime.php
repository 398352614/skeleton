<?php

namespace App\Jobs;

use App\Models\OrderTrail;
use App\Models\Tour;
use App\Models\TourDriverEvent;
use App\Models\TrackingOrderTrail;
use App\Services\ApiServices\TourOptimizationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateLineCountTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * 任务连接名称。
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务发送到的队列的名称.
     *
     * @var string|null
     */
    public $queue = 'location';

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    public $tour;

    public $nextBatchCode;


    /**
     * addTrail constructor.
     * @param $tour
     * @param $nextBatchCode
     */
    public function __construct(Tour $tour, $nextBatchCode)
    {
        $this->tour = $tour;
        $this->nextBatchCode = $nextBatchCode;
    }


    /**
     * 触发入库分拣队列
     * Execute the job.
     */
    public function handle()
    {
        Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '更新线路开始');
        if (empty($this->tour->company_id)) {
            $companyId = $this->tour['company_id'];
        } else {
            $companyId = $this->tour->company_id;
        }
        Log::info($companyId);
        TourOptimizationService::getOpInstance($companyId)->updateTour($this->tour, $this->nextBatchCode);
    }

}
