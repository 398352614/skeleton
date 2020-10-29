<?php


namespace App\Http\Validate\Api\Driver;


use App\Http\Validate\BaseValidate;

class RechargeValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'merchant_id' => 'required|string',
        'out_user_id' => 'required|string',
        'out_user_name' => 'required|string',
        'recharge_amount' => 'required|numeric|gte:0.01|lte:2000',
        'recharge_no' => 'required|string|max:250',
        'recharge_first_pic' => 'nullable|string|max:250',
        'recharge_second_pic' => 'nullable|string|max:250',
        'recharge_third_pic' => 'nullable|string|max:250',
        'signature' => 'required|string|max:250',
        'remark' => 'nullable|string|max:250',
        'verify_phone_end' => 'nullable|string|max:250',
    ];

    public $scene = [
        'getOutUser' => ['merchant_id', 'out_user_name'],
        'recharge' => ['recharge_no','merchant_id', 'out_user_id', 'recharge_amount', 'recharge_first_pic', 'recharge_second_pic', 'recharge_third_pic', 'signature', 'remark'],
        'verify' => ['recharge_no', 'verify_phone_end', 'merchant_id'],
    ];

    public $message = [
    ];
}
