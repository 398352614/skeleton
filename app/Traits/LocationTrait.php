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
     * @param $postCode
     * @param $houseNumber
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     * @throws \Exception
     */
    public static function getLocation($postCode, $houseNumber)
    {
        $url = sprintf('%s?%s', config('thirdParty.location_api'), http_build_query(['q' => $postCode . '+' . $houseNumber]));
        try {
            $client = new \GuzzleHttp\Client();
            $result = $client->request('GET', $url, ['http_errors' => false]);
        } catch (\Exception $ex) {
            throw new \Exception('可能由于网络问题，无法根据邮编和门牌号码获取具体信息，请稍后再尝试');
        }
        $featureList = json_decode($result, true);
        if (count($featureList) > 1) {
            throw new \App\Exceptions\BusinessLogicException('邮编和门牌号码不正确，请仔细检查输入或联系客服');
        }
        return [$featureList[0]['geometry']['coordinates'][0], $featureList[0]['geometry']['coordinates'][1]];
    }
}