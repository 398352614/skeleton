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

    'accepted' => ':attribute必须接受',
    'active_url' => ':attribute必须是一个合法的 URL',
    'after' => ':attribute 必须是 :date 之后的一个日期',
    'after_or_equal' => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha' => ':attribute只能包含字母',
    'alpha_dash' => ':attribute只能包含字母、数字、中划线或下划线',
    'alpha_num' => ':attribute只能包含字母和数字',
    'array' => ':attribute必须是一个数组',
    'before' => ':attribute 必须是 :date 之前的一个日期',
    'before_or_equal' => ':attribute 必须是 :date 之前或相同的一个日期',
    'between' => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file' => ':attribute 必须在 :min 到 :max KB 之间',
        'string' => ':attribute 必须在 :min 到 :max 个字符之间',
        'array' => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean' => ':attribute字符必须是 true 或false, 1 或 0 ',
    'confirmed' => ':attribute 二次确认不匹配',
    'date' => ':attribute 必须是一个合法的日期',
    'date_format' => ':attribute 与给定的格式 :format 不符合',
    'different' => ':attribute 必须不同于 :other',
    'digits' => ':attribute必须是 :digits 位.',
    'digits_between' => ':attribute 必须在 :min 和 :max 位之间',
    'dimensions' => ':attribute具有无效的图片尺寸',
    'distinct' => ':attribute字段具有重复值',
    'email' => ':attribute必须是一个合法的电子邮件地址',
    'exists' => '选定的 :attribute 是无效的.',
    'file' => ':attribute必须是一个文件',
    'filled' => ':attribute的字段是必填的',
    'image' => ':attribute必须是 jpeg, png, bmp 或者 gif 格式的图片',
    'in' => '选定的 :attribute 是无效的',
    'in_array' => ':attribute 字段不存在于 :other',
    'integer' => ':attribute 必须是个整数',
    'ip' => ':attribute必须是一个合法的 IP 地址。',
    'json' => ':attribute必须是一个合法的 JSON 字符串',
    'max' => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file' => ':attribute 的最大为 :max',
        'string' => ':attribute 的最大长度为 :max 字符',
        'array' => ':attribute 的最大个数为 :max 个.',
    ],
    'mimes' => ':attribute 的文件类型必须是 :values',
    'min' => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'file' => ':attribute 大小至少为 :min KB',
        'string' => ':attribute 的最小长度为 :min 字符',
        'array' => ':attribute 至少有 :min 项',
    ],
    'not_in' => '选定的 :attribute 是无效的',
    'numeric' => ':attribute 必须是数字',
    'present' => ':attribute 字段必须存在',
    'regex' => ':attribute 格式是无效的',
    'required' => ':attribute 字段是必须的',
    'required_if' => ':attribute 字段是必须的当 :other 是 :value',
    'required_unless' => ':attribute 字段是必须的，除非 :other 是在 :values 中',
    'required_with' => ':attribute 字段是必须的当 :values 是存在的',
    'required_with_all' => ':attribute 字段是必须的当 :values 是存在的',
    'required_without' => ':attribute 字段是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
    'same' => ':attribute和:other必须匹配',
    'size' => [
        'numeric' => ':attribute 必须是 :size 位',
        'file' => ':attribute 必须是 :size KB',
        'string' => ':attribute 必须是 :size 个字符',
        'array' => ':attribute 必须包括 :size 项',
    ],
    'string' => ':attribute 必须是一个字符串',
    'timezone' => ':attribute 必须是个有效的时区.',
    'unique' => ':attribute 已存在',
    'url' => ':attribute 无效的格式',
    'unique_ignore' => ':attribute 已存在',

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
        'lon' => [
            'required' => '系统无法获取该地址的位置信息',
        ],
        'lat' => [
            'required' => '系统无法获取该地址的位置信息',
        ],
        //包裹列表
        'package_list.*.id' => [
            'required_with' => '包裹ID是必须的',
            'integer' => '包裹ID必须是整数'
        ],
        'package_list.*.name' => [
            'required_with' => '包裹名称是必须的',
            'max' => '包裹名称字段必须在 :max 个字符之内',
            'string' => '包裹名称必须是字符串'
        ],
        'package_list.*.weight' => [
            'required_with' => '包裹重量是必须的',
            'numeric' => '包裹重量必须是数字'
        ],
        'package_list.*.expect_quantity' => [
            'required_with' => '包裹数量是必须的',
            'integer' => '包裹数量必须是整数'
        ],
        'package_list.*.actual_quantity' => [
            'required_with' => '包裹数量是必须的',
            'integer' => '包裹数量必须是整数'
        ],
        'package_list.*.remark' => [
            'max' => '包裹名称字段必须在 :max 个字符之内',
            'string' => '包裹名称备注是字符串'
        ],
        'package_list.*.out_order_no' => [
            'max' => '包裹名称字段必须在 :max 个字符之内',
            'string' => '包裹外部标识必须是字符串'
        ],
        'package_list.*.express_first_no' => [
            'required_with' => '包裹快递单号1是必须的',
            'max' => "包裹快递单号1字段必须在 :max 个字符之内",
            'string' => '包裹名称必须是字符串'
        ],
        'package_list.*.express_second_no' => [
            'max' => '包裹名称字段必须在 :max 个字符之内',
            'string' => '包裹名称必须是字符串'
        ],
        'package_list.*.sticker_no' => [
            'max' => '包裹贴单号字段必须在 :max 个字符之内',
            'string' => '包裹贴单号必须是字符串'
        ],
        //材料列表
        'material_list.*.order_no' => [
            'required_with' => '材料所属订单号是必须的',
            'string' => '材料所属订单号必须是字符串',
            'max' => '材料外部标识字段必须在 :max 个字符之内',
        ],
        'material_list.*.name' => [
            'required_with' => '材料名称是必须的',
            'string' => '材料名称必须是字符串',
            'max' => '材料名称字段必须在 :max 个字符之内',
        ],
        'material_list.*.code' => [
            'required_with' => '材料代码是必须的',
            'string' => '材料代码必须是字符串',
            'max' => '材料代码字段必须在 :max 个字符之内',
        ],
        'material_list.*.out_order_no' => [
            'string' => '材料外部标识必须是字符串',
            'max' => '材料外部标识字段必须在 :max 个字符之内',
        ],
        'material_list.*.expect_quantity' => [
            'required_with' => '材料数量是必须的',
            'integer' => '材料数量必须是整数',
        ],
        'material_list.*.actual_quantity' => [
            'required_with' => '材料实际数量是必须的',
            'integer' => '材料实际数量必须是整数',
        ],
        'material_list.*.remark' => [
            'string' => '材料备注必须是字符串',
            'max' => '材料备注字段必须在 :max 个字符之内',
        ],
        //公里计费列表
        'km_list.*.start' => [
            'required_with' => '公里计费列表起始值是必须的',
            'integer' => '公里计费列表起始值必须是整数'
        ],
        'km_list.*.end' => [
            'required_with' => '公里计费列表截止值是必须的',
            'integer' => '公里计费列表截止值必须是整数',
            'gt' => '公里计费列表截止值必须大于起始值'
        ],
        'km_list.*.price' => [
            'required_with' => '公里计费列表价格是必须的',
            'integer' => '公里计费列表价格必须是数字'
        ],
        //重量计费列表
        'weight_list.*.start' => [
            'required_with' => '重量计费列表起始值是必须的',
            'integer' => '重量计费列表起始值必须是整数'
        ],
        'weight_list.*.end' => [
            'required_with' => '重量计费列表截止值是必须的',
            'integer' => '重量计费列表截止值必须是整数',
            'gt' => '重量计费列表截止值必须大于起始值'
        ],
        'weight_list.*.price' => [
            'required_with' => '重量计费列表价格是必须的',
            'integer' => '重量计费列表价格必须是数字'
        ],
        //特殊时段计费列表
        'special_time_list.*.start' => [
            'required_with' => '特殊时段计费列表起始时间是必须的',
            'date_format' => '特殊时段计费列表起始时间格式必须是H:i:s'
        ],
        'special_time_list.*.end' => [
            'required_with' => '特殊时段计费列表截止值是必须的',
            'date_format' => '特殊时段计费列表截止时间格式必须是H:i:s',
            'after' => '特殊时段计费列表截止时间必须大于起始时间'
        ],
        'special_time_list.*.price' => [
            'required_with' => '重量计费列表价格是必须的',
            'integer' => '重量计费列表价格必须是数字'
        ],
        //邮编列表
        'item_list.*.post_code_start' => [
            'required' => '邮编列表起始邮编是必须的',
            'integer' => '邮编列表起始邮编必须是整数',
            'between' => '邮编列表起始邮编范围必须的:min-:max之间'
        ],
        'item_list.*.post_code_end' => [
            'required' => '邮编列表截止邮编是必须的',
            'integer' => '邮编列表截止邮编必须是整数',
            'between' => '邮编列表截止邮编范围必须的:min-:max之间',
            'gt' => '邮编列表截止邮编必须大于起始邮编'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        //Common-attributes
        'country' => '所在国家',
        'cn_name' => '中文名称',
        'en_name' => '英文名称',
        'phone' => '手机号码',
        'post_code' => '邮编',
        'street' => '街道',
        'city' => '城市',
        'is_locked' => '是否锁定',
        'email' => '邮箱',
        'remark' => '备注',
        'password' => '密码',
        'name' => '名称',
        'contacts' => '负责人',
        'address' => '详细地址',
        'receiver' => '收件人姓名',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_city' => '收件人城市',
        'receiver_street' => '收件人街道',
        'receiver_address' => '收件人地址',
        'lon' => '经度',
        'lat' => '纬度',
        'sender' => '发件人姓名',
        'sender_phone' => '发件人电话',
        'sender_country' => '发件人国家',
        'sender_post_code' => '发件人邮编',
        'sender_house_number' => '发件人门牌号',
        'sender_city' => '发件人城市',
        'sender_street' => '发件人街道',
        'sender_address' => '发件人详细地址',
        'house_number' => '门牌号',
        //Admin-BatchException
        'deal_remark' => '处理内容',
        //Admin-Car
        'car_no' => '车牌号',
        'outgoing_time' => '出厂日期',
        'car_brand_id' => '车辆品牌ID',
        'car_model_id' => '汽车型号id',
        'frame_number' => '车架号',
        'engine_number' => '发动机编号',
        'transmission' => '车型',
        'fuel_type' => '燃料类型',
        'current_miles' => '当前里程数',
        'annual_inspection_data' => '下次年检日期',
        'ownership_type' => '类型',
        'received_date' => '接收车辆日期',
        'month_road_tax' => '每月路税',
        'insurance_company' => '保险公司',
        'insurance_type' => '保险类型',
        'month_insurance' => '每月保险',
        'rent_start_date' => '起租时间',
        'rent_end_date' => '到期时间',
        'rent_month_fee' => '月租金',
        'repair' => '维修自理',
        'relate_material' => '文件',
        'relate_material_name' => '相关文件名',
        'brand_id' => '品牌ID',
        //Admin-Common
        //Admin-Company
        //Admin-Country
        //Admin-Driver
        'last_name' => '姓',
        'first_name' => '名',
        'gender' => '性别',
        'birthday' => '生日',
        'duty_paragraph' => '税号',
        'door_no' => '门牌号',
        'lisence_number' => '驾照编号',
        'lisence_valid_date' => '有效期',
        'lisence_type' => '驾照类型',
        'lisence_material' => '驾照材料',
        'lisence_material_name' => '驾照材料名',
        'government_material' => '政府信件',
        'government_material_name' => '政府信件名',
        'avatar' => '头像',
        'bank_name' => '银行名称',
        'iban' => 'IBAN',
        'bic' => 'BIC',
        'crop_type' => '合作类型',
        //Admin-Employee
        'fullname' => '姓名',
        'username' => '用户名',
        'group_id' => '用户组',
        'institution_id' => '组织机构',
        //Admin-Institution
        'parent_id' => '父节点ID',
        //Admin-Line
        'warehouse_id' => '仓库ID',
        'order_max_count' => '最大订单量',
        'work_day_list' => '工作日',
        //Admin-Order
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
        'special_remark' => '特殊事项',
        //Admin-ReceiverAddress
        //Admin-Register
        'confirm_password' => '密码确认',
        'code' => '验证码',
        'new_password' => '新密码',
        'confirm_new_password' => '新密码确认',
        //Admin-SenderAddress
        //Admin-Tour
        'driver_id' => '司机ID',
        'car_id' => '车辆ID',
        //Admin-Upload
        'image' => '图片',
        'file' => '文件',
        'dir' => '目录',
        //Admin-WareHouse
        'contacter' => '联系人',

        //driver-auth
        'origin_password' => '原密码',
        'new_confirm_password' => '新密码确认',

        //driver-memorandum
        'content' => '内容',

        //driver-tourtask
        'batch_id' => '站点ID',
        'order_id' => '订单ID',

        //driver-tour
        'begin_signature' => '出库签名',
        'begin_signature_remark' => '出库备注',
        'begin_signature_first_pic' => '出库图片1',
        'begin_signature_second_pic' => '出库图片2',
        'begin_signature_third_pic' => '出库图片1',
        'stage' => '状态',
        'exception_remark' => '异常内容',
        'picture' => '图片',
        'cancel_type' => '取消取派类型',
        'cancel_remark' => '取消取派内容',
        'cancel_picture' => '取消取派图片',
        'cancel_order_id_list' => '取消订单ID列表',
        'signature' => '客户签名',
        'pay_type' => '支付方式',
        'pay_picture' => '支付图片',
        'end_signature' => '入库签名',
        'end_signature_remark' => '入库备注',

        'merchant_group_id' => '商户组ID',
        'status' => '状态',
        'transport_price_id' => '运价ID',
        'is_default' => '是否默认',
        'starting_price' => '起步价',
        'permission' => '权限',
        'package_list' => '包裹列表',
        'material_list' => '材料列表',
    ],
];
