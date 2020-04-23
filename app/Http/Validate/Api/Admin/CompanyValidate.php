<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '公司名称',
        'country' => '所在国家',
        'contacts' => '公司联系人',
        'phone' => '公司电话',
        'address' => '公司地址',
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

