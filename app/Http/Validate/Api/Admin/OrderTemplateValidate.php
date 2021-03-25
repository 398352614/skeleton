<?php
/**
 * 打印模板 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderTemplateValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'type' => 'required|integer|in:1,2',
        'destination_mode' => 'required|integer|in:1,2,3,4',
        'logo' => 'required|string',

        'sender' => 'required|string',
        'receiver' => 'required|string',
        'destination' => 'required|string',
        'carrier' => 'required|string',
        'carrier_address' => 'required|string',
        'contents' => 'required|string',
        'package' => 'required|string',
        'material' => 'required|string',
        'count' => 'required|string',
        'replace_amount' => 'required|string',
        'settlement_amount' => 'required|string',

    ];

    public $scene = [
        'update' => ['type', 'destination_mode', 'logo', 'sender', 'receiver', 'destination', 'carrier', 'carrier_address', 'contents', 'package', 'material', 'count', 'replace_amount', 'settlement_amount'],

        'type' => ['type'],
    ];
}

