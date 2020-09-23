<?php

namespace App\Listeners;

use App\Events\AfterDriverLocationUpdated;
use App\Events\TourNotify\NextBatch;
use App\Exceptions\BusinessLogicException;
use App\Services\Admin\ApiTimesService;
use App\Services\ApiServices\GoogleApiService;
use App\Services\ApiServices\TourOptimizationService;
use App\Traits\FactoryInstanceTrait;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDriverCountTime implements ShouldQueue
{
    use UpdateTourTimeAndDistanceTrait, Dispatchable, InteractsWithQueue, SerializesModels;

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
    public $timeout = 30;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    public $apiClient;


    /**
     * 更新预计到达时间
     *
     * @param AfterDriverLocationUpdated $event
     * @throws BusinessLogicException
     */
    public function handle(AfterDriverLocationUpdated $event)
    {

        try {
            $tour = $event->tour;
            $driverLocation = $event->location;
            $nextBatchNo = $event->nextBatchNo;
            TourOptimizationService::getOpInstance($tour->company_id)->updateDriverLocation($tour, $driverLocation, $nextBatchNo, $event->queue);
            $service = FactoryInstanceTrait::getInstance(ApiTimesService::class);
            $service->timesCount('distance_times', $tour->company_id);
            //通知下一个站点事件
            if ($event->notifyNextBatch == true) {
                event(new NextBatch($tour->toArray(), ['batch_no' => $nextBatchNo]));
            }
            $service = FactoryInstanceTrait::getInstance(ApiTimesService::class);
            $service->timesCount('actual_distance_times', $tour->company_id);
            Log::info('司机位置和各站点预计耗时和里程更新成功');
        } catch (\Exception $ex) {
            Log::channel('job-daily')->error('更新线路失败:' . $ex->getFile());
            Log::channel('job-daily')->error('更新线路失败:' . $ex->getLine());
            Log::channel('job-daily')->error('更新线路失败:' . $ex->getMessage());
            throw new BusinessLogicException('更新线路失败');
        }
        return;
    }

    /**
     * 确定监听器是否应加入队列
     *
     * @param \App\Events\AfterDriverLocationUpdated $event
     * @return bool
     */
    public function shouldQueue(AfterDriverLocationUpdated $event)
    {
        return ($event->queue === true);
    }
}
