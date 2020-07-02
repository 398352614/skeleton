<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderNoRuleValidate extends BaseValidate
{

    public $rules = [

    ];

    public $scene = [
        'store' => ['type', 'prefix', 'string_length', 'int_length', 'status'],
        'update' => ['prefix', 'string_length', 'int_length', 'status']
    ];
}

