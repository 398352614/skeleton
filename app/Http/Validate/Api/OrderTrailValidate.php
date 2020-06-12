<?php


namespace App\Http\Validate\Api;


use App\Http\Validate\BaseValidate;

class OrderTrailValidate extends BaseValidate
{
    public $customAttributes = [
        'order_no' => '订单编号',
        'content' => '内容',
        'create_at' => '创建时间',
    ];


    public $rules = [
        'order_no' => 'required|string',
        'content' => 'required|string',
        'create_at' => 'required|string|between:8,20|different:origin_password',
    ];

    public $scene = [
        'store' => ['order_no', 'content','create_at'],
    ];

    public $message = [
    ];
}
