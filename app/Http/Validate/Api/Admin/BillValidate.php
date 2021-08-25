<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class BillValidate extends BaseValidate
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
        'status' => 'required|integer|in:1,2,3,',
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
        'create_timing' => 'required|integer|in:1,2,3,4',
        'pay_timing' => 'required|integer|in:1,2,3,4'
    ];

    public $scene = [
        'store' => [
            'object_type', 'object_no', 'remark', 'picture_list',
            'pay_type', 'mode', 'type', 'create_date',
            'expect_amount', 'actual_amount', 'status', 'verify_status',
            'payer_id', 'payer_type', 'payer_name', 'payee_type',
            'payer_name', 'payee_id', 'operator_id', 'operator_type',
            'operator_name', 'create_timing', 'pay_timing'
        ],
        'update' => [
            'object_type', 'object_no', 'remark', 'picture_list',
            'pay_type', 'mode', 'type', 'create_date',
            'expect_amount', 'actual_amount', 'status', 'verify_status',
            'payer_id', 'payer_type', 'payer_name', 'payee_type',
            'payer_name', 'payee_id', 'operator_id', 'operator_type',
            'operator_name', 'create_timing', 'pay_timing'
        ],
        'merchantRecharge' => [
            'merchant_id',
            'pay_type',
            'expect_amount',
            'object_no',
            'object_type',
            'remark',
            'picture_list'
        ],

        'merchantDeduct' => [
            'payer_id',
            'payer_type',
            'pay_type',
            'expect_amount',
            'object_no',
            'object_type',
            'remark',
            'picture_list'
        ],
    ];

    public $message = [

    ];
}

