<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'unique:company,name|required|string|max:50',
        'country' => 'sometimes|nullable|string|max:50',
        'contacts' => 'sometimes|nullable|string|max:50',
        'phone' => 'sometimes|nullable|string|max:50|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'address' => 'sometimes|nullable|string|max:250',
        'web_site' => 'sometimes|nullable|string|max:250|url',
        'system_name' => 'sometimes|nullable|string|max:50',
        'logo_url' => 'sometimes|nullable|string|max:250|url',
        'login_logo_url' => 'sometimes|nullable|string|max:250|url',
    ];

    public $scene = [
        'update' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
            'web_site',
            'system_name',
            'logo_url',
            'login_logo_url',
        ],
    ];
}

