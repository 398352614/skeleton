<?php


namespace App\Http\Validate\Api\Merchant;


use App\Http\Validate\BaseValidate;

class OrderImportValidate extends BaseValidate
{
    public $customAttributes = [
        'type' => '类型',
        'receiver' => '收件人',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_address' => '收件人详细地址',
        'execution_date' => '取派日期',
        'out_order_no' => '外部订单号',
        'settlement_type' => '结算方式',
        'settlement_amount' => '结算金额',
        'replace_amount' => '代收款',
        'delivery' => '自提',
        'remark' => '其余备注',

        'item_type_1'=>'物品一类型',
        'item_name_1'=>'物品一名称',
        'item_number_1'=>'物品一编号',
        'item_count_1'=>'物品一数量',
        'item_weight_1'=>'物品一重量',

        'item_type_2'=>'物品二类型',
        'item_name_2'=>'物品二名称',
        'item_number_2'=>'物品二编号',
        'item_count_2'=>'物品二数量',
        'item_weight_2'=>'物品二重量',

        'item_type_3'=>'物品三类型',
        'item_name_3'=>'物品三名称',
        'item_number_3'=>'物品三编号',
        'item_count_3'=>'物品三数量',
        'item_weight_3'=>'物品三重量',

        'item_type_4'=>'物品四类型',
        'item_name_4'=>'物品四名称',
        'item_number_4'=>'物品四编号',
        'item_count_4'=>'物品四数量',
        'item_weight_4'=>'物品四重量',

        'item_type_5'=>'物品五类型',
        'item_name_5'=>'物品五名称',
        'item_number_5'=>'物品五编号',
        'item_count_5'=>'物品五数量',
        'item_weight_5'=>'物品五重量',

    ];

    public $rules = [
        'out_order_no' => 'nullable|string|max:50|uniqueIgnore:order,id',
        'execution_date' => 'required|date|after_or_equal:today',
        'type' => 'required|integer|in:1,2',
        'settlement_type' => 'required|in:1,2',
        'settlement_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'replace_amount' => 'nullable|numeric|gte:0',
        'delivery' => 'nullable|integer|in:1,2',
        'receiver' => 'required|string|max:50',
        'receiver_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'receiver_country' => 'required|string|max:20',
        'receiver_post_code' => 'required|string|max:50',
        'receiver_house_number' => 'required|string|max:50',
        'receiver_address' => 'required|string|max:250',
        'remark' => 'nullable|string|max:250',

        'item_type_1'=>'required|integer|in:1,2',
        'item_name_1'=>'required|string|max:50',
        'item_number_1'=>'required_unless:item_type_1,0|string|max:50',
        'item_count_1'=>'nullable|integer|gte:0',
        'item_weight_1'=>'	nullable|integer|gte:0',

        'item_type_2'=>'required|integer|in:1,2,0',
        'item_name_2'=>'required_unless:item_type_2,0|string|max:50',
        'item_number_2'=>'required_unless:item_type_2,0|string|max:50',
        'item_count_2'=>'nullable|integer|gte:0',
        'item_weight_2'=>'	nullable|integer|gte:0',

        'item_type_3'=>'required|integer|in:1,2,0',
        'item_name_3'=>'required_unless:item_type_3,0|string|max:50',
        'item_number_3'=>'required_unless:item_type_3,0|string|max:50',
        'item_count_3'=>'nullable|integer|gte:0',
        'item_weight_3'=>'	nullable|integer|gte:0',

        'item_type_4'=>'required|integer|in:1,2,0',
        'item_name_4'=>'required_unless:item_type_4,0|string|max:50',
        'item_number_4'=>'required_unless:item_type_4,0|string|max:50',
        'item_count_4'=>'nullable|integer|gte:0',
        'item_weight_4'=>'	nullable|integer|gte:0',

        'item_type_5'=>'required|integer|in:1,2,0',
        'item_name_5'=>'required_unless:item_type_5,0|string|max:50',
        'item_number_5'=>'required_unless:item_type_5,0|string|max:50',
        'item_count_5'=>'nullable|integer|gte:0',
        'item_weight_5'=>'	nullable|integer|gte:0',
    ];

    public $message = [
        'settlement_amount.required_if' => '当结算方式为到付时,:attribute字段必填',
        'required_unless'=>':attribute必填'
    ];
}
