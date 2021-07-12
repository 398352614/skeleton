<?php


namespace App\Services\ApiServices;


use App\Exceptions\BusinessLogicException;
use App\Models\MapConfig;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class GoogleApiDistanceService
{

    protected $url;

    protected $key;

    public $times = 5;


    protected $client;

    /**
     * GoogleApiDistanceService constructor.
     * @throws BusinessLogicException
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->url = config('tms.map_url');
        $this->key = config('tms.map_key');

        $company = auth('admin')->user();
        if (empty($company)) {
            $company = auth('merchant')->user();
        }
        if (empty($company)) {
            $company = auth('driver')->user();
        }
        if (empty($company)) {
            $company = auth()->user();
        }
        if (empty($company)) {
            throw new BusinessLogicException('公司不存在');
        }
        $mapConfig = MapConfig::query()->where('company_id', $company->company_id)->first();
        if (!empty($mapConfig) && !empty($mapConfig['google_key'])) {
            $this->key = $mapConfig->toArray()['google_key'];
        } else {
            $this->key = config('tms.map_key');
        }
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
        Log::info($to);
        $from = is_array($from) ? implode(';', array_filter($from)) : $from;
        $to = is_array($to) ? implode(';', array_filter($to)) : $to;
        $query = "distancematrix/json?origins={$from}&destinations={$to}&key={$this->key}";
        $url = $url . $query;
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
        Log::info('request',[
            'res'=>$res,
            'url'=>$url,
        ]);
        $body = $res->getBody();
        $stringBody = (string)$body;
        $res = json_decode($stringBody, TRUE);
        if (!isset($res['status']) || ($res['status'] != 'OK')) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            throw new BusinessLogicException('google-api请求报错');
        }
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
            //导入填充,手动录入派件反向
            if(empty($order['place_lat']) || empty($order['place_lon'])){
                $from = implode(',', [$order['warehouse_lat'], $order['warehouse_lon']]);
                $to = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
            }elseif (empty($order['second_place_lat']) || empty($order['second_place_lon'])){
                $from = implode(',', [$order['place_lat'], $order['place_lon']]);
                $to = implode(',', [$order['warehouse_lat'], $order['warehouse_lon']]);
            }elseif ($order['type'] == BaseConstService::ORDER_TYPE_2){
                $from = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
                $to = implode(',', [$order['place_lat'], $order['place_lon']]);
            }else{
                $from = implode(',', [$order['place_lat'], $order['place_lon']]);
                $to = implode(',', [$order['second_place_lat'], $order['second_place_lon']]);
            }
            $distance = $this->getDistance($this->url, $from, $to);
        } catch (\Exception $e) {
            Log::channel('info')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            throw new BusinessLogicException('可能由于网络原因，无法估算距离');
        }
        return $distance;
    }
}
