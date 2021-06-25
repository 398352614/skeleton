<?php

namespace App\Listeners;

use App\Events\AfterDriverLocationUpdated;
use App\Events\TourNotify\NextBatch;
use App\Exceptions\BusinessLogicException;
use App\Models\RouteRetry;
use App\Services\Admin\ApiTimesService;
use App\Services\ApiServices\TourOptimizationService;
use App\Traits\FactoryInstanceTrait;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

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
            $queue = $event->queue;
            TourOptimizationService::getOpInstance($tour->company_id)->updateDriverLocation($tour, $driverLocation, $nextBatchNo, $queue);
            $service = FactoryInstanceTrait::getInstance(ApiTimesService::class);
            $service->timesCount('distance_times', $tour->company_id);
            //通知下一个站点事件
            if ($event->notifyNextBatch == true) {
                event(new NextBatch($tour->toArray(), ['batch_no' => $nextBatchNo]));
            }
            $service = FactoryInstanceTrait::getInstance(ApiTimesService::class);
            $service->timesCount('actual_distance_times', $tour->company_id);
            //清空路线重试任务
            $row = RouteRetry::query()->where('tour_no', $tour['tour_no'])->delete();
            if ($row == true) {
                Log::channel('roll')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '线路重试任务已清空');
            } else {
                Log::channel('roll')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '线路重试任务清空失败');
            }
            Log::channel('worker')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '司机位置和各站点预计耗时和里程更新成功');
        } catch (\Exception $e) {
            Log::channel('work')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            //计入路线重推
            $this->repush($tour, $driverLocation, $nextBatchNo, $queue);
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

    public function repush($tour, $driverLocation, $nextBatchNo, $queue)
    {
        $row = RouteRetry::query()->create([
            'company_id' => $tour->company_id,
            'tour_no' => $tour->tour_no,
            'retry_times' => 0,
            'data' => json_encode([
                'tour' => $tour,
                'driver_location' => $driverLocation,
                'next_batch_no' => $nextBatchNo,
                'queue' => $queue
            ])
        ]);
        if ($row == false) {
            Log::channel('roll')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '计入路线重推失败');
        }
    }
}
