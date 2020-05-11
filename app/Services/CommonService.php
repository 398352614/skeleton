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
use App\Traits\CountryAddressTrait;
use App\Traits\LocationTrait;
use Doctrine\DBAL\Driver\OCI8\Driver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


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
        if ($params['country'] !== 'NL') {
            $checkfile = Validator::make($params, ['city' => 'required', 'street' => 'required']);
            if ($checkfile->fails()) {
                $error = array_values($checkfile->errors()->getMessages())[0][0];
                throw new BusinessLogicException($error, 301);
            }
        }
        return LocationTrait::getLocation($params['country'], $params['city'], $params['street'], $params['house_number'], $params['post_code']);
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
}
