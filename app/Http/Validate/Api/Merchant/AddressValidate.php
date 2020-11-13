<?php

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class AddressValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'required|string|max:50',
        'place_street' => 'required|string|max:50',
        'place_address' => 'checkAddress|nullable|string|max:250',
        'lon' => 'required|string|max:50',
        'lat' => 'required|string|max:50',
    ];

    public $scene = [
        'store' => [
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'lon', 'lat',
        ],
        'update' => [
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'lon', 'lat',
        ]
    ];
}

