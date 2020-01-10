<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class SenderAddressValidate extends BaseValidate
{
    public $customAttributes = [
        'sender' => '发件人姓名',
        'sender_phone' => '发件人电话',
        'sender_country' => '发件人国家',
        'sender_post_code' => '发件人邮编',
        'sender_house_number' => '发件人门牌号',
        'sender_city' => '发件人城市',
        'sender_street' => '发件人街道',
        'sender_address' => '发件人详细地址',
        'lon' => '经度',
        'lat' => '纬度'
    ];


    public $rules = [
        'sender' => 'required|string|max:50',
        'sender_phone' => 'required|string|max:20',
        'sender_country' => 'required|string|max:20',
        'sender_post_code' => 'required|string|max:50',
        'sender_house_number' => 'required|string|max:50',
        'sender_city' => 'required|string|max:50',
        'sender_street' => 'required|string|max:50',
        'sender_address' => 'nullable|string|max:250',
        'lon' => 'nullable|string|max:50',
        'lat' => 'nullable|string|max:50',
    ];

    public $scene = [
        'store' => ['sender', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address','lon','lat' ],
        'update' => ['sender', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address', 'lon','lat'],
    ];
}

