<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderNoRuleValidate extends BaseValidate
{

    public $rules = [
        'type' => 'required|string|max:50',
        'prefix' => 'required|string|max:10',
        'string_length' => 'required|integer|max:6',
        'length' => 'required|integer|max:4',
        'status' => 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => ['type', 'prefix', 'string_length', 'length', 'status'],
        'update' => ['prefix', 'string_length', 'length', 'status']
    ];
}

