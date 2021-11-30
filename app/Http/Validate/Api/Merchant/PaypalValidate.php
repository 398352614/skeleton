<?php

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class PaypalValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [


        'bill_no' => 'required',
        'payment_id' => 'required',
        'payer_id' => 'nullable',
        'status' => 'required|integer|in:2,3,',
        'amount' => 'required|numeric|gte:0'
    ];

    public $scene = [
        'store' => [
            'bill_no'
        ],
        'pay' => [
            'payment_id', 'payer_id', 'status', 'amount',

        ],

    ];

    public $message = [

    ];
}

