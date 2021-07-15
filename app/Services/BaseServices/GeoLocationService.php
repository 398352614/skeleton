<?php

namespace App\Services\BaseServices;

use App\Exceptions\BusinessLogicException;
use App\Services\CurlClient;
use Illuminate\Support\Facades\Log;

/**
 * 地理位置反编码 -- 位置转经纬度
 */
class GeoLocationService
{
    //德国的一个地理位置反编码 api
    const EU_BASE_URL = 'http://photon.komoot.de/api/?q=';

    /**
     * @var CurlClient
     */
    private $curl;

    public function __construct()
    {
        $this->curl = new CurlClient();
    }

    /**
     * 根据地址详细信息转经纬度
     */
    public function GetCode($location): ?array
    {
        if (count($location) === 0) {
            return null;
        }

        $key = implode('+', $location);

        return $this->getCodeUseString($key);
    }

    /**
     * 根据关键词进行查询
     */
    public function getCodeUseString(string $key): ?array
    {
        $res = $this->curl->get(self::EU_BASE_URL . $key);
        Log::channel('api')->info(__CLASS__ .'.'. __FUNCTION__ .'.'. 'res', $res);

        if (!$res || count($res['features']) === 0) {
            return null;
        }

        if (count($res['features']) > 1) {
            throw new BusinessLogicException('地址不够精确,请检查');
        }

        return self::dealEuApiRet($res['features'][0]);
    }

    /**
     * 处理 eu api 的结果为合适的格式
     */
    public static function dealEuApiRet(array $origin): array
    {
        return [
            'latitude'      => $origin['geometry']['coordinates'][1],       // 纬度
            'longitude'    => $origin['geometry']['coordinates'][0],        // 经度
        ];
    }
}
