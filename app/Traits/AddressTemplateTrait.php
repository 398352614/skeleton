<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/22
 * Time: 13:44
 */

namespace App\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        print_r($addressTemplate);exit;
        $template = $addressTemplate['template'];
        return json_decode($template, true);
    }

    public static function getFormatAddressTemplate($type)
    {
        $addressTemplate = self::getAddressTemplate();
        if ($type == 'order') {
            $orderReceiverAddress = array_key_prefix($addressTemplate, 'receiver_');
            $senderReceiverAddress = array_key_prefix(Arr::only($addressTemplate, 'sender_'));
            return array_merge($orderReceiverAddress, $senderReceiverAddress);
        }
        if ($type == 'receiver') {
            return array_key_prefix($addressTemplate, 'receiver_');
        }
        if ($type == 'sender') {
            return array_key_prefix($addressTemplate, 'sender_');
        }
        if ($type == 'warehouse') {
            return $addressTemplate;
        }
        return [];
    }
}