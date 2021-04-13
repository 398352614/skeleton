<?php
/**
 * 位置
 * User: long
 * Date: 2019/12/27
 * Time: 10:29
 */

namespace App\Traits;

use App\Exceptions\BusinessLogicException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $key = sprintf("%s:%s-%s-%s", 'location', $country, $postCode, $houseNumber);

        return Cache::rememberForever($key, self::getLocationDetail($country, $city, $street, $houseNumber, $postCode));
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
     * @return array|\Closure
     * @throws BusinessLogicException
     */
    private static function getLocationDetail($country, $city, $street, $houseNumber, $postCode)
    {
        Log::info('country', ['country' => $country]);
        return ($country === 'NL') ? self::getLocationDetailFirst($country, $houseNumber, $postCode) : self::getLocationDetailSecond($country, $city, $street, $houseNumber, $postCode);
    }

    /**
     * 获取地址信息方法一
     * @param $country
     * @param $city
     * @param $street
     * @param $houseNumber
     * @param $postCode
     * @return \Closure
     */
    private static function getLocationDetailFirst($country, $houseNumber, $postCode)
    {
        list($houseNumber, $houseNumberAddition) = self::splitHouseNumber($houseNumber);
        return function () use ($country, $houseNumber, $postCode, $houseNumberAddition) {
            try {
                $client = new \GuzzleHttp\Client();
                $url = sprintf("%s/addresses/%s/%s/%s", config('thirdParty.location_api'), $postCode, $houseNumber, $houseNumberAddition);
                Log::info('location-url', ['url' => $url]);
                $res = $client->request('GET', $url, [
                        'auth' =>
                            [
                                config('thirdParty.location_api_key'),
                                config('thirdParty.location_api_secret')
                            ],
                        'http_errors' => false,
                        'timeout' => 50
                    ]
                );
            } catch (\Exception $ex) {
                Log::info('location-ex', ['message' => $ex->getMessage()]);
                throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法根据邮编和门牌号码获取城市和地址信息，请稍后再尝试');
            }
            $body = $res->getBody();
            $stringBody = (string)$body;
            $arrayBody = json_decode($stringBody, TRUE);

            if ($res->getStatusCode() !== 200) {
                throw new \App\Exceptions\BusinessLogicException('邮编或门牌号码不正确，请仔细检查输入或联系客服');
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
     * 获取地址信息方法二
     * @param $country
     * @param $city
     * @param $street
     * @param $houseNumber
     * @param $postCode
     * @return \Closure
     */
    private static function getLocationDetailSecond($country, $city, $street, $houseNumber, $postCode)
    {
        return function () use ($country, $city, $street, $houseNumber, $postCode) {
            $url = sprintf('%s?%s', config('thirdParty.location_api_another'), http_build_query(['q' => $country . '+' . $city . '+' . $street . '+' . $houseNumber . '+' . $postCode]));
            try {
                $client = new \GuzzleHttp\Client();
                $result = $client->request('GET', $url, ['http_errors' => false, 'timeout' => 10]);
                $featureList = json_decode((string)($result->getBody()), TRUE)['features'];
            } catch (\Exception $ex) {
                Log::info($ex->getMessage());
                throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法获取具体信息，请稍后再尝试');
            }
            $count = count($featureList);
            if (($count == 0)/* || ($count > 3)*/) {
                throw new \App\Exceptions\BusinessLogicException('国家，城市，街道，门牌号或邮编不正确，请仔细检查输入或联系客服');
            }
            Log::info('返回值',$featureList);
            return [
                'province' => $featureList[0]['properties']['state'] ?? '',
                'city' => $featureList[0]['properties']['city']  ?? $city,
                'district' => $featureList[0]['properties']['district'] ?? '',
                'street' => $featureList[0]['properties']['street'] ??$street,
                'house_number' => $houseNumber,
                'lon' => $featureList[0]['geometry']['coordinates'][0],
                'lat' => $featureList[0]['geometry']['coordinates'][1],
            ];
        };
    }


    /**
     * 获取站点地图
     * @param $params
     * @param $name
     * @return array
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getBatchMap($params, $name)
    {
        $markers = '&markers=color:blue%7Clabel:A%7C' . $params[0]['lat'] . ',' . $params[0]['lon'];
        for ($i = 1; $i < count($params); $i++) {
            $markers = $markers . '&markers=color:red%7Clabel:' . $i . '%7C' . $params[$i]['lat'] . ',' . $params[$i]['lon'];
        }
        //$url = 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=127689096,1321755151&fm=15&gp=0.jpg';
        $url = config('tms.map_url') . 'staticmap?size=640x640&maptype=roadmap' . $markers . '&key=' . config('tms.map_key');
//        try {
            if ((App::environment() === 'development') || (App::environment() === 'local')) {
                $options = ['proxy' => ['http' => config('tms.vpn'), 'https' => config('tms.vpn')]];
            } else {
                $options = [];
            }
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, $options);
//        } catch (\Exception $ex) {
//            throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法获取地图，请稍后再尝试');
//        }
        $map['image'] = $res->getBody();
        $map['dir'] = 'tour';
        $map['name'] = $name;
        return (new \App\Services\Admin\DownloadService)->imageDownload($map);
    }
}
