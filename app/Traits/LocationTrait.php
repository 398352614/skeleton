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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getLocation($country, $city, $street, $houseNumber, $postCode)
    {
        $key = sprintf("%s:%s-%s-%s", 'location', $country, $postCode, $houseNumber);
        $value = Cache::rememberForever($key, self::getLocationDetail($country, $city, $street, $houseNumber, $postCode));
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
     * @return array|\Closure
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function getLocationDetail($country, $city, $street, $houseNumber, $postCode)
    {
        //凹成谷歌要的数据
        $address = [
            'country' => $country,
            //'administrative_area_level_1'=>$province,
            'locality' => $city,//administrative_area_level_2
            //'administrative_area_level_3' => $district,
            'route' => $street,
            'street_number' => $houseNumber,
            'postal_code' => $postCode,
            //'room'=>$roomNumber,
        ];
        return ($country === 'NL') ? self::getLocationDetailFirst($country, $houseNumber, $postCode) : self::getLocationDetailThird($address);
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
                Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'get', ['url' => $url]);
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
                Log::debug(config('thirdParty.location_api_key'));
                Log::debug(config('thirdParty.location_api_secret'));
                Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'res', collect($res)->toArray());
            } catch (\Exception $e) {
                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]);
                throw new BusinessLogicException('由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试');
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
                'country' => $country,
                'province' => $arrayBody['province'],
                'city' => $arrayBody['city'],
                'district' => $arrayBody['municipality'],//相当于是区
                'street' => $arrayBody['street'],
                'house_number' => $arrayBody['houseNumber'],
                'post_code' => $postCode,
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
            } catch (\Exception $e) {
                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]);
                throw new \App\Exceptions\BusinessLogicException('由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试');
            }
            $count = count($featureList);
            if (($count == 0)/* || ($count > 3)*/) {
                throw new \App\Exceptions\BusinessLogicException('由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试');
            }
            return [
                'country'=>$featureList[0]['properties']['country'],
                'province' => $featureList[0]['properties']['state'] ?? '',
                'city' => $featureList[0]['properties']['city'] ?? $city,
                'district' => $featureList[0]['properties']['district'] ?? '',
                'street' => $featureList[0]['properties']['street'] ?? $street,
                'house_number' => $houseNumber,
                'post_code' => $postCode,
                'lon' => $featureList[0]['geometry']['coordinates'][0],
                'lat' => $featureList[0]['geometry']['coordinates'][1],
            ];
        };
    }

    /**
     * @param $address
     * @return \Closure
     */
    private static function getLocationDetailThird($address)
    {
        return function () use ($address) {
            $data = '';
            foreach ($address as $k => $v) {
                if (!empty($address)) {
                    $data = $data . $k . ':' . $v . '|';
                }
            }
            $data = substr($data, 0, -1);
            $url = config('tms.map_url') . 'geocode/json?components=' . $data . '&key=' . config('tms.geocode_key');
            Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'get', [
                'url' => $url,
            ]);
            if ((App::environment() === 'development') || (App::environment() === 'local')) {
                $options = ['proxy' => ['http' => config('tms.http_proxy'), 'https' => config('tms.https_proxy')]];
            } else {
                $options = [];
            }
            try {
                $client = new \GuzzleHttp\Client();
                $result = $client->request('GET', $url, array_merge($options, ['http_errors' => false]));
                $result = json_decode((string)($result->getBody()), TRUE)['results'];
            } catch (\Exception $e) {
                Log::channel('api')->error(__CLASS__ . '.' . __FUNCTION__ . '.' . 'Exception', [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage()
                ]);
                throw new BusinessLogicException('由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试');
            }
            if (empty($result) || count($result) == 0) {
                throw new BusinessLogicException('由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试');
            }
            $addressComponents = collect($result[0]['address_components']);
            $addressResult = [];
            foreach ($addressComponents as $k => $v) {
                foreach ($v['types'] as $x => $y) {
                    $addressResult[$y] = $v['long_name'];
                }
            }
            return [
                'district' => $addressResult['administrative_area_level_4'] ?? '',//相当于是区
                'province' => $addressResult['administrative_area_level_1'] ?? '',//相当于是区
                'country' => $addressResult['country'] ?? $address['country'],
                'city' => $addressResult['locality'] ?? $address['locality'],
                'street' => $addressResult['route'] ?? $address['route'],
                'house_number' => $addressResult['street_number'] ?? $address['street_number'],
                'post_code' => $addressResult['postal_code'] ?? $address['postal_code'],
                'lon' => $result[0]['geometry']['location']['lng'],
                'lat' => $result[0]['geometry']['location']['lat'],
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
