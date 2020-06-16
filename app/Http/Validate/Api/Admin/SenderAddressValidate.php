<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class SenderAddressValidate extends BaseValidate
{
    public $customAttributes = [
        'sender_fullname' => '发件人姓名',
        'sender_phone' => '发件人电话',
        'sender_country' => '发件人国家',
        'sender_post_code' => '发件人邮编',
        'sender_house_number' => '发件人门牌号',
        'sender_city' => '发件人城市',
        'sender_street' => '发件人街道',
        'sender_address' => '发件人详细地址',
        'merchant_id'=>'商户',
        'lon' => '经度',
        'lat' => '纬度'
    ];


    public $rules = [
        'sender_fullname' => 'required|string|max:50',
        'sender_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'sender_post_code' => 'required|string|max:50',
        'sender_house_number' => 'required|string|max:50',
        'sender_city' => 'required|string|max:50',
        'sender_street' => 'required|string|max:50',
        'sender_address' => 'checkAddress|nullable|string|max:250',
        'lon' => 'nullable|string|max:50',
        'lat' => 'nullable|string|max:50',
        'merchant_id'=>'required|integer',
    ];

    public $scene = [
        'store' => ['sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address','lon','lat','merchant_id' ],
        'update' => ['sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address', 'lon','lat','merchant_id'],
    ];
}

