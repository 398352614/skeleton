<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class OrderValidate extends BaseValidate
{
    public $customAttributes = [
        'execution_date' => '取派日期',
        'item_list' => '货物列表',
    ];

    public $rules = [
        'execution_date' => 'required|date',
//        'out_order_no' => 'required|string|max:50|uniqueIgnore:order,id',
//        'express_first_no' => 'required|string|max:50|uniqueIgnore:order,id',
//        'express_second_no' => 'required|string|max:50|uniqueIgnore:order,id',
//        'source' => 'required|string|max:50',
//        'type' => 'required|integer|in:1,2',
//        'out_user_id' => 'required|integer',
//        'nature' => 'required|integer|in:1,2,3,4',
//        'settlement_type' => 'required|in:1,2',
//        'settlement_amount' => 'required_if:settlement_type,1|numeric',
//        'replace_amount' => 'numeric',
//        'delivery' => 'required|integer|in:1,2',
//        'sender' => 'required|string|max:50',
//        'sender_phone' => 'required|string|max:20',
//        'sender_country' => 'required|string|max:20',
//        'sender_post_code' => 'required|string|max:50',
//        'sender_house_number' => 'required|string|max:50',
//        'sender_city' => 'required|string|max:50',
//        'sender_street' => 'required|string|max:50',
//        'sender_address' => 'required|string|max:250',
//        'receiver' => 'required|string|max:50',
//        'receiver_phone' => 'required|string|max:20',
//        'receiver_country' => 'required|string|max:20',
//        'receiver_post_code' => 'required|string|max:50',
//        'receiver_house_number' => 'required|string|max:50',
//        'receiver_city' => 'required|string|max:50',
//        'receiver_street' => 'required|string|max:50',
//        'receiver_address' => 'required|string|max:250',
//        'special_remark' => 'string|max:250',
//        'remark' => 'string|max:250',
        'item_list' => 'required|json',
    ];
    public $scene = [
        'store' => ['execution_date', 'item_list'],
    ];
}

