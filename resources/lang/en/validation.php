<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'unique_ignore' => 'The :attribute field must be unique.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'country' => 'country',
        'cn_name' => 'cn name',
        'en_name' => 'en name',
        'phone' => 'phone',
        'post_code' => 'post code',
        'street' => 'street',
        'city' => 'city',
        'is_locked' => 'is locked',
        'email' => 'email',
        'remark' => 'remark',
        'password' => 'password',
        'name' => 'name',
        'contacts' => 'contacts',
        'address' => 'address',
        'receiver' => 'receiver',
        'receiver_phone' => 'receiver phone',
        'receiver_country' => 'receiver country',
        'receiver_post_code' => 'receiver post code',
        'receiver_house_number' => 'receiver house number',
        'receiver_city' => 'receiver city',
        'receiver_street' => 'receiver street',
        'receiver_address' => 'receiver address',
        'lon' => 'lon',
        'lat' => 'lat',
        'sender' => 'sender',
        'sender_phone' => 'sender phone',
        'sender_country' => 'sender country',
        'sender_post_code' => 'sender post code',
        'sender_house_number' => 'sender house number',
        'sender_city' => 'sender city',
        'sender_street' => 'sender street',
        'sender_address' => 'sender address',
        'house_number' => 'house number',
        'deal_remark' => 'deal remark',
        'car_no' => 'car no',
        'outgoing_time' => 'outgoing time',
        'car_brand_id' => 'car brand id',
        'car_model_id' => 'car model id',
        'frame_number' => 'frame number',
        'engine_number' => 'engine number',
        'transmission' => 'transmission',
        'fuel_type' => 'fuel type',
        'current_miles' => 'current miles',
        'annual_inspection_data' => 'annual inspection data',
        'ownership_type' => 'ownership type',
        'received_date' => 'received date',
        'month_road_tax' => 'month road tax',
        'insurance_company' => 'insurance company',
        'insurance_type' => 'insurance type',
        'month_insurance' => 'month insurance',
        'rent_start_date' => 'rent start date',
        'rent_end_date' => 'rent end date',
        'rent_month_fee' => 'rent month fee',
        'repair' => 'repair',
        'relate_material' => 'relate material',
        'relate_material_name' => 'relate material name',
        'brand_id' => 'brand id',
        'last_name' => 'last name',
        'first_name' => 'first name',
        'gender' => 'gender',
        'birthday' => 'birthday',
        'duty_paragraph' => 'duty paragraph',
        'door_no' => 'door no',
        'lisence_number' => 'lisence number',
        'lisence_valid_date' => 'lisence valid date',
        'lisence_type' => 'lisence type',
        'lisence_material' => 'lisence material',
        'lisence_material_name' => 'lisence material name',
        'government_material' => 'government material',
        'government_material_name' => 'government material name',
        'avatar' => 'avatar',
        'bank_name' => 'bank name',
        'iban' => 'iban',
        'bic' => 'bic',
        'crop_type' => 'crop type',
        'fullname' => 'fullname',
        'username' => 'username',
        'group_id' => 'group id',
        'institution_id' => 'institution id',
        'parent_id' => 'parent id',
        'warehouse_id' => 'warehouse id',
        'order_max_count' => 'order max count',
        'work_day_list' => 'work day list',
        'execution_date' => 'execution date',
        'out_order_no' => 'out order no',
        'express_first_no' => 'express first no',
        'express_second_no' => 'express second no',
        'source' => 'source',
        'type' => 'type',
        'out_user_id' => 'out user id',
        'nature' => 'nature',
        'settlement_type' => 'settlement type',
        'settlement_amount' => 'settlement amount',
        'replace_amount' => 'replace amount',
        'delivery' => 'delivery',
        'special_remark' => 'special remark',
        'confirm_password' => 'confirm password',
        'code' => 'code',
        'new_password' => 'new password',
        'confirm_new_password' => 'confirm new password',
        'driver_id' => 'driver id',
        'car_id' => 'car id',
        'image' => 'image',
        'file' => 'file',
        'dir' => 'dir',
        'contacter' => 'contacter',
        'origin_password' => 'origin password',
        'new_confirm_password' => 'new confirm password',
        'content' => 'content',
        'batch_id' => 'batch id',
        'order_id' => 'order id',
        'begin_signature' => 'begin signature',
        'begin_signature_remark' => 'begin signature remark',
        'begin_signature_first_pic' => 'begin signature first pic',
        'begin_signature_second_pic' => 'begin signature second pic',
        'begin_signature_third_pic' => 'begin signature third pic',
        'stage' => 'stage',
        'exception_remark' => 'exception remark',
        'picture' => 'picture',
        'cancel_type' => 'cancel type',
        'cancel_remark' => 'cancel remark',
        'cancel_picture' => 'cancel picture',
        'cancel_order_id_list' => 'cancel order id list',
        'signature' => 'signature',
        'pay_type' => 'pay type',
        'pay_picture' => 'pay picture',
        'end_signature' => 'end signature',
        'end_signature_remark' => 'end signature remark',
        'merchant_group_id' => 'merchant group id',
        'status' => 'status',
        'transport_price_id' => 'transport price id',
        'is_default' => 'is default',
        'starting_price' => 'starting price',
        'permission' => 'permission',
        'package_list' => 'package list',
        'material_list' => 'material list',
        'km' => 'km',
        'weight' => 'weight',
        'special_time' => 'special time',
        'url' => 'url',
        'white_ip_list' => 'white IP list',
    ],
];
