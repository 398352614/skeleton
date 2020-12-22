<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class PackageNoRuleValidate extends BaseValidate
{

    public $rules = [
        'name' => 'required|string|max:50',
        'prefix' => 'required|string|max:50',
        'length' => 'required|integer|lte:20',
        'status' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'store' => ['name', 'prefix', 'length', 'status'],
        'update' => ['name', 'prefix', 'length', 'status']
    ];
}

