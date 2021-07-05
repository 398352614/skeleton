<?php
/**
 * 公共 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:35
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\Country;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryAddressTrait;
use App\Traits\LocationTrait;
use App\Traits\PostcodeTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class CommonService
{

    /**
     * 获取地址经纬度
     * @param $params
     * @return mixed
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLocation($params)
    {
        empty($params['country']) && $params['country'] = CompanyTrait::getCountry();
        Log::info(CompanyTrait::getCountry());
        if($params['post_code'] > 9999){
            $params['country'] = BaseConstService::POSTCODE_COUNTRY_DE;
        }
        return LocationTrait::getLocation($params['country'], $params['city'] ?? '', $params['street'] ?? '', $params['house_number'], $params['post_code']);
    }

    /**
     * 获取国家列表
     * @return mixed
     */
    public function getCountryList()
    {
        $countryList = Country::query()->get(['id', 'short', 'cn_name', 'en_name', 'tel'])->toArray();
        if (empty($countryList)) return [];
        //获取语言
        $locate = (App::getLocale() !== 'cn') ? 'en' : 'cn';
        //获取字段
        $columnName = $locate . '_name';
        $delColumnName = ($columnName === 'en_name') ? 'en_name' : 'en_name';
        //字段处理
        $countryList = array_map(function ($country) use ($columnName, $delColumnName) {
            $country['name'] = $country[$columnName];
            unset($country[$columnName], $country[$delColumnName]);
            return $country;
        }, $countryList);
        return $countryList;
    }


    public function getCountryAddress($country)
    {
        return CountryAddressTrait::getCountry($country);
    }

    public function getPostcode(array $all)
    {
        return PostcodeTrait::getPostcode($all);
    }


    /**
     * 地址字段组合
     * @param $data
     * @param $fields
     * @return string
     */
    public static function addressFieldsSortCombine($data, $fields)
    {
        $countryKey = Arr::first($fields, function ($keyValue) {
            return in_array($keyValue, ['country', 'place_country', 'second_place_country']);
        });
        if (!empty($countryKey) && !empty($data[$countryKey]) && (app()->getLocale() == 'cn') && ($data[$countryKey] == 'CN')) {
            $data[$countryKey] = '中国';
        }
        $address = implode(' ', array_filter(array_only_fields_sort($data, $fields)));
        return $address;
    }


    public function dictionary()
    {
        $data=[];
        $reflection = new \ReflectionClass(ConstTranslateTrait::class);
        $result = collect($reflection->getProperties())->pluck('name')->toArray();
        foreach ($result as $k => $v) {
            $data[$v] = ConstTranslateTrait::formatList(ConstTranslateTrait::$$v);
        }
        return $data;
    }
}
