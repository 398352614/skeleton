<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class LedgerValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'user_id'=> 'required|string|max:50',
        'user_type'=> 'required|integer|in:1,2,3,4,5',
        'balance'=>'nullable|numeric|gte:0',
        'credit'=>'nullable|numeric|gte:0',
        'create_date'=>'required|date',
        'pay_type'=> 'required|integer|in:1,2,3,4',
        'verify_type'=> 'required|integer|in:1,2',
        'status'=> 'required|integer|in:1,2',
    ];

    public $scene = [
        'store' => [
            'user_id', 'user_type', 'balance', 'credit',
            'create_date', 'pay_type', 'verify_type', 'status'
        ],
        'update' => [
            'credit',
        ],

    ];

    public $message = [

    ];
}

