<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class AddressImportValidate extends BaseValidate
{
    public $customAttributes = [
        'place_fullname' => '发件人',
        'place_phone' => '电话',
        'place_country' => '国家',
        'place_province' => '省份',
        'place_post_code' => '邮编',
        'place_house_number' => '门牌号',
        'place_city' => '城市',
        'place_district' => '区县',
        'place_street' => '街道',
        'place_address' => '地址',
    ];

    public $rules = [
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9]([0-9- ])*[0-9]$/',
        'place_country' => 'nullable|string|max:20',
        'place_province' => 'nullable|string|max:50',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'nullable|string|max:50',
        'place_district' => 'nullable|string|max:50',
        'place_street' => 'nullable|string|max:50',
        'place_address' => 'nullable|string|max:50',
    ];
    public $message = [

    ];
}
