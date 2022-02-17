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
use App\Model\User;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Psr\Http\Message\MessageInterface;

class GoogleMapCurl extends Curl
{
    /**
     * 重试次数，未实装.
     */
    public int $times = 3;

    public string $key;

    /**
     * BaseCurl constructor.
     * @param $method
     * @param $urlSuffix
     * @param $data
     * @throws GuzzleException
     */
    public function __construct($method, $urlSuffix, $data)
    {
        $this->url = config('tms.map_url');
        $clientFactory = new ClientFactory::class;
        parent::__construct(
            $clientFactory,
            $method,
            $this->url . $urlSuffix,
            $data,
            [],
            [],
            [],
            BaseConstant::NO,
            $this->times,
            BaseConstant::CONTENT_TYPE_FORM_DATA
        );
        $this->setKey();
    }

    /**
     * 自定义Key.
     * @throws BusinessException
     */
    public function setKey()
    {
        $user = auth()->user();
        if (empty($user['key'])) {
            $this->key = $this->getDefaultKey();
        } else {
            $this->key = User::query()->where('id', $user['id'])->get()['url'];
        }
        $this->url = $this->url . '&key=' . $this->key;
    }

    public function getDefaultKey()
    {
        return config('default_key');
    }

    /**
     * 返回值检验.
     * @param MessageInterface $res
     * @return null|mixed|void
     * @throws BusinessException
     */
    public function response($res): mixed
    {
        if ($res->getStatusCode() !== 'OK') {
            throw new BusinessException('google请求报错');
        }
        return parent::response($res);
    }
}
