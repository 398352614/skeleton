<?php


namespace App\Http\Validate\Api\Merchant;


use App\Http\Validate\BaseValidate;

class OrderImportValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        "type" => 'required|integer|in:1,2,3',
        "out_user_id" => 'nullable|string',
        'out_order_no' => 'nullable|string|max:50|uniqueIgnore:order,id',
        'place_fullname' => 'required_unless:type,2|string|max:50',
        'place_phone' => 'required_unless:type,2|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'place_country' => 'nullable|string|max:20',
        'place_post_code' => 'required_unless:type,2|string|max:50',
        'place_house_number' => 'required_unless:type,2|max:50',
        "place_city" => 'nullable|string|max:50',
        "place_street" => 'nullable|string|max:50',
        'execution_date' => 'required_unless:type,2|date|after_or_equal:today',

        'second_place_fullname' => 'required_unless:type,1|string|max:50',
        'second_place_phone' => 'required_unless:type,1|string|max:20|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'second_place_country' => 'nullable|string|max:20',
        'second_place_post_code' => 'required_unless:type,1|string|max:50',
        'second_place_house_number' => 'required_unless:type,1|max:50',
        "second_place_city" => 'nullable|string|max:50',
        "second_place_street" => 'nullable|string|max:50',
        'second_execution_date' => 'required_unless:type,1|date|after_or_equal:today',

        "amount_1" => 'nullable|numeric|gte:0',
        "amount_2" => 'nullable|numeric|gte:0',
        "amount_3" => 'nullable|numeric|gte:0',
        "amount_4" => 'nullable|numeric|gte:0',
        "amount_5" => 'nullable|numeric|gte:0',
        "amount_6" => 'nullable|numeric|gte:0',
        "amount_7" => 'nullable|numeric|gte:0',
        "amount_8" => 'nullable|numeric|gte:0',
        "amount_9" => 'nullable|numeric|gte:0',
        "amount_10" => 'nullable|numeric|gte:0',
        "amount_11" => 'nullable|numeric|gte:0',
        "settlement_amount" => 'nullable|numeric|gte:0',
        "settlement_type" => 'nullable|integer|in:1,2,3,4,5',
        "control_mode" => 'nullable|integer|in:1,2',
        "receipt_type" => 'nullable|integer|in:1',
        "receipt_count" => 'nullable|integer|gte:0',
        "special_remark" => 'nullable|string|max:250',
        "mask_code" => 'nullable|string',

        "package_no_1" => 'nullable|string|max:50',
        "package_name_1" => 'nullable|string|max:50',
        "package_weight_1" => 'nullable|numeric|gte:0',
        "package_feature_1" => 'nullable|integer|in:1,2,3,4',
        "package_remark_1" => 'nullable|string',
        "package_expiration_date_1" => 'nullable|date',
        "package_out_order_no_1" => 'nullable|string|max:50',

        "package_no_2" => 'nullable|string|max:50',
        "package_name_2" => 'nullable|string|max:50',
        "package_weight_2" => 'nullable|numeric|gte:0',
        "package_feature_2" => 'nullable|integer|in:1,2,3,4',
        "package_remark_2" => 'nullable|string',
        "package_expiration_date_2" => 'nullable|date',
        "package_out_order_no_2" => 'nullable|string|max:50',

        "package_no_3" => 'nullable|string|max:50',
        "package_name_3" => 'nullable|string|max:50',
        "package_weight_3" => 'nullable|numeric|gte:0',
        "package_feature_3" => 'nullable|integer|in:1,2,3,4',
        "package_remark_3" => 'nullable|string',
        "package_expiration_date_3" => 'nullable|date',
        "package_out_order_no_3" => 'nullable|string|max:50',

        "package_no_4" => 'nullable|string|max:50',
        "package_name_4" => 'nullable|string|max:50',
        "package_weight_4" => 'nullable|numeric|gte:0',
        "package_feature_4" => 'nullable|integer|in:1,2,3,4',
        "package_remark_4" => 'nullable|string',
        "package_expiration_date_4" => 'nullable|date',
        "package_out_order_no_4" => 'nullable|string|max:50',

        "package_no_5" => 'nullable|string|max:50',
        "package_name_5" => 'nullable|string|max:50',
        "package_weight_5" => 'nullable|numeric|gte:0',
        "package_feature_5" => 'nullable|integer|in:1,2,3,4',
        "package_remark_5" => 'nullable|string',
        "package_expiration_date_5" => 'nullable|date',
        "package_out_order_no_5" => 'nullable|string|max:50',

        "material_code_1" => 'nullable|string|max:50',
        "material_name_1" => 'nullable|string|max:50',
        "material_count_1" => 'nullable|integer|gt:0',
        "material_weight_1" => 'nullable|numeric|gte:0',
        "material_size_1" => 'nullable|numeric|gte:0',
        "material_type_1" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10',
        "material_pack_type_1" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
        "material_price_1" => 'nullable|numeric|gte:0',
        "material_remark_1" => 'nullable|string',
        "material_out_order_no_1" => 'nullable|string|max:50',

        "material_code_2" => 'nullable|string|max:50',
        "material_name_2" => 'nullable|string|max:50',
        "material_count_2" => 'nullable|integer|gt:0',
        "material_weight_2" => 'nullable|numeric|gte:0',
        "material_size_2" => 'nullable|numeric|gte:0',
        "material_type_2" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10',
        "material_pack_type_2" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
        "material_price_2" => 'nullable|numeric|gte:0',
        "material_remark_2" => 'nullable|string',
        "material_out_order_no_2" => 'nullable|string|max:50',

        "material_code_3" => 'nullable|string|max:50',
        "material_name_3" => 'nullable|string|max:50',
        "material_count_3" => 'nullable|integer|gt:0',
        "material_weight_3" => 'nullable|numeric|gte:0',
        "material_size_3" => 'nullable|numeric|gte:0',
        "material_type_3" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10',
        "material_pack_type_3" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
        "material_price_3" => 'nullable|numeric|gte:0',
        "material_remark_3" => 'nullable|string',
        "material_out_order_no_3" => 'nullable|string|max:50',

        "material_code_4" => 'nullable|string|max:50',
        "material_name_4" => 'nullable|string|max:50',
        "material_count_4" => 'nullable|integer|gt:0',
        "material_weight_4" => 'nullable|numeric|gte:0',
        "material_size_4" => 'nullable|numeric|gte:0',
        "material_type_4" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10',
        "material_pack_type_4" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
        "material_price_4" => 'nullable|numeric|gte:0',
        "material_remark_4" => 'nullable|string',
        "material_out_order_no_4" => 'nullable|string|max:50',

        "material_code_5" => 'nullable|string|max:50',
        "material_name_5" => 'nullable|string|max:50',
        "material_count_5" => 'nullable|integer|gt:0',
        "material_weight_5" => 'nullable|numeric|gte:0',
        "material_size_5" => 'nullable|numeric|gte:0',
        "material_type_5" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10',
        "material_pack_type_5" => 'nullable|integer|in:1,2,3,4,5,6,7,8,9,10,11,12',
        "material_price_5" => 'nullable|numeric|gte:0',
        "material_remark_5" => 'nullable|string',
        "material_out_order_no_5" => 'nullable|string|max:50',

//        'file' => 'required|file|mimes:txt,xls,xlsx',
    ];

    public $message = [
        'place_fullname.required_unless' => '姓名 是必须的',
        'place_post_code.required_unless' => '邮编 是必须的',
        'place_phone.required_unless' => '电话 是必须的',
        'place_house_number.required_unless' => '门牌号 是必须的',
        'execution_date.required_unless' => '取件日期是必须的',
        'second_execution_date.required_unless' => '派件日期是必须的',
    ];

    public $scene = [
        'import' => 'file',
    ];
}
