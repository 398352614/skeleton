<?php


namespace App\Services\ApiServices;


use App\Exceptions\BusinessLogicException;
use App\Models\Batch;
use App\Models\Tour;
use App\Services\Admin\ApiTimesService;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Traits\FactoryInstanceTrait;
use Illuminate\Support\Facades\Log;

class TenCentApiDistanceService
{

    protected $url;

    protected $distance_url;

    protected $key;

    protected $secret;

    const MAX_POINTS = 25;

    /**
     * @var CurlClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new CurlClient;
        $this->url = config('tms.tencent_api_url');
        $this->distance_url = config('tms.tencent_distance_matrix_api_url');
        $this->key = config('tms.tencent_api_key');
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
        $query = "mode=driving&from={$from}&to={$to}&key={$this->key}";
        $url = $url . '?' . $query;
        $url = str_replace('optimal_order', 'matrix/', $url);
        $res = $this->client->get($url);
        dd($res);
        $body = $res->getBody();
        $stringBody = (string)$body;
        $res = json_decode($stringBody, TRUE);
        if (!isset($res['status']) || ($res['status'] != 0)) {
            Log::info('tencent-api请求url', ['url' => $url]);
            Log::info('tencent-api请求报错:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('teCent-api请求报错');
        }
        return $res;
    }

    /**
     * 通过订单获取预计距离
     * @param $order
     * @return mixed|null
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDistanceByOrder($order)
    {
//        try {
            if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
                $from = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
            } else {
                $from = implode(',', [$order['warehouse_lat'], $order['warehouse_lon']]);
            }
            $to = implode(',', [$order['place_lat'], $order['place_lon']]);
            $res = $this->getDistance($this->url, $from, $to);
            dd($res);
            $distance = $res['result']['elements'][0]['distance']['value'];
//        } catch (\Exception $e) {
//            throw new BusinessLogicException('可能由于网络原因，无法估算距离');
//        }
        return $distance;
    }
}
