<?php

/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019-05-21
 * Time: 10:34
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\MessageInterface;

class BaseCurl
{
    protected $http;

    protected $url;

    protected $key;

    protected $method;

    protected $options = [];

    protected $data;

    protected $headers = [
        'verify' => false
    ];


    public $times = 5;

    /**
     * BaseCurl constructor.
     * @param $method
     * @param $url
     * @param $data
     * @param array $headers
     * @param array $options
     * @param array $auth
     * @param int $needProxy
     * @param int $times
     * @param int $contentType
     * @throws BusinessLogicException
     */
    public function __construct($method, $url, $data, $headers = [], $options = [], $auth = [], $needProxy = BaseConstService::NO, $times = 1, $contentType = BaseConstService::CONTENT_TYPE_JSON)
    {
        $this->validate($method, $url, $data);
        $this->options = $options;
        $this->url = $url;
        $this->data = $data;
        $this->method = $method;
        $this->headers = $headers;
        $this->times = $times;
        $this->setAuth($auth)->setHeaders($this->headers);
        if ($needProxy == BaseConstService::YES) {
            $this->setProxy();
        }
        $this->setData($contentType);
        $this->http = new Client($this->options);
        if($method == BaseConstService::METHOD_GET){
            return $this->get();
        }elseif ($method == BaseConstService::METHOD_POST){
            return $this->post();
        }
    }

    /**
     * 设置数据类型
     * @param $contentType
     * @throws BusinessLogicException
     */
    public function setData($contentType)
    {
        if ($this->method == BaseConstService::METHOD_POST) {
            if ($contentType == BaseConstService::CONTENT_TYPE_FORM_DATA) {
                $this->options[] = ['form_params' => $this->data];
            } elseif ($contentType == BaseConstService::CONTENT_TYPE_JSON) {
                $this->options[] = ['json' => $this->data];
            } else {
                throw new BusinessLogicException('数据类型未定义');
            }
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $data
     * @throws BusinessLogicException
     */
    public function validate($method, $url, $data)
    {
        if (!in_array($method, [BaseConstService::METHOD_GET, BaseConstService::METHOD_POST])) {
            throw new BusinessLogicException('方法未定义');
        }
        if (empty($url)) {
            throw new BusinessLogicException('路由为空');
        }
        if (empty($data)) {
            throw new BusinessLogicException('请求数据为空');
        }
        if ($method == BaseConstService::METHOD_GET || !is_string($data)) {
            throw new BusinessLogicException('请组装好get请求参数');
        }
    }

    public function setProxy()
    {
        $this->options[] = [
            'proxy' => [
                'http' => config('tms.http_proxy'),
                'https' => config('tms.https_proxy')
            ]];
        return $this;
    }

    /**
     * 自定义头部
     * @param $headers
     * @return BaseCurl
     */
    public function setHeaders($headers)
    {
        $this->options[] = [
            'headers' => $headers];
        return $this;
    }

    /**
     * 自定义头部
     * @param $auth
     * @return BaseCurl
     */
    public function setAuth($auth)
    {
        $credentials = base64_encode($auth[0] . ':' . $auth[1]);
        $this->headers[] = [
            'Authorization' => 'Basic ' . $credentials
        ];
        return $this;
    }

    /**
     * post请求
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function post()
    {
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'post', [
            'url' => $this->url,
            'data' => $this->data,
        ]);
        try {
            $res = $this->http->post($this->url, $this->options);
            return $this->response($res);
        } catch (\Exception $e) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            throw new BusinessLogicException('请求第三方错误');
        }
    }

    /**
     * get请求
     * @return mixed|null
     * @throws BusinessLogicException
     */
    public function get()
    {
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'get', [
            'url' => $this->url,
            'data' => $this->data,
        ]);
        try {
            $res = $this->http->get($this->url . $this->data, $this->options);
            return $this->response($res);
        } catch (\Exception $e) {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            throw new BusinessLogicException('请求第三方错误');
        }
    }

    /**
     * 返回值检查
     * @param $res MessageInterface
     * @return mixed|null
     */
    public function response($res)
    {
        $bodyData = $res->getBody();
        $res = json_decode((string)$bodyData, true);
//        if ($res->getStatusCode() == 200) {
//            if (!$res) {
//                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '返回不是json数组');
//                return null;
//            }
//            return $res;
//        } else {
//            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . '状态码非200');
//            return null;
//        }
        return $res;
    }
}
