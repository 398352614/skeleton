<?php

namespace App\Listeners;

use App\Events\AfterDriverLocationUpdated;
use App\Exceptions\BusinessLogicException;
use App\Services\GoogleApiService;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function handle(AfterDriverLocationUpdated $event)
    {
        app('log')->info('更新司机位置进入此处');
        $tour = $event->tour;
        $driverLocation = $event->location; // 司机位置数组
        $nextBatchNo = $event->nextBatchNo; // 下一个站点的唯一标识

        $this->apiClient = new GoogleApiService;

        //需要验证上一次操作是否完成,不可多次修改数据,防止数据混乱

        if ($tour) {
            app('log')->info('存在在途任务,更新');
            if (!$driverLocation) {
                //此处需要考虑事件没有传入司机位置的情况,此时查找司机位置
            }

            $data = [
                "latitude" => $driverLocation['latitude'],
                "longitude" => $driverLocation['longitude'],
                "target_code" => $nextBatchNo,
                "line_code" => $tour->tour_no,
            ];

            $res = $this->apiClient->PushDriverLocation($data);

            app('log')->info('更新司机位置的结果为:', $res ?? []);

            if (!$this->updateTourTimeAndDistance($tour)) {
                throw new BusinessLogicException('更新线路失败');
            }
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
