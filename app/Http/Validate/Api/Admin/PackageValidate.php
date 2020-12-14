<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class PackageValidate extends BaseValidate
{

    public $rules = [
        'package_list' => 'required',
        'package_list.*.weight' => 'required|numeric|gte:0',
        'package_list.*.express_first_no' => 'required_with:package_list|string|max:50|regex:/^[0-9a-zA-Z]([0-9a-zA-Z])*[0-9a-zA-Z]$/',
    ];

    public $scene = [
        'fillWeightInfo' => [
            'package_list', 'package_list.*.weight', 'package_list.*.express_first_no'
        ]
    ];
}

