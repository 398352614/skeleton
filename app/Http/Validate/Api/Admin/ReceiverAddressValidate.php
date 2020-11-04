<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class ReceiverAddressValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'receiver_fullname' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'receiver_country' => 'nullable|string|max:20',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'required|string|max:50',
        'receiver_city' => 'required|string|max:50',
        'receiver_street' => 'required|string|max:50',
        'receiver_address' => 'checkAddress|nullable|string|max:250',
        'receiver_lon' => 'required|string|max:50',
        'receiver_lat' => 'required|string|max:50',
        'merchant_id' => 'required|integer',
    ];

    public $scene = [
        'store' => [
            'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'receiver_lon', 'receiver_lat', 'merchant_id'
        ],
        'update' => [
            'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address', 'receiver_lon', 'receiver_lat', 'merchant_id'
        ]
    ];
}

