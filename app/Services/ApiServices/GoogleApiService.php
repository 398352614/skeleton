<?php

namespace App\Services\ApiServices;

use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\MapConfig;
use App\Models\Tour;
use App\Services\Admin\ApiTimesService;
use App\Services\BaseConstService;
use App\Services\BaseServices\XLDirectionService;
use App\Services\CurlClient;
use App\Traits\CompanyTrait;
use App\Traits\FactoryInstanceTrait;
use App\Traits\UpdateTourTimeAndDistanceTrait;
use Illuminate\Support\Facades\Log;

class GoogleApiService
{
    use UpdateTourTimeAndDistanceTrait;

    protected $url;

    protected $key;

    protected $secret;

    public $times = 5;

    public $delay = 1;

    //站点停留时间(分钟)
    const BATCH_STOP_TIME = 2;

    /**
     * @var CurlClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new CurlClient;
        $this->url = config('tms.api_url');
        //$this->key = CompanyTrait::getCompany(auth()->user()->company_id)['map_config']['google_key'];
        $this->key = MapConfig::query()->where('company_id',auth()->user()->company_id)->first()->toArray()['google_key'] ?? '';
        $this->secret = config('tms.api_secret');
    }

    /**
     * 签名
     */
    public function makeSign($timestamp)
    {
        $mac = hash_hmac('sha256', $this->key . $timestamp, $this->secret);
        // app('log')->info('计算出来的 sign 为:', $mac);
        $query = "?api_key=$this->key&timestamp=$timestamp&sign=$mac";
        return $query;
    }

    /**
     * 初始化线路
     */
    public function InitTour(Tour $tour)
    {
        $api = '/api/init-line';
        $driver_location = $tour->driver_location;
        $driver_location['code'] = create_unique($tour->company_id);
        $batchs = [$driver_location]; // 将司机位置放在序列中的第一位

        $tourBatchs = Batch::where('tour_no', $tour->tour_no)->orderBy('index', 'asc')->get(); //有序的

        foreach ($tourBatchs as $key => $batch) {
            $batchs[] = [
                "latitude" => $batch->location->latitude,
                "longitude" => $batch->location->longitude,
                "code" => $batch->location_code
            ];
        }

        $params = [
            'code' => $tour->code,
            'location' => $batchs,
        ];

        app('log')->info('初始化线路传送给 api 端的参数为:', $params);

        $res = $this->client->postJson($this->url . $api . $this->makeSign(time()), $params);
        FactoryInstanceTrait::getInstance(ApiTimesService::class)->timesCount('api_distance_times', $tour->company_id);
        return $res;
    }

    /**
     * 获取线路信息
     */
    public function LineInfo($lineCode)
    {
        $api = '/api/line-info';

        $path = $this->url . $api . $this->makeSign(time()) . "&line_code=$lineCode";

        $res = $this->client->get($path);

        return $res;
    }

    /**
     * 更新司机位置
     */
    public function PushDriverLocation($data)
    {
        //example
        // [
        //     "latitude"=>52.25347699,
        //     "longitude"=>4.62897256,
        //     "target_code"=>"1023",
        //     "line_code"=>"line10086"
        // ];
        $api = '/api/update-driver';

        $res = $this->client->postJson($this->url . $api . $this->makeSign(time()), $data);

        return $res;
    }

    /**
     * 更新线路
     * @param Tour $tour
     * @param $nextCode
     * @param $driverLocation
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function updateTour(Tour $tour, $nextCode, $driverLocation = [])
    {
        $api = '/api/update-line';
        $driver_location = $tour->driver_location;
        $driver_location['code'] = $tour->tour_no . 'driver_location';
        $batchs = [$driver_location]; // 将司机位置放在序列中的第一位

        $orderBatchs = Batch::where('tour_no', $tour->tour_no)->whereIn('status', [BaseConstService::BATCH_WAIT_ASSIGN, BaseConstService::BATCH_ASSIGNED, BaseConstService::BATCH_WAIT_OUT, BaseConstService::BATCH_DELIVERING])->orderBy('sort_id', 'asc')->get();
        foreach ($orderBatchs as $key => $batch) {
            $batchs[] = [
                "latitude" => $batch->place_lat,
                "longitude" => $batch->place_lon,
                "code" => $batch->batch_no,
                "gather_sn" => ['a'],
            ];
        }
        $batchs[] = [
            "latitude" => $tour->warehouse_lat,
            "longitude" => $tour->warehouse_lon,
            "code" => $tour->tour_no,
            "gather_sn" => ['a'],
        ];
        $params = [
            'code' => $tour->tour_no,
            'latitude' => (string)$driver_location['latitude'],
            'longitude' => (string)$driver_location['longitude'],
            'target_code' => $nextCode,
            'location' => $batchs,
        ];
        app('log')->info('更新线路传送给 api 端的参数为:', $params);
        $this->client->postJson($this->url . $api . $this->makeSign(time()), $params);
        FactoryInstanceTrait::getInstance(ApiTimesService::class)->timesCount('api_distance_times', $tour->company_id);
        //更新距离和时间
        $this->multiUpdateTourTimeAndDistance($tour);
        return true;
    }

    /**
     * 更新距离和时间
     * @param Tour $tour
     * @throws BusinessLogicException
     */
    private function multiUpdateTourTimeAndDistance(Tour $tour)
    {
        sleep(1);
        $bool = false;
        for ($i = 1; $i <= $this->times; $i++) {
            $bool = $this->updateTourTimeAndDistance($tour);
            if ($bool) break;
            sleep($this->delay);
        }
        if (!$bool) {
            throw new BusinessLogicException('更新线路失败，请稍后重试');
        }
        Log::info('取件线路预计耗时和里程更新成功');
    }


    /**
     * 自动优化
     * @param Tour $tour
     * @return bool
     * @throws BusinessLogicException
     */
    public function autoUpdateTour(Tour $tour)
    {
        //获取并更新站点
        $key = 1;
        if ($tour->status != BaseConstService::TOUR_STATUS_4) { // 未取派的情况下,所有 batch 都是未取派的
            $batchs = $tour->batchs;
        } else {
            $preBatchs = $tour->batchs()->where('status', '<>', BaseConstService::BATCH_DELIVERING)->get()->sortByDesc('sort_id'); // 已完成的包裹排序值放前面并不影响 -- 此处不包括未开始的
            foreach ($preBatchs as $preBatch) {
                $preBatch->update(['sort_id' => $key]);
                $key++;
            }
            $batchs = $tour->batchs()->where('status', BaseConstService::BATCH_DELIVERING)->get();
        }
        if ($batchs->isEmpty()) {
            return true;
        }
        //自动优化
        $driverLoc = [
            'batch_no' => 'driver_location',
            'place_lat' => $tour->driver_location['latitude'],
            'place_lon' => $tour->driver_location['longitude'],
        ];
        app('log')->debug('查看当前 batch 总数为:' . count($batchs) . '当前的 batch 为:', $batchs->toArray());
        app('log')->debug('整合后的数据为:', array_merge([$driverLoc], $batchs->toArray()));
        $batchNos = (new XLDirectionService())->GetRoute(array_merge([$driverLoc], $batchs->toArray()));
        if (empty($batchNos)) {
            throw new BusinessLogicException('优化线路失败');
        }
        app('log')->info('当前返回的值为:' . json_encode($batchNos));
        $nextBatch = null;
        foreach ($batchNos as $k => $batchNo) {
            Batch::where('batch_no', $batchNo)->update(['sort_id' => $key + $k]);
            if (!$nextBatch) {
                $nextBatch = Batch::where('batch_no', $batchNo)->first();
            }
        }
        if (empty($nextBatch)) {
            throw new BusinessLogicException('优化失败');
        };
        $this->updateTour($tour, $nextBatch->batch_no);
        return true;
    }

    /**
     * 更新司机位置并更新站点预计信息
     * @param $tour
     * @param $driverLocation
     * @param $nextBatchNo
     * @param bool $queue
     * @throws BusinessLogicException
     */
    public function updateDriverLocation(Tour $tour, $driverLocation, $nextBatchNo, $queue = false)
    {
        app('log')->info('更新司机位置进入此处');
        //需要验证上一次操作是否完成,不可多次修改数据,防止数据混乱
        if (empty($tour)) return;
        app('log')->info('存在在途任务,更新');
        $data = [
            "latitude" => $driverLocation['latitude'],
            "longitude" => $driverLocation['longitude'],
            "target_code" => $nextBatchNo,
            "line_code" => $tour->tour_no,
        ];

        $res = $this->PushDriverLocation($data);
        if ($queue == true) {
            sleep(1);
        }
        app('log')->info('更新司机位置的结果为:', $res ?? []);
        if (!$this->updateTourTimeAndDistance($tour)) {
            throw new BusinessLogicException('更新线路失败');
        }
    }
}
