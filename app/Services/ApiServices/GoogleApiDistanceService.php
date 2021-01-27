<?php


namespace App\Services\ApiServices;


use App\Exceptions\BusinessLogicException;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class GoogleApiDistanceService
{

    protected $url;

    protected $key;

    public $times = 5;

    /**
     * @var CurlClient
     */
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();;
        $this->url = config('tms.map_url');
        $this->key = config('tms.map_key');
    }

    /**
     * 获取距离和时间
     * @param $url
     * @param $from
     * @param $to
     * @return mixed|null
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getDistance($url, $from, $to)
    {
        $from = is_array($from) ? implode(';', array_filter($from)) : $from;
        $to = implode(';', array_filter($to));
        $query = "distancematrix/json?origins={$from}&destinations={$to}&key={$this->key}";
        $url = $url . '?' . $query;
        if ((config('tms.true_app_env') === 'develop')) {
            $options = ['proxy' => ['http' => config('tms.http_proxy'), 'https' => config('tms.https_proxy')]];
        } else {
            $options = [];
        }
        $res = $this->client->request('GET', $url, $options);
        if (!isset($res['status']) || ($res['status'] != 0)) {
            Log::info('google-api请求url', ['url' => $url]);
            Log::info('google-api请求报错:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('google-api请求报错');
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
        try {
            if ($order['type'] == BaseConstService::ORDER_TYPE_3) {
                $from = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
            } else {
                $from = implode(',', [$order['warehouse_lat'], $order['warehouse_lon']]);
            }
            $to = implode(',', [$order['place_lat'], $order['place_lon']]);
            $res = $this->getDistance($this->url, $from, $to);
            $distance = $res['result']['elements'][0]['distance']['value'];
        } catch (\Exception $e) {
            throw new BusinessLogicException('可能由于网络原因，无法估算距离');
        }
        return $distance;
    }
}
