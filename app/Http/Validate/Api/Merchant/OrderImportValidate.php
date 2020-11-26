<?php


namespace App\Http\Validate\Api\Merchant;


use App\Http\Validate\BaseValidate;

class OrderImportValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'out_order_no' => 'nullable|string|max:50|uniqueIgnore:order,id',
        'execution_date' => 'required|date|after_or_equal:today',
        'type' => 'required|integer|in:1,2',
        'settlement_type' => 'required|in:1,2',
        'settlement_amount' => 'nullable|required_if:settlement_type,2|numeric|gte:0',
        'replace_amount' => 'nullable|numeric|gte:0',
        'delivery' => 'nullable|integer|in:1,2',
        'place_fullname' => 'required|string|max:50',
        'place_phone' => 'required|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'place_country' => 'nullable|string|max:20',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'remark' => 'nullable|string|max:250',

        'item_type_1'=>'required|integer|in:1,2',
        'item_name_1'=>'nullable|string|max:50',
        'item_number_1'=>'required_with:item_type_1|string|max:50|regex:/^[0-9A-Za-z-]([0-9A-Za-z-])*[0-9A-Za-z-]$/',
        'item_count_1'=>'nullable|integer|gt:0',
        'item_weight_1'=>'	nullable|integer|gte:0',

        'item_type_2'=>'nullable|integer|in:1,2',
        'item_name_2'=>'nullable|string|max:50',
        'item_number_2'=>'required_with:item_type_2|string|max:50|regex:/^[0-9A-Za-z-]([0-9A-Za-z-])*[0-9A-Za-z-]$/',
        'item_count_2'=>'nullable|integer|gt:0',
        'item_weight_2'=>'	nullable|integer|gte:0',

        'item_type_3'=>'nullable|integer|in:1,2',
        'item_name_3'=>'nullable|string|max:50',
        'item_number_3'=>'required_with:item_type_3|string|max:50|regex:/^[0-9A-Za-z-]([0-9A-Za-z-])*[0-9A-Za-z-]$/',
        'item_count_3'=>'nullable|integer|gt:0',
        'item_weight_3'=>'	nullable|integer|gte:0',

        'item_type_4'=>'nullable|integer|in:1,2',
        'item_name_4'=>'nullable|string|max:50',
        'item_number_4'=>'required_with:item_type_4|string|max:50|regex:/^[0-9A-Za-z-]([0-9A-Za-z-])*[0-9A-Za-z-]$/',
        'item_count_4'=>'nullable|integer|gt:0',
        'item_weight_4'=>'	nullable|integer|gte:0',

        'item_type_5'=>'nullable|integer|in:1,2',
        'item_name_5'=>'nullable|string|max:50',
        'item_number_5'=>'required_with:item_type_5|string|max:50|regex:/^[0-9A-Za-z-]([0-9A-Za-z-])*[0-9A-Za-z-]$/',
        'item_count_5'=>'nullable|integer|gt:0',
        'item_weight_5'=>'	nullable|integer|gte:0',
    ];

    public $message = [
        'settlement_amount.required_if' => '当结算方式为到付时,:attribute字段必填',
        'required_unless'=>':attribute必填'
    ];
}
