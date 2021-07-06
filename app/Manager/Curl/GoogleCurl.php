<?php

/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2019-05-21
 * Time: 10:34
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\MapConfig;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\MessageInterface;

class GoogleCurl extends BaseCurl
{
    /**
     * 重试次数，未实装
     * @var int
     */
    public $times = 3;

    public $key;

    /**
     * BaseCurl constructor.
     * @param $method
     * @param $urlSuffix
     * @param $data
     * @throws BusinessLogicException
     */
    public function __construct($method, $urlSuffix, $data)
    {
        $this->url = config('tms.map_url');
        parent::__construct($method, $this->url . $urlSuffix, $data, [], [], [], BaseConstService::NO, $this->times, BaseConstService::CONTENT_TYPE_FORM_DATA);
        $this->setKey();
    }

    /**
     * 自定义Key
     * @return void
     * @throws BusinessLogicException
     */
    public function setKey()
    {
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
        $this->url = $this->url . '&key=' . $this->key;
    }

    /**
     * 返回值检验
     * @param MessageInterface $res
     * @return mixed|void|null
     * @throws BusinessLogicException
     */
    public function response($res)
    {
        if ($res->getStatusCode() !== 'OK') {
            Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', [$res]);
            throw new BusinessLogicException('google请求报错');
        }
        return parent::response($res);
    }
}
