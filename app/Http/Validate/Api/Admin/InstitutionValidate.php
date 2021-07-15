<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class InstitutionValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'required|string|max:50',
        'country' => 'sometimes|nullable|string|max:50',
        'contacts' => 'sometimes|nullable|string|max:20',
        'phone' => 'sometimes|nullable|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'address' => 'sometimes|nullable|string|max:250',
        'parent_id' => 'required|integer',
    ];

    public $scene = [
        'update' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
        ],
        'store' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
            'parent_id'
        ],
    ];
}

