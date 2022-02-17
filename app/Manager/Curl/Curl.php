<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Manager\Curl;

use App\Constants\BaseConstant;
use App\Exception\BusinessException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;

class Curl
{
    protected string $url;

    protected string $key;

    protected int $method;

    protected array $options = [];

    protected array $data;

    protected array $headers = [
        'verify' => false,
    ];

    protected int $times;

    private ClientFactory $clientFactory;

    private Client $http;

    /**
     * Curl constructor.
     * @param ClientFactory $clientFactory
     * @param $method
     * @param $url
     * @param $data
     * @param array $headers
     * @param array $options
     * @param array $auth
     * @param int $needProxy
     * @param int $times
     * @param int $contentType
     * @throws GuzzleException
     */
    public function __construct(
        ClientFactory $clientFactory,
        $method,
        $url,
        $data,
        array $headers = [],
        array $options = [],
        array $auth = [],
        int $needProxy = BaseConstant::NO,
        int $times = 1,
        int $contentType = BaseConstant::CONTENT_TYPE_JSON
    ) {
        $this->clientFactory = $clientFactory;
        $this->validate($method, $url, $data);
        $this->options = $options;
        $this->url = $url;
        $this->data = $data;
        $this->method = $method;
        $this->headers = $headers;
        $this->times = $times;
        $this->setAuth($auth)->setHeaders($this->headers);
        if ($needProxy == BaseConstant::YES) {
            $this->setProxy();
        }
        $this->setData($contentType);
        $this->http = $this->clientFactory->create($options);
        if ($method == BaseConstant::METHOD_GET) {
            return $this->get();
        }
        if ($method == BaseConstant::METHOD_POST) {
            return $this->post();
        }
        return '';
    }

    /**
     * get请求
     * @throws GuzzleException
     */
    public function get(): mixed
    {
        try {
            $res = $this->http->get($this->url . $this->data, $this->options);
            return $this->response($res);
        } catch (\Exception) {
            throw new BusinessException('请求第三方错误');
        }
    }

    /**
     * post请求
     * @throws GuzzleException
     */
    public function post(): mixed
    {
        try {
            $res = $this->http->post($this->url, $this->options);
            return $this->response($res);
        } catch (\Exception $e) {
            throw new BusinessException('请求第三方错误');
        }
    }

    /**
     * 验证
     * @param $method
     * @param $url
     * @param $data
     */
    public function validate($method, $url, $data)
    {
        if (! in_array($method, [BaseConstant::METHOD_GET, BaseConstant::METHOD_POST])) {
            throw new BusinessException('方法未定义');
        }
        if (empty($url)) {
            throw new BusinessException('路由为空');
        }
        if (empty($data)) {
            throw new BusinessException('请求数据为空');
        }
        if ($method == BaseConstant::METHOD_GET || ! is_string($data)) {
            throw new BusinessException('请组装好get请求参数');
        }
    }

    /**
     * 设置内容.
     * @param $contentType
     */
    public function setData($contentType)
    {
        if ($this->method == BaseConstant::METHOD_POST) {
            if ($contentType == BaseConstant::CONTENT_TYPE_FORM_DATA) {
                $this->options[] = ['form_params' => $this->data];
            } elseif ($contentType == BaseConstant::CONTENT_TYPE_JSON) {
                $this->options[] = ['json' => $this->data];
            } else {
                throw new BusinessException('数据类型未定义');
            }
        }
    }

    /**
     * 设置认证
     * @param $auth
     * @return $this
     */
    public function setAuth($auth): static
    {
        $credentials = base64_encode($auth[0] . ':' . $auth[1]);
        $this->headers[] = [
            'Authorization' => 'Basic ' . $credentials,
        ];
        return $this;
    }

    /**
     * 设置表头.
     * @param $headers
     * @return $this
     */
    public function setHeaders($headers): static
    {
        $this->options[] = [
            'headers' => $headers,
        ];
        return $this;
    }

    /**
     * 设置代理.
     * @return $this
     */
    public function setProxy(): static
    {
        $this->options[] = [
            'proxy' => [
                'http' => config('tms.http_proxy'),
                'https' => config('tms.https_proxy'),
            ],
        ];
        return $this;
    }

    /**
     * 返回值
     * @param $res
     */
    public function response($res): mixed
    {
        $bodyData = $res->getBody();
        return json_decode((string) $bodyData, true);
    }
}
