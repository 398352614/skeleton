<?php
/**
 * 位置
 * User: long
 * Date: 2019/12/27
 * Time: 10:29
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use App\Services\Admin\UploadService;
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
                $url = $url = sprintf("%s/addresses/%s/%s/%s", config('thirdParty.logvcation_api'), $postCode, $houseNumber, $houseNumberAddition);
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
                throw new \App\Exceptions\BusinessLogicException('邮编或门牌号码不正确，请仔细检查输入或联系客服');
            }
            if ((Str::lower($arrayBody['city']) !== Str::lower($city)) || (Str::lower($arrayBody['street']) !== Str::lower($street))) {
                throw new BusinessLogicException('城市或街道不正确');
            }
            if (empty($arrayBody['latitude']) || empty($arrayBody['longitude'])) {
                throw new BusinessLogicException('邮编或门牌号码不正确，请仔细检查输入或联系客服');
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

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public static function getBatchMap($params,$name)
    {
        //https://maps.googleapis.com/maps/api/staticmap?center=Brooklyn+Bridge,New+York,NY&zoom=13&size=600x300&maptype=roadmap
        //&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
        //&markers=color:red%7Clabel:C%7C40.718217,-73.998284
        //&key=YOUR_API_KEY
                $client = new \GuzzleHttp\Client();
                $markers ='&markers=color:blue%7Clabel:A%7C'.$params[0]['lat'].','.$params[0]['lon'];
                for($i=1;$i<count($params);$i++){
                    $markers=$markers.'&markers=color:red%7Clabel:'.$i.'%7C'.$params[$i]['lat'].','.$params[$i]['lon'];
                }
                //$url = 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=127689096,1321755151&fm=15&gp=0.jpg';
        $url =config('tms.map_url').'staticmap?size=640x640&maptype=roadmap'.$markers.'&key='.config('tms.map_key');
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'proxy' => [
                    'http'  => env('HTTP_PROXY'), // Use this proxy with "http"
                    'https' => env('HTTPS_PROXY'), // Use this proxy with "https",
                ]]);
            } catch (\Exception $ex) {
                throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法获取地图，请稍后再尝试');
            }
        $map['image'] = $res->getBody();
        $map['dir'] ='tour';
        $map['name'] = $name;
        return (new \App\Services\Admin\UploadService)->imageDownload($map);
    }
}
