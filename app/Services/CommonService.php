<?php
/**
 * 公共 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:35
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Traits\LocationTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class CommonService
{
    /**
     * 获取地址经纬度
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getLocation($params)
    {
        return LocationTrait::getLocation($params['country'], $params['city'], $params['street'], $params['house_number'], $params['post_code']);
    }

    /**
     * 获取国家列表
     * @return mixed
     */
    public function getCountryList()
    {
        $countryList = Cache::rememberForever('country', function () {
            $country = Storage::disk('public')->get('country.json');
            return $country;
        });
        return $countryList;
    }
}