<?php
/**
 * 谷歌 API
 * User: long
 * Date: 2020/9/11
 * Time: 16:52
 */

namespace App\Services\ApiServices;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\BaseConstService;
use App\Services\BaseServices\XLDirectionService;
use App\Services\GoogleCurl;
use Illuminate\Support\Facades\Log;

class GoogleApiService
{
    const MAX_POINTS = 25;

    /**
     * GoogleApiService constructor.
     */
    public function __construct()
    {

    }

    /**
     * 优化(主功能)
     * @param Tour $tour
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function optimizeTour(Tour $tour)
    {
        /***********************@**********1.获取网点到所有站点的距离和时间********************************************/
        //获取并更新站点
        $batchList = $this->init($tour);
        $driverLocation = $this->getDriverLocation($tour);
        $batchList=array_merge([$driverLocation],$batchList);
        $batchNoList = (new XLDirectionService())->GetRoute($batchList);
        $this->updateBatch($batchNoList, $tour);
        $this->updateTour($tour);
    }

    /**
     * 获取司机位置
     * @param $tour
     * @return array
     */
    private function getDriverLocation($tour)
    {
        return [
            'batch_no' => 'driver_location',
            'place_lat' => $tour->driver_location['latitude'],
            'place_lon' => $tour->driver_location['longitude'],
        ];
    }

    /**
     * 根据任务获取站点
     * @param Tour $tour
     * @return array
     */
    private function init(Tour $tour)
    {
        //获取站点
        $keyIndex = 1;
        if ($tour->status != BaseConstService::TOUR_STATUS_4) { // 未取派的情况下,所有 batch 都是未取派的
            $orderBatchs = $tour->batchs;
        } else {
            $preBatchs = $tour->batchs()->where('status', '<>', BaseConstService::BATCH_DELIVERING)->get()->sortByDesc('sort_id'); // 已完成的包裹排序值放前面并不影响 -- 此处不包括未开始的
            foreach ($preBatchs as $preBatch) {
                $preBatch->update(['sort_id' => $keyIndex]);
                $keyIndex++;
            }
            $orderBatchs = $tour->batchs()->where('status', BaseConstService::BATCH_DELIVERING)->get();
        }
        if (empty($orderBatchs)) {
            return [];
        }
        $batchs = [];
        foreach ($orderBatchs as $key => $batch) {
            $batchs[] = [
                'batch_no' => $batch->batch_no,
                'place_lat' => $batch->place_lat,
                'place_lon' => $batch->place_lon
            ];
        }
        return $batchs;
    }

    /**
     * 更新站点
     * @param $batchNos
     * @param $tour
     * @throws BusinessLogicException
     */
    private function updateBatch($batchNos, $tour)
    {
        if (empty($batchNos)) {
            throw new BusinessLogicException('线路优化失败');
        }
        $nextBatch = null;
        $mix = count($batchNos);
        foreach ($batchNos as $k => $batchNo) {
            Batch::where('batch_no', $batchNo)->update(['sort_id' => $mix + $k]);
            if (!$nextBatch) {
                $nextBatch = Batch::where('batch_no', $batchNo)->first();
            }
        }
        if (empty($nextBatch)) {
            throw new BusinessLogicException('优化失败');
        }
        $this->updateTour($tour);
    }

    /**
     * 更新时距（主功能）
     * @param Tour $tour
     * @param array $driverLocation
     * @throws BusinessLogicException
     */
    public function updateTour(Tour $tour, $driverLocation = [])
    {
        $orderBatchs = Batch::where('tour_no', $tour->tour_no)->whereIn('status', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING])->orderBy('sort_id', 'asc')->get();
        if (!collect($orderBatchs)->isEmpty()) {
            /** @var  $orderBatchs \Illuminate\Support\Collection */
            $orderBatchs = $orderBatchs->keyBy('batch_no')->map(function ($batch) {
                return collect(['place_lat' => $batch->place_lat, 'place_lon' => $batch->place_lon]);
            })->toArray();
            if (empty($driverLocation)) {
                $driverLocation = ['latitude' => $tour->warehouse_lat, 'longitude' => $tour->warehouse_lon];
            }
            try {
                $res = $this->getDistance(array_merge([$driverLocation], array_values((array)$orderBatchs)));
                $distance = $time = 0;
                $nowTime = time();
                $key = 0;
                foreach ($orderBatchs as $batchNo => $batch) {
                    $distance += $res[$key][$key + 1]['distance']['value'];
                    $time += $res[$key][$key + 1]['duration']['value'];
                    Batch::query()->where('batch_no', $batchNo)->update([
                        'expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time),
                        'expect_distance' => $distance,
                        'expect_time' => $time
                    ]);
                    $key++;
                }
                $this->updateLastBatchToWarehouse($orderBatchs, $tour, $nowTime, $time, $distance);
            } catch (BusinessLogicException $exception) {
                throw new BusinessLogicException('线路更新失败');
            }
        }
    }

    /**
     * 估算距离（主功能）
     * @param $order
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function getDistanceByOrder($order)
    {
        try {
            if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
                $from = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
            } else {
                $from = implode(',', [$order['warehouse_lat'], $order['warehouse_lon']]);
            }
            $to = implode(',', [$order['place_lat'], $order['place_lon']]);
            $distance = $this->getDistanceByGoogle($from, $to);
        } catch (BusinessLogicException $e) {
            Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'BusinessLogicException', ['message' => $e->getMessage()]);
            throw new BusinessLogicException('可能由于网络原因，无法估算距离');
        }
        return $distance;
    }

    /**
     * 更新归程
     * @param $orderBatchs
     * @param $tour
     * @param $nowTime
     * @param $time
     * @param $distance
     * @throws BusinessLogicException
     */
    private function updateLastBatchToWarehouse($orderBatchs, $tour, $nowTime, $time, $distance)
    {
        $backWarehouseElement = $this->getDistance([last($orderBatchs), $tour->driver_location]);
        $backElement = $backWarehouseElement[0][1];
        if ($backElement['status'] !== "ZERO_RESULTS") {
            $tourData = [
                'warehouse_expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time + $backElement['duration']['value']),
                'warehouse_expect_distance' => $distance + $backElement['distance']['value'],
                'warehouse_expect_time' => $time + $backElement['duration']['value']
            ];
            // 只有未更新过的线路需要更新期望时间和距离
            if (
                ((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])
            ) {
                $tourData['expect_distance'] = $distance + $backElement['distance']['value'];
                $tourData['expect_time'] = $time + $backElement['duration']['value'];
            }
            Tour::query()->where('tour_no', $tour->tour_no)->update($tourData);
        }
    }

    /**
     * 批量获取时距
     * @param array $points
     * @return array
     * @throws BusinessLogicException
     */
    private function getDistance(array $points)
    {
        $elements = [];
        $index = 0;
        foreach ($points as &$point) {
            $point = is_array($point) ? implode(',', $point) : $point;
        }
        $groupPoints = (count($points) <= self::MAX_POINTS) ? [$points] : array_chunk($points, self::MAX_POINTS);
        $groupCount = count($groupPoints);
        for ($count = $groupCount, $i = 0; $i < $count; $i++) {
            $res = $this->getDistanceByGoogle($groupPoints[$i], $groupPoints[$i]);
            for ($gCount = count($groupPoints[$i]), $element = 0; $element < $gCount - 1; $element++) {
                $elements[$index][$index + 1] = $res['rows'][$element]['elements'][$element + 1];
                $index++;
            }
            $index++;
        }
        //超出25个点,则还要计算每个分组的点的衔接距离
        if ($groupCount > 1) {
            $startPoints = $endPoints = [];
            for ($k = 1; $k <= $groupCount; $k++) {
                $startPoints[] = implode(',', $points[self::MAX_POINTS * $k - 1]);
                $endPoints[] = implode(',', $points[self::MAX_POINTS * $k]);
            }
            $res = $this->getDistanceByGoogle($startPoints, $endPoints);
            for ($k = 1; $k <= $groupCount; $k++) {
                $elements[self::MAX_POINTS * $k - 1][self::MAX_POINTS * $k] = $res['result']['rows'][$k - 1]['elements'][$k];
            }
        }
        return $elements;
    }

    /**
     * 请求谷歌，获取时距
     * @param $from
     * @param $to
     * @return mixed|null
     * @throws BusinessLogicException
     */
    private function getDistanceByGoogle($from, $to)
    {
        $urlSuffix = 'distancematrix/json?';
        $from = is_array($from) ? implode('|', array_filter($from)) : $from;
        $to = is_array($to) ? implode('|', array_filter($to)) : $to;
        $data = "origins={$from}&destinations={$to}";
        return new GoogleCurl('get', $urlSuffix, $data);
    }

    /**
     * 更新司机位置
     * @param Tour $tour
     * @param $driverLocation
     * @throws BusinessLogicException
     */
    public function updateDriverLocation(Tour $tour, $driverLocation)
    {
        $this->updateTour($tour, $driverLocation);
    }


}
