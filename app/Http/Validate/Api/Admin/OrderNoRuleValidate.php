<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderNoRuleValidate extends BaseValidate
{

    public $rules = [
        'type' => 'required|string|max:50',
        'prefix' => 'required|string|max:10',
        'string_length' => 'nullable|integer',
        'int_length' => 'required|integer',
        'status' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'store' => ['type', 'prefix', 'string_length', 'int_length', 'status'],
        'update' => ['prefix', 'string_length', 'int_length', 'status']
    ];
}

