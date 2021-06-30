<?php

namespace App\Traits;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

/**
 * 国家地址
 * User: long
 * Date: 2020/5/11
 * Time: 16:05
 */
trait CountryAddressTrait
{
    public static function getCountry($country)
    {
        $key = sprintf("%s:%s", 'country', $country);
        $value = Cache::rememberForever($key, self::getCountryDetail($country));
        return $value;
    }


    public static function getCountryDetail($country)
    {
        return ($country === 'CN') ? self::getCNCountryDetail($country) : self::getOtherCountryDetail($country);
    }

    /**
     * 获取中国信息
     * @param $country
     * @return \Closure
     */
    public static function getCNCountryDetail($country)
    {
        return function () use ($country) {
            $client = new \GuzzleHttp\Client();
            //1.请求版本号
            $url = sprintf("%s/public/pcd/version.json", config('thirdParty.country_cn_api'));
            try {
                $res = $client->request('GET', $url, ['http_errors' => false]);
                $body = $res->getBody();
                $stringBody = (string)$body;
                $arrayBody = json_decode($stringBody, TRUE);
                $version = $arrayBody["version"];
            } catch (ClientException $e) {
                throw new \App\Exceptions\BusinessLogicException('由于网络问题，无法获取国家信息，请稍后尝试');
            }

            //2.根据版本号请求PCD
            $url = sprintf("%s/public/pcd/%s/pcd.json", config('thirdParty.country_cn_api'), $version);
            try {
                $res = $client->request('GET', $url, ['http_errors' => false]);
            } catch (ClientException $e) {
                throw new \App\Exceptions\BusinessLogicException('由于网络问题，无法获取国家信息，请稍后尝试');
            }
            if ($res->getStatusCode() !== 200) {
                throw new \App\Exceptions\BusinessLogicException('系统无相关国家信息');
            }
            $body = $res->getBody();
            $stringBody = (string)$body;
            $arrayBody = json_decode($stringBody, TRUE);

            //3.数据组装
            $stateArr = [];
            foreach ($arrayBody['children'] as $state) {
                if ($state['children']) {
                    $cityArr = [];
                    foreach ($state['children'] as $city) {
                        $districtArr = NULL;
                        if ($city['children']) {
                            foreach ($city['children'] as $district) {
                                $districtArr[] = [
                                    'name' => $district['value'],
                                    'code' => $district['id'],
                                    'postcode' => $district['postnumber'] ?? '',
                                    'letter' => $district['letter'],
                                ];
                            }
                        }
                        $cityArr[] = [
                            'name' => $city['value'],
                            'code' => $city['id'],
                            'district' => array_values(Arr::sort($districtArr, function ($value) {
                                return $value['letter'];
                            })),
                            'postcode' => $city['postnumber'] ?? '',
                            'letter' => $city['letter'],
                        ];
                    }
                }
                $stateArr[] = [
                    'name' => $state['value'],
                    'code' => $state['id'],
                    'city' => array_values(Arr::sort($cityArr, function ($value) {
                        return $value['letter'];
                    })),
                    'postcode' => "",
                    'letter' => $state['letter'],
                ];
            }
            $data = array_values(Arr::sort($stateArr, function ($value) {
                return $value['letter'];
            }));
            return $data;
        };
    }

    /**
     * 获取其他国家信息
     * @param $country
     * @return \Closure
     */
    public static function getOtherCountryDetail($country)
    {
        $lang = in_array($country, ['TW', 'HK']) ? 'zh-cn' : 'en';
        $url = sprintf('%s/?lang=%s&country=%s', config('thirdParty.country_api'), $lang, $country);
        return function () use ($country, $lang, $url) {
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('GET', $url, ['http_errors' => false]);
            } catch (ClientException $e) {
                throw new \App\Exceptions\BusinessLogicException('由于网络问题，无法获取国家信息，请稍后尝试');
            }
            if ($res->getStatusCode() !== 200) {
                throw new \App\Exceptions\BusinessLogicException('系统无相关国家信息');
            }
            $body = $res->getBody();
            $stringBody = (string)$body;
            $arrayBody = json_decode($stringBody, TRUE);
            if (!empty($arrayBody['status']) && $arrayBody['status'] == 1) {
                throw new \App\Exceptions\BusinessLogicException('系统无相关国家信息');
            }
            return $arrayBody['Country'];
        };
    }
}
