<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class SenderAddressValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'sender_fullname' => 'required|string|max:50',
        'sender_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'sender_post_code' => 'required|string|max:50',
        'sender_house_number' => 'required|string|max:50',
        'sender_city' => 'required|string|max:50',
        'sender_street' => 'required|string|max:50',
        'sender_address' => 'checkAddress|nullable|string|max:250',
        'sender_lon' => 'nullable|string|max:50',
        'sender_lat' => 'nullable|string|max:50',
        'merchant_id' => 'required|integer',
    ];

    public $scene = [
        'store' => ['sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address', 'sender_lon', 'sender_lat', 'merchant_id'],
        'update' => ['sender_fullname', 'sender_phone', 'sender_country', 'sender_post_code',
            'sender_house_number', 'sender_city', 'sender_street', 'sender_address', 'sender_lon', 'sender_lat', 'merchant_id'],
    ];
}

