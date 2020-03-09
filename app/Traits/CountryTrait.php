<?php
/**
 * 国家 trait
 * User: long
 * Date: 2020/3/9
 * Time: 14:22
 */

namespace App\Traits;
trait CountryTrait
{
    /**
     * 获取国家列表
     * @return array
     */
    public static function getCountryList()
    {
        $countryList = \Illuminate\Support\Facades\Cache::rememberForever('country', function () {
            $country = \Illuminate\Support\Facades\Storage::disk('public')->get('country.json');
            $country = array_create_index(json_decode($country, true), 'short');
            return json_encode($country, JSON_UNESCAPED_UNICODE);
        });
        return json_decode($countryList, true);
    }

    /**
     * 通过简称,获取单个国家信息
     * @param $short
     * @return string
     */
    public static function getCountryName($short)
    {
        $countryList = self::getCountryList();
        $locate = (\Illuminate\Support\Facades\App::getLocale() !== 'cn') ? 'en' : 'cn';
        $value = $countryList[$short][$locate . '_name'] ?? $short;
        unset($countryList);
        return $value;
    }


}