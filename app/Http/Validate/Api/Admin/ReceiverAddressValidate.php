<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class ReceiverAddressValidate extends BaseValidate
{
    public $customAttributes = [
        'receiver' => '收件人姓名',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_city' => '收件人城市',
        'receiver_street' => '收件人街道',
        'receiver_address' => '收件人地址',
        'lon' => '经度',
        'lat' => '纬度',
    ];


    public $rules = [
        'receiver' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20',
        'receiver_country' => 'required|string|max:20',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'required|string|max:50',
        'receiver_city' => 'required|string|max:50',
        'receiver_street' => 'required|string|max:50',
        'receiver_address' => 'required|string|max:250',
        'lon' => 'required|string|max:50',
        'lat' => 'required|string|max:50',
    ];

    public $scene = [
        'store' => [
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat',
        ],
        'update' => [
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat',
        ]
    ];
}

