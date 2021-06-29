<?php

/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019-05-21
 * Time: 10:34
 */

namespace App\Services;

use App\Hash\MerchantApi;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CurlClient
{
    protected $http;

    public function __construct()
    {
        $this->http = new Client(['headers' => ['Language' => 'en'], 'verify' => false]);
    }

    public function setMethod($url, $params, $method)
    {
        if ($method == 'post') {
            $responseData = $this->post($url, $params);
        } elseif ($method == 'get') {
            $responseData = $this->get($url . '?' . http_build_query($params));
        } else {
            return false;
        }
        return $responseData;
    }

    /**
     * 自定义头部
     * @param $headers
     */
    public function setHeaders($headers)
    {
        $this->http = new Client(['headers' => $headers, 'verify' => false]);
    }

    /**
     * 自定义头部
     * @param $headers
     */
    public function setAuth($auth)
    {
        $credentials = base64_encode($auth[0] . ':' . $auth[1]);

        $this->http = new Client(['headers' => [
            'Authorization' => 'Basic ' . $credentials,
        ], 'verify' => false]);
    }

    public function post($url, $params, $next = 0, $auth = null)
    {
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'post', [
            'url' => $url,
            'data' => $params,
        ]);
        try {
            if ($auth) {
                $response = $this->http->post($url, ['auth' => $auth, 'form_params' => $params]);
            } else {
                $response = $this->http->post($url, ['form_params' => $params]);
            }
        } catch (\Exception $e) {
            if ($next >= 2) {
                Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '多次请求出错，不再请求');
                return null;
            }
            $next++;
            app("log")->error($e->getMessage());
            app("log")->error($e->getTraceAsString());
            return $this->post($url, $params, $next);
        }
        if ($response->getStatusCode() == 200) {
            $bodyData = $response->getBody();
            $responseData = json_decode((string)$bodyData, true);
            if (!$responseData) {
                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '返回不是json数组');
                return null;
            }
            return $responseData;
        } else {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '状态码非200');
            return null;
        }
    }

    public function postJson($url, $params, $next = 0, $auth = null)
    {
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'post', [
            'url' => $url,
            'data' => $params,
        ]);
        try {
            //php 7.4兼容
            //https://cloud.tencent.com/developer/article/1489213
            stream_context_set_default(
                [
                    'ssl' => [
                        'verify_host' => false,
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ]
                ]
            );
            if ($auth) {
                $response = $this->http->post($url, ['auth' => $auth, 'json' => $params]);
            } else {
                $response = $this->http->post($url, ['json' => $params]);
            }
        } catch (\Exception $e) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            if ($next >= 2) {
                Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '多次请求出错，不再请求');
                return null;
            }
            $next++;
            Log::channel('api')->notice(__CLASS__ . '.' . __FUNCTION__ . '.' . '请求失败，重新推送');
            return $this->postJson($url, $params, $next, $auth);
        }
        if ($response->getStatusCode() == 200) {
            $bodyData = $response->getBody();
            $responseData = json_decode((string)$bodyData, true);
            if (!$responseData) {
                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '返回不是json数组');
                return null;
            }
            return $responseData;
        } else {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '状态码非200');
            return null;
        }
    }

    /**
     * 货主接口请求
     * @param $merchant
     * @param $params
     * @param $type
     * @param int $next
     * @param null $auth
     * @return mixed|null
     */
    public function merchantPost($merchant, $params, $next = 0, $auth = null)
    {
        $data = array_merge($params,
            [
                'key' => $merchant['key'],
                'time' => time(),
                'sign' => (new MerchantApi())->make($merchant['secret'], $params['data'] ?? []),
            ]);
        return $this->postJson($merchant['url'], $data, $next, $auth);
    }

    public function get($url, $options = [])
    {
        try {
            //php 7.4兼容
            //https://cloud.tencent.com/developer/article/1489213
            stream_context_set_default(
                [
                    'ssl' => [
                        'verify_host' => false,
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ]
                ]
            );
            $res = $this->http->request('GET', $url, $options);
            // app('log')->info('测试 url'.$url);
        } catch (\Exception $e) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return null;
        }

        if ($res->getStatusCode() == 200) {
            $bodyData = $res->getBody();
            $responseData = json_decode((string)$bodyData, true);
            return $responseData;
        } else {
            return null;
        }
    }
}
