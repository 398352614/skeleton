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
    'check_id_list' => 'Please select at least one order',
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
    'check_special_char' => 'The :attribute has special character',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute.rule' to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'new_password' => [
            'different' => 'The new password cannot be the same as the original password.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as 'E-Mail Address' instead
    | of 'email'. This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'time' => 'time',
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
        //第一地点地址信息
        'place_fullname' => 'place fullname',
        'place_phone' => 'place phone',
        'place_country' => 'place country',
        'place_post_code' => 'place post code',
        'place_house_number' => 'place house number',
        'place_city' => 'place city',
        'place_street' => 'place street',
        'place_address' => 'place address',
        'place_lon' => 'place lon',
        'place_lat' => 'place lat',
        //第二地点地址信息
        'second_place_fullname' => 'second place fullname',
        'second_place_phone' => 'second place phone',
        'second_place_country' => 'second place country',
        'second_place_post_code' => 'second place post code',
        'second_place_house_number' => 'second place house number',
        'second_place_city' => 'second place city',
        'second_place_street' => 'second place street',
        'second_place_address' => 'second place address',
        'second_place_lon' => 'second place lon',
        'second_place_lat' => 'second place lat',
        //仓库地址信息
        'warehouse_fullname' => 'warehouse fullname',
        'warehouse_phone' => 'warehouse phone',
        'warehouse_country' => 'warehouse country',
        'warehouse_post_code' => 'warehouse post code',
        'warehouse_house_number' => 'warehouse house number',
        'warehouse_city' => 'warehouse city',
        'warehouse_street' => 'warehouse street',
        'warehouse_address' => 'warehouse address',
        'warehouse_lon' => 'warehouse lon',
        'warehouse_lat' => 'warehouse lat',
        'house_number' => 'house number',
        'deal_remark' => 'deal remark',
        'car_no' => 'car no',
        'outgoing_time' => 'outgoing time',
        'car_brand_id' => 'car brand id',
        'car_model_id' => 'car model id',
        'ownership_type' => 'ownership type',
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
        'fullname' => 'full name',
        'gender' => 'gender',
        'birthday' => 'birthday',
        'duty_paragraph' => 'duty paragraph',
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
        'username' => 'username',
        'group_id' => 'group id',
        'institution_id' => 'institution id',
        'parent_id' => 'parent id',
        'warehouse_id' => 'warehouse id',
        'pickup_max_count' => 'pickup max count',
        'pie_max_count' => 'pie max count',
        'is_increment' => 'is increment',
        'order_deadline' => 'order deadline',
        'appointment_days' => 'appointment days',
        'work_day_list' => 'work day list',
        'execution_date' => 'execution date',
        'out_order_no' => 'out order no',
        'express_first_no' => 'express first no',
        'express_second_no' => 'express second no',
        'source' => 'source',
        'list_mode' => 'list mode',
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
        'coordinate_list' => 'coordinate list',
        'package_no' => 'package no',
        'recharge_no' => 'recharge no',
        'transaction_number' => 'transaction number',
        'out_user_name' => 'out_user name',
        'out_user_phone' => 'out_user phone',
        'recharge_date' => 'recharge date',
        'recharge_time' => 'recharge time',
        'driver_name' => 'driver_name',
        'recharge_amount' => 'recharge amount',
        'recharge_first_pic' => 'recharge first_pic',
        'recharge_second_pic' => 'recharge second_pic',
        'recharge_third_pic' => 'recharge third_pic',
        'driver_verify_status' => 'driver verify_status',
        'verify_status' => 'verify status',
        'verify_recharge_amount' => 'verify recharge_amount',
        'verify_date' => 'verify date',
        'verify_time' => 'verify time',
        'verify_remark' => 'verify remark',
        'id' => 'id',
        'company_id' => 'company id',
        'merchant_id' => 'merchant id',
        'batch_no' => 'batch no',
        'created_at' => 'created at',
        'updated_at' => 'updated at',
        'template' => 'template',
        'date' => 'date',
        'directions_times' => 'directions times',
        'actual_directions_times' => 'actual directions times',
        'api_directions_times' => 'api directions times',
        'distance_times' => 'distance times',
        'actual_distance_times' => 'actual distance times',
        'api_distance_times' => 'api distance times',
        'tour_no' => 'tour no',
        'line_id' => 'line id',
        'line_name' => 'line name',
        'exception_label' => 'exception label',
        'driver_phone' => 'driver phone',
        'driver_rest_time' => 'driver rest time',
        'sort_id' => 'sort id',
        'is_skipped' => 'is skipped',
        'expect_pickup_quantity' => 'expect pickup quantity',
        'actual_pickup_quantity' => 'actual pickup quantity',
        'expect_pie_quantity' => 'expect pie quantity',
        'actual_pie_quantity' => 'actual pie quantity',
        'expect_arrive_time' => 'expect arrive time',
        'actual_arrive_time' => 'actual arrive time',
        'expect_distance' => 'expect distance',
        'actual_distance' => 'actual distance',
        'expect_time' => 'expect time',
        'actual_time' => 'actual time',
        'sticker_amount' => 'sticker amount',
        'delivery_amount' => 'delivery amount',
        'actual_replace_amount' => 'actual replace amount',
        'actual_settlement_amount' => 'actual settlement amount',
        'auth_fullname' => 'auth fullname',
        'auth_birth_date' => 'auth birth date',
        'batch_exception_no' => 'batch exception no',
        'deal_id' => 'deal id',
        'deal_name' => 'deal name',
        'deal_time' => 'deal time',
        'amount' => 'amount',
        'attached_document' => 'attached document',
        'company_code' => 'company code',
        'line_rule' => 'line rule',
        'show_type' => 'show type',
        'address_template_id' => 'address template id',
        'weight_unit' => 'weight unit',
        'currency_unit' => 'currency unit',
        'volume_unit' => 'volume unit',
        'map' => 'map',
        'short' => 'short',
        'tel' => 'tel',
        'messager' => 'messager',
        'encrypt' => 'encrypt',
        'workday' => 'workday',
        'business_range' => 'business range',
        'auth_group_id' => 'auth group id',
        'forbid_login' => 'forbid login',
        'connection' => 'connection',
        'queue' => 'queue',
        'payload' => 'payload',
        'exception' => 'exception',
        'failed_at' => 'failed at',
        'level' => 'level',
        'holiday_id' => 'holiday id',
        'parent' => 'parent',
        'ancestor' => 'ancestor',
        'descendant' => 'descendant',
        'distance' => 'distance',
        'attempts' => 'attempts',
        'reserved_at' => 'reserved at',
        'available_at' => 'available at',
        'start' => 'start',
        'end' => 'end',
        'price' => 'price',
        'rule' => 'rule',
        'can_skip_batch' => 'can skip batch',
        'creator_id' => 'creator id',
        'creator_name' => 'creator name',
        'schedule' => 'schedule',
        'user' => 'user',
        'operation' => 'operation',
        'post_code_start' => 'post code start',
        'post_code_end' => 'post code end',
        'order_no' => 'order no',
        'expect_quantity' => 'expect quantity',
        'actual_quantity' => 'actual quantity',
        'additional_status' => 'additional status',
        'advance_days' => 'advance days',
        'delay_time' => 'delay time',
        'key' => 'key',
        'secret' => 'secret',
        'recharge_status' => 'recharge status',
        'fee_code' => 'fee code',
        'count' => 'count',
        'is_alone' => 'is alone',
        'migration' => 'migration',
        'batch' => 'batch',
        'mask_code' => 'mask code',
        'unique_code' => 'unique code',
        'sticker_no' => 'sticker no',
        'out_status' => 'out status',
        'log' => 'log',
        'success_order' => 'success order',
        'fail_order' => 'fail order',
        'total_order' => 'total order',
        'quantity' => 'quantity',
        'volume' => 'volume',
        'prefix' => 'prefix',
        'start_index' => 'start index',
        'int_length' => 'int length',
        'start_string_index' => 'start string index',
        'string_length' => 'string length',
        'max_no' => 'max no',
        'feature_logo' => 'feature logo',
        'is_auth' => 'is auth',
        'recharge_statistics_id' => 'recharge statistics id',
        'total_recharge_amount' => 'total recharge amount',
        'recharge_count' => 'recharge count',
        'verify_name' => 'verify name',
        'tour_driver_event_id' => 'tour driver event id',
        'stop_time' => 'stop time',
        'source_name' => 'source name',
        'sequence' => 'sequence',
        'uuid' => 'uuid',
        'family_hash' => 'family hash',
        'should_display_on_index' => 'should display on index',
        'entry_uuid' => 'entry uuid',
        'tag' => 'tag',
        'driver_avt_id' => 'driver avt id',
        'warehouse_expect_time' => 'warehouse expect time',
        'warehouse_expect_distance' => 'warehouse expect distance',
        'warehouse_expect_arrive_time' => 'warehouse expect arrive time',
        'begin_time' => 'begin time',
        'begin_distance' => 'begin distance',
        'end_time' => 'end time',
        'end_distance' => 'end distance',
        'actual_out_status' => 'actual out status',
        'lave_distance' => 'lave distance',
        'icon_id' => 'icon id',
        'icon_path' => 'icon path',
        'route_tracking_id' => 'route tracking id',
        'action' => 'action',
        'finish_quantity' => 'finish quantity',
        'surplus_quantity' => 'surplus quantity',
        'uploader_email' => 'uploader email',
        'version' => 'version',
        'change_log' => 'change log',
        'to_id' => 'to id',
        'data' => 'data',
        'company_auth' => 'company auth',
        'sign_time' => 'sign time',
        'out_expect_arrive_time' => 'out expect arrive time',
        'out_expect_distance' => 'out expect distance',
        'out_expect_time' => 'out expect time',
        'relate_material_list' => 'relate material list',
        "number" => "number",
        "mode" => "mode",
        "delay_type" => "delay type",
        "delay_remark" => "delay remark",
        "second_execution_date" => "second execution date",
        "tracking_order_no" => "tracking order no",
    ]
];