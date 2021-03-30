<?php
/**
 * 国家 trait
 * User: long
 * Date: 2020/3/9
 * Time: 14:22
 */

namespace App\Traits;

use Illuminate\Support\Arr;

trait CountryTrait
{
    /**
     * 获取国家列表
     * @return array
     */
    public static function getCountryList()
    {
        $countryList = \Illuminate\Support\Facades\Cache::rememberForever('country', function () {
            $country = file_get_contents(config('tms.country_path'));
            $country = array_create_index(json_decode($country, true), 'short');
            return json_encode($country, JSON_UNESCAPED_UNICODE);
        });
        return json_decode($countryList, true);
    }


    /**
     * 通过简称,获取单个国家信息
     * @param $short
     * @return array
     */
    public static function getCountry($short)
    {
        $countryList = self::getCountryList();
        return $countryList[$short] ?? [];
    }


    /**
     * 通过简称,获取国家名称
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

    /**
     * 通过名称获取简称
     * @param $nameList
     * @return array
     */
    public static function getShortListByName($nameList)
    {
        $countryList = self::getCountryList();
        $newNameList = [];
        foreach ($nameList as $name) {
            $country = Arr::first($countryList, function ($country, $key) use ($name) {
                return in_array($name, [$country['cn_name'], $country['en_name']]);
            }, '');
            $newNameList[$name] = $country['short'] ?? $name;
        }
        return $newNameList;
    }
}
