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
use App\Services\CurlClient;
use Illuminate\Support\Facades\Log;

class GoogleApiService2
{
    const MAX_POINTS = 25;
    protected $url;
    protected $key;
    protected $secret;
    /**
     * @var CurlClient
     */
    protected $client;

    /**
     * GoogleApiService2 constructor.
     * @throws BusinessLogicException
     */
    public function __construct()
    {
        $this->client = new CurlClient;
        $this->url = config('tms.map_url');
        $this->key = config('tms.map_key');

//        $mapConfig = MapConfig::query()->where('company_id', $company->company_id)->first();
//        if (!empty($mapConfig) && !empty($mapConfig['google_key'])) {
//            $this->key = $mapConfig->toArray()['google_key'];
//        } else {
//            Log::info('备用Key');
        $this->key = config('tms.map_key');
//        }
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
        $driverLoc = [
            'batch_no' => 'driver_location',
            'place_lat' => $tour->driver_location['latitude'],
            'place_lon' => $tour->driver_location['longitude'],
        ];
        foreach ($orderBatchs as $key => $batch) {
            $batchs[] = [
                'batch_no' => $batch->batch_no,
                'place_lat' => $batch->place_lat,
                'place_lon' => $batch->place_lon
            ];
        }
        $batchNos = (new XLDirectionService())->GetRoute(array_merge([$driverLoc], $batchs));
        if (empty($batchNos)) {
            throw new BusinessLogicException('线路优化失败');
        }
        $nextBatch = null;
        foreach ($batchNos as $k => $batchNo) {
            Batch::where('batch_no', $batchNo)->update(['sort_id' => $key + $k]);
            if (!$nextBatch) {
                $nextBatch = Batch::where('batch_no', $batchNo)->first();
            }
        }
        if (empty($nextBatch)) {
            throw new BusinessLogicException('优化失败');
        }
        $this->updateTour($tour, $nextBatch->batch_no);
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
        if (!collect($orderBatchs)->isEmpty()) {
            $orderBatchs = $orderBatchs->keyBy('batch_no')->map(function ($batch) {
                return collect(['place_lat' => $batch->place_lat, 'place_lon' => $batch->place_lon]);
            })->toArray();
            if (empty($driverLocation)) {
                $preBatch = Batch::where('tour_no', $tour->tour_no)->whereIn('status', [BaseConstService::BATCH_CHECKOUT, BaseConstService::BATCH_CANCEL])->orderBy('sort_id', 'desc')->first();
                if(empty($preBatch)){
                    $wayPointList[] = $tour->warehouse_lat . ',' . $tour->warehouse_lon;
                }else{
                    $wayPointList[] = $preBatch['place_lat'] . ',' . $preBatch['place_lon'];
                }
            } else {
                $wayPointList[] = $driverLocation['latitude'] . ',' . $driverLocation['longitude'];
            }
            foreach ($orderBatchs as $k => $v) {
                $wayPointList[] = $v['place_lat'] . ',' . $v['place_lon'];
            }
            $wayPointList[] = $tour->warehouse_lat . ',' . $tour->warehouse_lon;
            $nowTime = time();
            $key = 0;
            $res = $this->getDistance2($wayPointList);
            $totalDistance = $totalTime = 0;
            foreach ($res as $k => $v) {
                $totalDistance += $v['distance'];
                $totalTime += $v['time'];
            }
            $inWarehouse = array_pop($res);
            //更新站点
            $arriveTime = 0;
            foreach ($orderBatchs as $batchNo => $batch) {
                $distance = $res[$key]['distance'];
                $time = $res[$key]['time'];
                $arriveTime += $time;
                Batch::query()->where('batch_no', $batchNo)->update([
                    'expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $arriveTime),
                    'expect_distance' => $distance,
                    'expect_time' => $time
                ]);
                $key++;
            }
            //更新回仓距时距和总时距
            $tourData = [
                'warehouse_expect_arrive_time' => date('Y-m-d H:i:s', $nowTime + $totalTime),
                'warehouse_expect_distance' => $inWarehouse['distance'],
                'warehouse_expect_time' => $inWarehouse['time']
            ];

            if (
                ((intval($tour->status) == BaseConstService::TOUR_STATUS_4) && ($tour->expect_time == 0))
                || in_array(intval($tour->status), [BaseConstService::TOUR_STATUS_1, BaseConstService::TOUR_STATUS_2, BaseConstService::TOUR_STATUS_3])
            ) {
                $tourData['expect_distance'] = $totalDistance;
                $tourData['expect_time'] = $totalTime;
            }
            Tour::query()->where('tour_no', $tour->tour_no)->update($tourData);


//            } catch (BusinessLogicException $exception) {
//                throw new BusinessLogicException('线路更新失败');
//            }
        }
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
            $res = $this->getDistance($this->url, $groupPoints[$i], $groupPoints[$i]);
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
            $res = $this->getDistance($this->url, $startPoints, $endPoints);
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
        $from = is_array($from) ? implode('|', array_filter($from)) : $from;
        $to = is_array($to) ? implode('|', array_filter($to)) : $to;
        $query = "distancematrix/json?origins={$from}&destinations={$to}&key={$this->key}";
        $url = $url . $query;
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'get', ['url' => $url]);
        if (config('tms.true_app_env') == 'develop') {
            $options = [
                'proxy' => [
                    'http' => config('tms.http_proxy'),
                    'https' => config('tms.https_proxy')
                ]];
        } else {
            $options = [];
        }
        $res = $this->client->get($url, $options);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
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
     * 获取最优顺序
     * @param $wayPointList
     * @return mixed|null
     * @throws BusinessLogicException
     */
    protected function getDistance2($wayPointList)
    {
        foreach ($wayPointList as &$point) {
            $point = is_array($point) ? implode(',', $point) : $point;
        }
        $groupPoints = array_chunk($wayPointList, self::MAX_POINTS);
        $res = [];
        foreach ($groupPoints as $k => $v) {
            if ($k > 0) {
                $array = array_merge([last($groupPoints[$k - 1])], $v);
            } else {
                $array = $v;
            }
            $res = array_merge($res, $this->getDistanceByGroup($array));
        }
        return $res;
    }

    /**
     * 分组求距离
     * @param $wayPointList
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getDistanceByGroup($wayPointList)
    {
        //["5.55,6,66","5.56,6,56","5,55,6,66"]
        $origin = $wayPointList[0];
        $destination = $wayPointList[count($wayPointList) - 1];
        unset($wayPointList[0]);
        array_pop($wayPointList);
        $wayPointList = array_values($wayPointList);
        $query = "directions/json?origin={$origin}&destination={$destination}&key={$this->key}";
        if (!empty($wayPointList)) {
            $wayPoints = implode('|', $wayPointList);
            $query .= "&waypoints={$wayPoints}";
        }
        $url = $this->url . $query;
        if (config('tms.true_app_env') == 'develop') {
            $options = [
                'proxy' => [
                    'http' => config('tms.http_proxy'),
                    'https' => config('tms.https_proxy')
                ]];
        } else {
            $options = [];
        }
        $res = $this->client->get($url, $options);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            throw new BusinessLogicException('google-api请求报错');
        }
        $result = [];
        foreach ($res['routes'][0]['legs'] as $k => $v) {
            $result[] = [
                'distance' => $v['distance']['value'],
                'time' => $v['duration']['value']
            ];
        }
        return $result;
    }
}
