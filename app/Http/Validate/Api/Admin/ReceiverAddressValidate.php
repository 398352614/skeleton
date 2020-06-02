<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class ReceiverAddressValidate extends BaseValidate
{
    public $customAttributes = [
        'receiver_fullname' => '收件人姓名',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_city' => '收件人城市',
        'receiver_street' => '收件人街道',
        'receiver_address' => '收件人地址',
        'merchant_id'=>'商户',
        'lon' => '经度',
        'lat' => '纬度',
    ];


    public $rules = [
        'receiver_fullname' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'receiver_country' => 'nullable|string|max:20',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'nullable|string|max:50',
        'receiver_city' => 'nullable|string|max:50',
        'receiver_street' => 'nullable|string|max:50',
        'receiver_address' => 'required|string|max:250',
        'lon' => 'required|string|max:50',
        'lat' => 'required|string|max:50',
        'merchant_id'=>'required|integer',
    ];

    public $scene = [
        'store' => [
            'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat','merchant_id'
        ],
        'update' => [
            'receiver_fullname', 'receiver_phone','receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'lon', 'lat','merchant_id'
        ]
    ];
}

