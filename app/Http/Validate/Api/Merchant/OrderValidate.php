<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class OrderValidate extends BaseValidate
{
    public $customAttributes = [
        'batch_no' => '站点编号',
        'execution_date' => '取派日期',
        'out_order_no' => '外部订单号',
        'express_first_no' => '快递单号1',
        'express_second_no' => '快递单号2',
        'source' => '来源',
        'type' => '类型',
        'out_user_id' => '外部客户ID',
        'nature' => '性质',
        'settlement_type' => '结算方式',
        'settlement_amount' => '结算金额',
        'replace_amount' => '代收款',
        'delivery' => '自提',
        'sender' => '发件人',
        'sender_phone' => '发件人电话',
        'sender_country' => '发件人国家',
        'sender_post_code' => '发件人邮编',
        'sender_house_number' => '发件人门牌号',
        'sender_city' => '发件人城市',
        'sender_street' => '发件人街道',
        'sender_address' => '发件人详细地址',
        'receiver' => '收件人',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_city' => '收件人城市',
        'receiver_street' => '收件人街道',
        'receiver_address' => '收件人详细地址',
        'special_remark' => '特殊事项',
        'remark' => '其余备注',
        'lon' => '经度',
        'lat' => '纬度'
    ];

    public $itemCustomAttributes = [
        'name' => '货物名称',
        'quantity' => '货物数量',
        'weight' => '货物重量',
        'volume' => '货物体积',
        'price' => '货物单价',
    ];

    public $rules = [
        'batch_no' => 'nullable|string|max:50',
        'execution_date' => 'required|date|after_or_equal:today',
        'out_order_no' => 'required|string|max:50|uniqueIgnore:order,id',
        'express_first_no' => 'required|string|max:50|uniqueIgnore:order,id',
        'express_second_no' => 'nullable|string|max:50|uniqueIgnore:order,id',
        'source' => 'required|string|max:50',
        'type' => 'nullable|integer|in:1,2',
        'out_user_id' => 'nullable|integer',
        'nature' => 'nullable|integer|in:1,2,3,4,5',
        'settlement_type' => 'required|in:1,2',
        'settlement_amount' => 'nullable|required_if:settlement_type,2|numeric',
        'replace_amount' => 'nullable|numeric',
        'delivery' => 'nullable|integer|in:1,2',
        'sender' => 'required|string|max:50',
        'sender_phone' => 'required|string|max:20',
        'sender_country' => 'required|string|max:20',
        'sender_post_code' => 'required|string|max:50',
        'sender_house_number' => 'required|string|max:50',
        'sender_city' => 'required|string|max:50',
        'sender_street' => 'required|string|max:50',
        'sender_address' => 'nullable|string|max:250',
        'receiver' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20',
        'receiver_country' => 'required|string|max:20',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'required|string|max:50',
        'receiver_city' => 'required|string|max:50',
        'receiver_street' => 'required|string|max:50',
        'receiver_address' => 'nullable|string|max:250',
        'lon' => 'required|string|max:50',
        'lat' => 'required|string|max:50',
        'special_remark' => 'nullable|string|max:250',
        'remark' => 'nullable|string|max:250'
    ];

    public $item_rules = [
        'name' => 'required|string|max:50',
        'quantity' => 'required|integer|between:1,10',
        'weight' => 'required|numeric',
        'volume' => 'required|numeric',
        'price' => 'required|numeric',
    ];

    public $scene = [

        'getLocation' => [
            'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street'
        ],
        'store' => [
            //'out_order_no', 'express_first_no', 'express_second_no',
            'execution_date', 'source',
            'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            'sender', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number',
            'sender_city', 'sender_street', 'sender_address',
            //收货人信息
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address',
            //备注
            'special_remark', 'remark', 'lon', 'lat',
            //明细
            //'item_list' => ['name', 'quantity', 'weight', 'volume', 'price']
        ],
        'update' => [
            // 'out_order_no', 'express_first_no', 'express_second_no',
            'execution_date', 'source',
            'type', 'out_user_id', 'nature', 'settlement_type', 'settlement_amount', 'replace_amount', 'delivery',
            //发货人信息
            'sender', 'sender_phone', 'sender_country', 'sender_post_code', 'sender_house_number',
            'sender_city', 'sender_street', 'sender_address',
            //收货人信息
            'receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number',
            'receiver_city', 'receiver_street', 'receiver_address',
            //备注
            'special_remark', 'remark', 'lon', 'lat',
            //明细
            //'item_list' => ['name', 'quantity', 'weight', 'volume', 'price']
        ],
        'getBatchPageListByOrder' => ['execution_date'],
        'assignToBatch' => ['execution_date', 'batch_no'],
        'recovery' => ['execution_date'],
    ];
    public $message = [
        'settlement_amount.required_if' => '当结算方式为到付时,:attribute字段必填',
    ];
}

