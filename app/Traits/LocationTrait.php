<?php
/**
 * 位置
 * User: long
 * Date: 2019/12/27
 * Time: 10:29
 */

namespace App\Traits;

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
        $url = sprintf('%s?%s', config('thirdParty.location_api'), http_build_query(['q' => $country . '+' . $city . '+' . $street . '+' . $houseNumber . '+' . $postCode]));
        try {
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $url, ['http_errors' => false, 'timeout' => 3]);
            $featureList = json_decode((string)($result->getBody()), TRUE)['features'];
        } catch (\Exception $ex) {
            throw new \App\Exceptions\BusinessLogicException('可能由于网络问题，无法获取具体信息，请稍后再尝试');
        }
        $count = count($featureList);
        if (($count == 0) || ($count > 3)) {
            throw new \App\Exceptions\BusinessLogicException('国家,城市,街道,门牌号或邮编不正确，请仔细检查输入或联系客服');
        }
        return ['lon' => $featureList[0]['geometry']['coordinates'][0], 'lat' => $featureList[0]['geometry']['coordinates'][1]];
    }
}