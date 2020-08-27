<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class RechargeValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'verify_recharge_amount' => 'required|numeric',
        'verify_remark' => 'nullable|string|max:250',
    ];

    public $scene = [
        'verify' => ['verify_recharge_amount', 'verify_remark']
    ];

    public $message = [
    ];
}
