<?php

namespace App\Listeners;

use App\Events\AfterDriverLocationUpdated;
use App\Events\UpdateDriver;
use App\Model\Line;
use App\Model\LineLocation;
use App\Services\GoogleApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDriverCountTime implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AfterDriverLocationUpdated  $event
     * @return void
     */
    public function handle(AfterDriverLocationUpdated $event)
    {
        app('log')->info('更新司机位置进入此处');
        $tour = $event->tour;
        $driverLocation = $event->location; // 司机位置数组
        $nextBatchNo = $event->nextBatchNo; // 下一个站点的唯一标识

        $appClient = new GoogleApiService;

        //需要验证上一次操作是否完成,不可多次修改数据,防止数据混乱

        if ($tour) {
            app('log')->info('存在在途任务,更新');
            if (!$driverLocation) {
                //此处需要考虑事件没有传入司机位置的情况,此时查找司机位置
            }
            
            $data = [
                "latitude"      =>  $driverLocation['latitude'],
                "longitude"     =>  $driverLocation['longitude'],
                "target_code"   =>  $nextBatchNo,
                "line_code"     =>  $tour->tour_no,
            ];

            $res = $appClient->PushDriverLocation($data);

            app('log')->info('更新司机位置的结果为:', $res ?? []);
        }
    }
}
