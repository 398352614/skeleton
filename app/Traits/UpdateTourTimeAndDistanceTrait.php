<?php

namespace App\Traits;

use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;

trait UpdateTourTimeAndDistanceTrait
{
    public function updateTourTimeAndDistance($tour): bool
    {
        $info = $this->apiClient->LineInfo($tour->tour_no);
        if (!$info || $info['ret'] == 0) { // 返回错误的情况下直接返回
            app('log')->info('更新动作失败,错误信息为:' . $info['msg']);
            return false;
        }
        $data = $info['data'];

        app('log')->info('开始更新线路,线路标识为:' . $tour->tour_no);
        app('log')->info('api返回的结果为:', $info);

        TourLog::where('tour_no', $tour->tour_no)->where('action', $tour->tour_no)->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
        $tour = Tour::where('tour_no', $tour->tour_no)->first();
        $max_time = 0;
        $max_distance = 0;

        foreach ($data['loc_res'] as $key => $res) {
            $tourBatch = Batch::where('batch_no', str_replace($tour->tour_no, '', $key))->where('tour_no', $tour->tour_no)->first();
            $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', $data['timestamp'] + $res['time']);
            $tourBatch->expect_distance = $res['distance'];
            $tourBatch->save();
            $max_time = max($max_time, $res['time']);
            $max_distance = max($max_distance, $res['distance']);
        }

        if ($tour->expect_time == 0) { // 只有未更新过的线路需要更新期望时间和距离
            $tour->expect_time = $max_time;
            $tour->expect_distance = $max_distance;
            $tour->save();
        }
        $tour->lave_distance = $max_distance;

        app('log')->info('更新线路完成,线路标识为:' . $tour->tour_no);
        return true;
    }
}
