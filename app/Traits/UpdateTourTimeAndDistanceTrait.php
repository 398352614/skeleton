<?php

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Models\TourLog;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

trait UpdateTourTimeAndDistanceTrait
{
    use TourRedisLockTrait;

    public function updateTourTimeAndDistance($tour): bool
    {
        if (self::getTourLock($tour->tour_no) == 1) {
            throw new BusinessLogicException('当前 tour 正在操作中,请稍后操作');
        }
        try {
            self::setTourLock($tour->tour_no, 1);
            $info = $this->LineInfo($tour->tour_no);
            if (empty($info['ret']) || (!empty($info['ret']) && ($info['ret'] == 0))) { // 返回错误的情况下直接返回
                Log::info('更新动作失败,错误信息', $info ?? []);
                self::setTourLock($tour->tour_no, 0);
                return false;
            }
            $data = $info['data'];

            app('log')->info('开始更新线路,线路标识为:' . $tour->tour_no);
            app('log')->info('api返回的结果为:', $info);

            TourLog::where('tour_no', $tour->tour_no)->where('action', $tour->tour_no)->update(['status' => BaseConstService::TOUR_LOG_COMPLETE]); // 日志标记为已完成
            $tour = Tour::where('tour_no', $tour->tour_no)->first();
            $max_time = 0;
            $max_distance = 0;
            $warehouse = [
                'warehouse_expect_distance' => 0,
                'warehouse_expect_time' => 0,
                'warehouse_expect_arrive_time' => null
            ];
            //若取件线路未结束，则仓库更新预计
            if (in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3, BaseConstService::TOUR_STATUS_4])) {
                $warehouse['warehouse_expect_arrive_time'] = date('Y-m-d H:i:s', time() + $data['loc_res'][$tour->tour_no . $tour->tour_no]['time']);
                $warehouse['warehouse_expect_distance'] = $data['loc_res'][$tour->tour_no . $tour->tour_no]['distance'];
                $warehouse['warehouse_expect_time'] = $data['loc_res'][$tour->tour_no . $tour->tour_no]['time'];
                Tour::query()->where('tour_no', $tour->tour_no)->update($warehouse);
            }
            unset($data['loc_res'][$tour->tour_no . $tour->tour_no]);
            $max_time = $max_distance = 0;
            foreach ($data['loc_res'] as $key => $res) {
                $tourBatch = Batch::where('batch_no', str_replace($tour->tour_no, '', $key))->where('tour_no', $tour->tour_no)->first();
                if (empty($tourBatch)) continue;
                //若站点未签收,则更新预计
                if (in_array(intval($tourBatch->status), [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING])) {
                    $tourBatch->expect_arrive_time = date('Y-m-d H:i:s', time() + $res['time']);
                    $tourBatch->expect_distance = $res['distance'];
                    $tourBatch->expect_time = $res['time'];
                }
                //更新出库预计
                if ($tour['actual_out_status'] == BaseConstService::YES && $tourBatch['status'] == BaseConstService::BATCH_DELIVERING) {
                    Log::info($tourBatch);
                    if (empty($tourBatch->out_expect_arrive_time)) {
                        $tourBatch->out_expect_arrive_time = date('Y-m-d H:i:s', time() + $res['time']);
                    }
                    if (empty($tourBatch->out_expect_distance)) {
                        $tourBatch->out_expect_distance = $res['distance'];
                    }
                    if (empty($tourBatch->out_expect_time)) {
                        $tourBatch->out_expect_time = $res['time'];
                    }
                }
                $tourBatch->save();
                $max_time = max($max_time, $res['time']);
                $max_distance = max($max_distance, $res['distance']);
            }
            Log::info(((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3]));
            // 只有未更新过的线路需要更新期望时间和距离
            if (
                ((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])
            ) {
                $tour->expect_time = max($max_time, $warehouse['warehouse_expect_time']);
                $tour->expect_distance = max($max_distance, $warehouse['warehouse_expect_distance']);
                $tour->save();
            }
            $tour->lave_distance = $max_distance;

            app('log')->info('更新线路完成,线路标识为:' . $tour->tour_no);
            self::setTourLock($tour->tour_no, 0);
            return true;
        } catch (\Exception $e) {
            self::setTourLock($tour->tour_no, 0);
            app('log')->info('updateTourTimeAndDistance错误-----:' . $e->getFile());
            app('log')->info('updateTourTimeAndDistance错误-----:' . $e->getLine());
            app('log')->info('updateTourTimeAndDistance错误-----:' . $e->getMessage());
            throw new BusinessLogicException('更新线路信息失败，请稍后重试');
        }
    }
}
