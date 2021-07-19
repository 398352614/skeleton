<?php
/**
 * 公共 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:35
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\Address;
use App\Models\Country;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\CountryAddressTrait;
use App\Traits\LocationTrait;
use App\Traits\PostcodeTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
        empty($params['country']) && $params['country'] = CompanyTrait::getCountry();
        if ($params['post_code'] > 9999) {
            $params['country'] = BaseConstService::POSTCODE_COUNTRY_DE;
        }
        if ($params['country'] == BaseConstService::POSTCODE_COUNTRY_NL && post_code_be($params['post_code'])) {
            $params['country'] = BaseConstService::POSTCODE_COUNTRY_BE;
        }
        $address = DB::table('address')->where('place_country', $params['country'])->where('place_house_number', $params['house_number'])->where('place_post_code', $params['post_code'])->first();
        if (!empty($address) && CompanyTrait::getCountry() == BaseConstService::POSTCODE_COUNTRY_NL) {
            $address = collect($address)->toArray();
            $data = [
                'country' => $address['place_country'],
                'city' => $address['place_city'],
                'street' => $address['place_street'],
                'house_number' => $address['place_house_number'],
                'lat' => $address['place_lat'],
                'lon' => $address['place_lon'],
            ];
        } else {
            $data = LocationTrait::getLocation($params['country'], $params['city'] ?? '', $params['street'] ?? '', $params['house_number'], $params['post_code']);
        }
        return $data;
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
        $data = [];
        $reflection = new \ReflectionClass(ConstTranslateTrait::class);
        $result = collect($reflection->getProperties())->pluck('name')->toArray();
        foreach ($result as $k => $v) {
            $data[$v] = ConstTranslateTrait::formatList(ConstTranslateTrait::$$v);
        }
        return $data;
    }
}
