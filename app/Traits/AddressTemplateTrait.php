<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/22
 * Time: 13:44
 */

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

trait AddressTemplateTrait
{
    /**
     * 获取地址模板
     * @return array|mixed
     */
    public static function getAddressTemplate()
    {
        $rootKey = config('tms.cache_prefix.address_template');
        $tag = config('tms.cache_tags.address_template');
        $company = CompanyTrait::getCompany();
        if (empty($company['address_template_id'])) {
            return [];
        }
        $addressTemplate = Cache::tags($tag)->get($rootKey . $company['address_template_id']);
        if (empty($addressTemplate)) {
            Artisan::call('cache:address-template');
            $addressTemplate = Cache::tags($tag)->get($rootKey . $company['address_template_id']);
        }
        return json_decode($addressTemplate['template'], true);
    }

    public static function getFormatAddressTemplate($type)
    {
        $addressTemplate = self::getAddressTemplate();
        if ($type == 'order') {
            $orderPlaceAddress = array_key_prefix($addressTemplate, 'place_');
            $second_PlaceAddress = array_key_prefix(Arr::only($addressTemplate, 'second_place_'));
            return array_merge($orderPlaceAddress, $second_PlaceAddress);
        }
        if ($type == 'place_') {
            return array_key_prefix($addressTemplate, 'place_');
        }
        if ($type == 'second_place_') {
            return array_key_prefix($addressTemplate, 'second_place_');
        }
        if ($type == 'warehouse') {
            return $addressTemplate;
        }
        return [];
    }
}
