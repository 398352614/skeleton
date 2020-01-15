<?php
/**
 * 位置
 * User: long
 * Date: 2019/12/27
 * Time: 10:29
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait LocationTrait
{
    /**
     * 获取经纬度
     * @param $country
     * @param $city
     * @param $street
     * @param $houseNumber
     * @param $postCode
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     * @throws \Exception
     */
    public static function getLocation($country, $city, $street, $houseNumber, $postCode)
    {
        list($houseNumber, $houseNumberAddition) = self::splitHouseNumber($houseNumber);
        $key = sprintf("%s:%s-%s-%s", 'location', $country, $postCode, $houseNumber);
        $value = Cache::rememberForever($key, self::getLocationDetail($country, $city, $street, $houseNumber, $postCode, $houseNumberAddition));
        return $value;
    }

    /**
     * 分隔门牌号
     * @param $houseNumber
     * @return array
     */
    private static function splitHouseNumber($houseNumber)
    {
        $houseNumberAddition = '';
        if (preg_match('~^(?<number>[0-9]+)(?:[^0-9a-zA-Z]+(?<addition1>[0-9a-zA-Z ]+)|(?<addition2>[a-zA-Z](?:[0-9a-zA-Z ]*)))?$~', $houseNumber, $match)) {
            $houseNumber = $match['number'];
            $houseNumberAddition = isset($match['addition2']) ? $match['addition2'] : (isset($match['addition1']) ? $match['addition1'] : '');
        }
        return [$houseNumber, $houseNumberAddition];
    }

    /**
     * 获取地址详情
     * @param $country
     * @param $city
     * @param $street
     * @param $houseNumber
     * @param $postCode
     * @param $houseNumberAddition
     * @return \Closure
     */
    private static function getLocationDetail($country, $city, $street, $houseNumber, $postCode, $houseNumberAddition)
    {
        return function () use ($country, $city, $street, $houseNumber, $postCode, $houseNumberAddition) {
            try {
                $client = new \GuzzleHttp\Client();
                $url = $url = sprintf("%s/addresses/%s/%s/%s", config('thirdParty.location_api'), $postCode, $houseNumber, $houseNumberAddition);
                $res = $client->request('GET', $url, [
                        'auth' =>
                            [
                                config('thirdParty.location_api_key'),
                                config('thirdParty.location_api_secret')
                            ],
                        'http_errors' => false
                    ]
                );
            } catch (\Exception $ex) {
                throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法根据邮编和门牌号码获取城市和地址信息，请稍后再尝试');
            }
            $body = $res->getBody();
            $stringBody = (string)$body;
            $arrayBody = json_decode($stringBody, TRUE);

            if ($res->getStatusCode() !== 200) {
                throw new \Exception('邮编或门牌号码不正确，请仔细检查输入或联系客服');
            }
            if ((Str::lower($arrayBody['city']) !== Str::lower($city)) || (Str::lower($arrayBody['street']) !== Str::lower($street))) {
                throw new BusinessLogicException('城市或街道不正确');
            }
            return [
                'province' => $arrayBody['province'],
                'city' => $arrayBody['city'],
                'district' => $arrayBody['municipality'],//相当于是区
                'street' => $arrayBody['street'],
                'house_number' => $arrayBody['houseNumber'],
                'lat' => $arrayBody['latitude'],
                'lon' => $arrayBody['longitude'],
            ];
        };
    }
}