<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class BillVerifyValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'object_type' => 'nullable|string',
        'object_no' => 'nullable|string',
        'remark' => 'nullable|string',
        'picture_list' => 'nullable|string',
        'pay_type' => 'required|integer|in:1,2,3,4',
        'mode' => 'required|integer|in:1,2',
        'type' => 'required|integer',
        'create_date' => 'required|date',
        'expect_amount' => 'required|numeric|gte:0',
        'actual_amount' => 'nullable|numeric|gte:0',
        'status' => 'required|integer|in:1,2',
        'verify_status' => 'required|string|in:1,2,3',
//        'payer_id' => 'required|integer',
        'merchant_id' => 'required|integer',
        'payer_type' => 'required|string|in:1,2,3,4,5',
        'payer_name' => 'nullable|string',
        'payee_id' => 'required|integer',
        'payee_type' => 'required|string|in:1,2,3,4,5',//
        'payee_name' => 'nullable|string',
        'operator_id' => 'required|integer',
        'operator_type' => 'required|string|in:1,2,3,4,5',//
        'operator_name' => 'nullable|string',
        'bill_list'=>'required',
        'bill_list.*.bill_no'=>'required|string',
        'bill_list.*.pay_type'=>'required|string'

    ];

    public $scene = [
        'store' => [
            'bill_list',
            'bill_list.*.bill_no',
        ],

        'verify' => [
            'actual_amount',
            'status',
            'bill_list',
            'bill_list.*.pay_type'
        ]
    ];

    public $message = [

    ];
}

