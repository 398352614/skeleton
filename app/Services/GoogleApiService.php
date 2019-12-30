<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Tour;

class GoogleApiService
{
    protected $url;

    protected $key;

    protected $secret;

    /**
     * @var CurlClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new CurlClient;
        $this->url = config('tms.api_url');
        $this->key = config('tms.api_key');
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
        $driver_location = $tour->driver_location; //TODO: 此处司机位置如何获取
        $driver_location['code'] = create_unique($tour->company_id);
        $batchs = [$driver_location]; // 将司机位置放在序列中的第一位

        $tourBatchs = Batch::where('tour_no', $tour->tour_no)->orderBy('index', 'asc')->get(); //有序的

        foreach ($tourBatchs as $key => $batch) {
            $batchs[] = [
                "latitude"      =>  $batch->location->latitude,
                "longitude"     =>  $batch->location->longitude,
                "code"          =>  $batch->location_code
            ];
        }

        $params = [
            'code'          =>  $tour->code,
            'location'      =>  $batchs,
        ];

        app('log')->info('初始化线路传送给 api 端的参数为:', $params);

        $res = $this->client->post($this->url . $api . $this->makeSign(time()), $params);

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

        $res = $this->client->post($this->url . $api . $this->makeSign(time()), $data);

        return $res;
    }

    public function UpdateTour(Tour $tour, $nextCode)
    {
        $api = '/api/update-line';
        $driver_location = $tour->driver_location;
        $driver_location['code'] = $tour->tour_no . 'driver_location';
        $batchs = [$driver_location]; // 将司机位置放在序列中的第一位

        $orderBatchs = Batch::where('tour_no', $tour->tour_no)->orderBy('sort_id', 'asc')->get();

        foreach ($orderBatchs as $key => $batch) {
            $batchs[] = [
                "latitude"      =>  $batch->receiver_lat,
                "longitude"     =>  $batch->receiver_lon,
                "code"          =>  $batch->batch_no
            ];
        }

        $params = [
            'code'          =>  $tour->tour_no,
            'latitude'      =>  (string)$driver_location['latitude'],
            'longitude'      =>  (string)$driver_location['longitude'],
            'target_code'   =>  $nextCode,
            'location'      =>  $batchs,
        ];

        app('log')->info('更新线路传送给 api 端的参数为:', $params);

        $res = $this->client->post($this->url . $api . $this->makeSign(time()), $params);

        return $res;
    }
}