<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class AddressImportValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
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
