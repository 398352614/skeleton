<?php

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class AddressValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'place_province' => 'nullable|string|max:50',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'required|string|max:50',
        'place_district' => 'nullable|string|max:50',
        'place_street' => 'required|string|max:50',
        'place_address' => 'checkAddress|nullable|string|max:250',
        'place_lon' => 'required|string|max:50',
        'place_lat' => 'required|string|max:50',
    ];

    public $scene = [
        'store' => [
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lat', 'place_lat',
        ],
        'update' => [
            'place_fullname', 'place_phone', 'place_post_code', 'place_house_number',
            'place_city', 'place_street', 'place_address', 'place_lon', 'place_lat',
        ]
    ];
}

