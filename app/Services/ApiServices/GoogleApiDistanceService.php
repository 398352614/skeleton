<?php


namespace App\Services\ApiServices;


use App\Exceptions\BusinessLogicException;
use App\Models\MapConfig;
use App\Services\BaseConstService;
use App\Services\CurlClient;
use App\Traits\CompanyTrait;
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
        $company = auth('admin')->user();
        if (empty($company)) {
            $company = auth('merchant')->user();
        }
        if (empty($company)) {
            $company = auth('driver')->user();
        }
        $this->client = new \GuzzleHttp\Client();
        $this->url = config('tms.map_url');
        //$this->key = CompanyTrait::getCompany(auth()->user()->company_id)['map_config']['google_key'];
        $mapConfig = MapConfig::query()->where('company_id', $company->company_id)->first();
        if (!empty($mapConfig)) {
            $this->key = $mapConfig->toArray()['google_key'];
        }else{
            $this->key ='';
        }    }

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
        $res = $this->client->request('GET', $url, $options);
        $body = $res->getBody();
        $stringBody = (string)$body;
        $res = json_decode($stringBody, TRUE);
        Log::info('返回值', $res);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
            Log::info('google-api请求url', ['url' => $url]);
            Log::info('google-api请求报错:' . json_encode($res, JSON_UNESCAPED_UNICODE));
            throw new BusinessLogicException('google-api请求报错');
        }
        Log::info($res['rows'][0]['elements'][0]['distance']['value']);
        $distance = $res['rows'][0]['elements'][0]['distance']['value'];
        return $distance;
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
            $distance = $this->getDistance($this->url, $from, $to);
        } catch (\Exception $e) {
            Log::info('报错' . $e->getMessage());
            throw new BusinessLogicException('可能由于网络原因，无法估算距离');
        }
        return $distance;
    }
}
