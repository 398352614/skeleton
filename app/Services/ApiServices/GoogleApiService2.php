<?php
/**
 * 百度地图 API
 * User: long
 * Date: 2020/9/11
 * Time: 16:52
 */

namespace App\Services\ApiServices;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\MapConfig;
use App\Models\Tour;
use App\Services\Admin\ApiTimesService;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Support\Facades\Log;

class GoogleApiService2
{
    const MAX_POINTS = 25;
    protected $url;
    protected $distance_url;
    protected $key;
    protected $secret;
    /**
     * @var CurlClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new CurlClient;
        $this->url = config('tms.map_url');
        $this->key = config('tms.map_key');
    }

    /**
     * 自动更新tour
     * @param Tour $tour
     * @param $nextCode
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function autoUpdateTour(Tour $tour)
    {
        /***********************@**********1.获取网点到所有站点的距离和时间********************************************/
        //获取并更新站点
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
            return true;
        }
        $batchs = [];
        $origin = implode(',', $tour->driver_location);
        foreach ($orderBatchs as $key => $batch) {
            $batchs[] = [
                'batch_no' => $batch->batch_no,
                'location' => implode(',', [$batch->place_lat, $batch->place_lon])
            ];
        }
        try {
            $res = $this->getSort($this->url, $origin, array_column($batchs, 'location'));
            $distance = $time = 0;
            $nowTime = time();
            foreach ($res['routes']['legs'] as $key => $element) {
                $distance += $element['distance']['value'];
                $time += $element['duration']['value'] * 60;
                $data = [
                    'expect_distance' => $distance,
                    'expect_time' => $time,
                    'expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time),
                    'sort_id' => $keyIndex
                ];
                Batch::query()->where('batch_no', $batchs[$res['routes']['waypoint_order'][$key]]['batch_no'])->update($data);
                $keyIndex++;
            }
            /*********************************2.获取最后一个站点到网点的距离和时间*****************************************/
            $lastPointIndex = last($res['routes']['legs']);
            $backWarehouseElement = $this->distanceMatrix([$batchs[$lastPointIndex - 1]['location'], $tour->driver_location]);
            $backElement = $backWarehouseElement[0][1];
            $tourData = [
                'warehouse_expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time + $backElement['duration']['value'] * 60),
                'warehouse_expect_distance' => $distance + $backElement['distance']['value'],
                'warehouse_expect_time' => $time + $backElement['duration']['value']
            ];
            // 只有未更新过的线路需要更新期望时间和距离
            if (
                ((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])
            ) {
                $tourData['expect_distance'] = $distance + $backElement['distance'];
                $tourData['expect_time'] = $time + $backElement['duration'];
            }
            Log::info('auto-tour-data', $tourData);
            Tour::query()->where('tour_no', $tour->tour_no)->update($tourData);
        } catch (BusinessLogicException $exception) {
            dd($exception);
            throw new BusinessLogicException('线路自动更新失败');
        }
        FactoryInstanceTrait::getInstance(ApiTimesService::class)->timesCount('api_distance_times', $tour->company_id);
        return $res;
    }

    /**
     * 获取最优顺序
     * @param $url
     * @param $from
     * @param $to
     * @return mixed|null
     * @throws BusinessLogicException
     */
    protected function getSort($url, $origin, $wayPointList)
    {
        $wayPoints='';
        foreach ($wayPointList as $v){
            $wayPoints='|'.$v;
        }
        $query = "json?origin={$origin}&waypoints=optimize:true{$wayPoints}&destination={$origin}&key={$this->key}";
        $url = $url . '?' . $query;
        $res = $this->client->get($url);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
            Log::info('google-api请求url', ['url' => $url]);
            Log::info('google-api请求报错:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('google-api请求报错');
        }
        dd($res);
        return $res;
    }

    /**
     * 计算距离
     * @param array $points
     * @return array
     * @throws BusinessLogicException
     */
    public function distanceMatrix(array $points)
    {
        $elements = [];
        $index = 0;
        foreach ($points as &$point) {
            $point = is_array($point) ? implode(',', $point) : $point;
        }
        $groupPoints = (count($points) <= self::MAX_POINTS) ? [$points] : array_chunk($points, self::MAX_POINTS);
        $groupCount = count($groupPoints);
        for ($count = $groupCount, $i = 0; $i < $count; $i++) {
            $res = $this->getDistance($this->distance_url, $groupPoints[$i], $groupPoints[$i]);
            for ($gCount = count($groupPoints[$i]), $element = 0; $element < $gCount - 1; $element++) {
                $elements[$index][$index + 1] = $res['result']['rows'][$element]['elements'][$element + 1];
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
            $res = $this->getDistance($this->distance_url, $startPoints, $endPoints);
            for ($k = 1; $k <= $groupCount; $k++) {
                $elements[self::MAX_POINTS * $k - 1][self::MAX_POINTS * $k] = $res['result']['rows'][$k - 1]['elements'][$k];
            }
        }
        return $elements;
    }

    /**
     * 获取距离和时间
     * @param $url
     * @param $from
     * @param $to
     * @return mixed|null
     * @throws BusinessLogicException
     */
    protected function getDistance($url, $from, $to)
    {
        $from = is_array($from) ? implode(';', array_filter($from)) : $from;
        $to = is_array($to) ? implode(';', array_filter($to)) : $to;
        $query = "distancematrix/json?origins={$from}&destinations={$to}&key={$this->key}";
        $url = $url . $query;
        Log::info('路由' . $url);
        if (config('tms.true_app_env') == 'develop') {
            $options = [
                'proxy' => [
                    'http' => config('tms.http_proxy'),
                    'https' => config('tms.https_proxy')
                ]];
        } else {
            $options = [];
        }
        $res = $this->client->get($url);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
            Log::info('google-api请求url', ['url' => $url]);
            Log::info('google-api请求报错:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('google-api请求报错');
        }
        return $res;

    }

    /**
     * 更新司机位置
     * @param Tour $tour
     * @param $driverLocation
     * @param $nextBatchNo
     * @param bool $queue
     * @throws BusinessLogicException
     */
    public function updateDriverLocation(Tour $tour, $driverLocation, $nextBatchNo, $queue = false)
    {
        $this->updateTour($tour, $nextBatchNo, $driverLocation);
    }

    /**
     * 更新线路
     * @param Tour $tour
     * @param $nextCode
     * @param array $driverLocation
     * @throws BusinessLogicException
     */
    public function updateTour(Tour $tour, $nextCode, $driverLocation = [])
    {
        $orderBatchs = Batch::where('tour_no', $tour->tour_no)->whereIn('status', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING])->orderBy('sort_id', 'asc')->get();
        $orderBatchs = $orderBatchs->keyBy('batch_no')->map(function ($batch) {
            return collect(['place_lat' => $batch->place_lat, 'place_lon' => $batch->place_lon]);
        })->toArray();
        if (empty($driverLocation)) {
            $driverLocation = ['latitude' => $tour->warehouse_lat, 'longitude' => $tour->warehouse_lon];
        }
        try {
            $res = $this->distanceMatrix(array_merge([$driverLocation], array_values($orderBatchs)));
            $distance = $time = 0;
            $nowTime = time();
            $key = 0;
            foreach ($orderBatchs as $batchNo => $batch) {
                $distance += $res[$key][$key + 1]['distance'];
                $time += $res[$key][$key + 1]['duration'];
                Batch::query()->where('batch_no', $batchNo)->update([
                    'expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time),
                    'expect_distance' => $distance,
                    'expect_time' => $time
                ]);
                $key++;
            }
            /*********************************2.获取最后一个站点到网点的距离和时间*****************************************/
            $backWarehouseElement = $this->distanceMatrix([last($orderBatchs), $tour->driver_location]);
            $backElement = $backWarehouseElement[0][1];
            $tourData = [
                'warehouse_expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $time + $backElement['duration']),
                'warehouse_expect_distance' => $distance + $backElement['distance'],
                'warehouse_expect_time' => $time + $backElement['duration']
            ];
            // 只有未更新过的线路需要更新期望时间和距离
            if (
                ((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])
            ) {
                $tourData['expect_distance'] = $distance + $backElement['distance'];
                $tourData['expect_time'] = $time + $backElement['duration'];
            }
            Log::info('tour-data', $tourData);
            Tour::query()->where('tour_no', $tour->tour_no)->update($tourData);
        } catch (BusinessLogicException $exception) {
            throw new BusinessLogicException('线路更新失败');
        }
    }
}
