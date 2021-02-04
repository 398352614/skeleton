<?php

namespace App\Listeners;

use App\Events\AfterDriverLocationUpdated;
use App\Events\AfterTourUpdated;
use App\Exceptions\BusinessLogicException;
use App\Services\ApiServices\TourOptimizationService;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public $timeout = 30;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

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

    /**
     * 确定监听器是否应加入队列
     *
     * @param \App\Events\AfterDriverLocationUpdated $event
     * @return bool
     */
    public function shouldQueue(AfterTourUpdated $event)
    {
        return ($event->queue === true);
    }
}
