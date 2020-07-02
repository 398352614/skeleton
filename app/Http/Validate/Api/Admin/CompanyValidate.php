<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'sometimes|nullable|string|max:50',
        'country' => 'sometimes|nullable|string|max:50',
        'contacts' => 'sometimes|nullable|string|max:50',
        'phone' => 'sometimes|nullable|string|max:50|regex:/^[0-9]([0-9-])*[0-9]$/',
        'address' => 'sometimes|nullable|string|max:250',
    ];

    public $scene = [
        'update' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
        ],
    ];
}

