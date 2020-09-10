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
    'check_id_list' => '请输入至少一个结果',
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
    'gte.numeric' => ':attribute不能小于:value',
    'lte.numeric' => ':attribute不能超过:value',
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
    'today' => '今天',
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
            'string' => '包裹名称必须是字符串',
            'regex' => '快递单号是无效的'
        ],
        'package_list.*.express_second_no' => [
            'max' => '包裹名称字段必须在 :max 个字符之内',
            'string' => '包裹名称必须是字符串'
        ],
        'package_list.*.sticker_no' => [
            'max' => '包裹贴单号字段必须在 :max 个字符之内',
            'string' => '包裹贴单号必须是字符串'
        ],
        'additional_package_list.*.package_no' => [
            'required_with' => '顺带包裹编号是必须的',
            'max' => '顺带包裹单号字段必须在 :max 个字符之内',
            'string' => '顺带包裹单号必须是字符串'
        ],
        'additional_package_list.*.merchant_id' => [
            'required_with' => '商户ID是必须的',
            'max' => '商户ID字段必须在 :max 个字符之内',
            'string' => '商户ID必须是字符串'
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
        'new_password' => [
            'different' => '新密码不能和旧密码一样',
        ],
        'receiver_house_number' => [
            'required_if' => '当国家是荷兰时，门牌号必填',
        ]
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
        'receiver_fullname' => '收件人姓名',
        'receiver_phone' => '收件人电话',
        'receiver_country' => '收件人国家',
        'receiver_post_code' => '收件人邮编',
        'receiver_house_number' => '收件人门牌号',
        'receiver_city' => '收件人城市',
        'receiver_street' => '收件人街道',
        'receiver_address' => '收件人地址',
        'lon' => '经度',
        'lat' => '纬度',
        'sender_fullname' => '发件人姓名',
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
        'ownership_type' => '类型',
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
        //Admin-line
        'coordinate_list' => '坐标列表',
        //Admin-Common
        //Admin-Company
        'line_rule' => '线路分配规则',
        'address_template_id' => '地址模板',
        'weight_unit' => '重量单位',
        'currency_unit' => '货币单位',
        'volume_unit' => '体积单位',
        'map' => '地图',
        //Admin-Country
        //Admin-Driver
        'fullname' => '姓名',
        'gender' => '性别',
        'birthday' => '生日',
        'duty_paragraph' => '税号',
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
        'username' => '用户名',
        'group_id' => '用户组',
        'institution_id' => '组织机构',
        //Admin-Institution
        'parent_id' => '父节点ID',
        //Admin-Line
        'warehouse_id' => '仓库ID',
        'pickup_max_count' => '取件最大订单量',
        'pie_max_count' => '派件最大订单量',
        'is_increment' => '是否新增取件线路',
        'order_deadline' => '当天下单截止时间',
        'appointment_days' => '可预约天数',
        'work_day_list' => '工作日',
        'can_skip_batch' => '能否跳过',
        //Admin-Order
        'execution_date' => '取派日期',
        'out_order_no' => '外部订单号',
        'express_first_no' => '快递单号1',
        'express_second_no' => '快递单号2',
        'source' => '来源',
        'list_mode' => '清单模式',
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
        'order_count' => '订单数量',
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

        //driver-tourTask
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

        'advance_days' => '下单提前天数',
        'delay_time' => '延后时间(分钟)',
        'merchant_group_id' => '商户组ID',
        'status' => '状态',
        'transport_price_id' => '运价ID',
        'is_default' => '是否默认',
        'starting_price' => '起步价',
        'permission' => '权限',
        'package_list' => '包裹列表',
        'material_list' => '材料列表',

        'km' => '公里',
        'weight' => '重量',
        'special_time' => '时间点',

        'url' => 'url',
        'white_ip_list' => '白名单',

        //order-import
        "item_type_1" => "物品一类型",
        "item_name_1" => "物品一名称",
        "item_number_1" => "物品一编号",
        "item_count_1" => "物品一数量",
        "item_weight_1" => "物品一重量",

        "item_type_2" => "物品二类型",
        "item_name_2" => "物品二名称",
        "item_number_2" => "物品二编号",
        "item_count_2" => "物品二数量",
        "item_weight_2" => "物品二重量",

        "item_type_3" => "物品三类型",
        "item_name_3" => "物品三名称",
        "item_number_3" => "物品三编号",
        "item_count_3" => "物品三数量",
        "item_weight_3" => "物品三重量",

        "item_type_4" => "物品四类型",
        "item_name_4" => "物品四名称",
        "item_number_4" => "物品四编号",
        "item_count_4" => "物品四数量",
        "item_weight_4" => "物品四重量",

        "item_type_5" => "物品五类型",
        "item_name_5" => "物品五名称",
        "item_number_5" => "物品五编号",
        "item_count_5" => "物品五数量",
        "item_weight_5" => "物品五重量",

        //order_no_rule
        "prefix" => "开始字符",
        "int_length" => "数字长度",
        "string_length" => "字母长度",
        "max_no" => "最大单号",
        "start_index" => "数字计数",
        "start_string_index" => "前缀计数",

        //轨迹表
        'time' => '时间',

        //费用表
        'level' => '级别',
        'amount' => '费用',
        'total_sticker_amount' => '贴单总费用',
        'total_delivery_amount' => '提货总费用',

        'date_list' => '日期列表',

        //
        'begin_distance' => '回仓里程',
        'end_distance' => '出仓里程',
        'tour_no' => '线路编号',

        'package_no' => '包裹编号',
        'recharge_no' => '充值单号',
        'transaction_number' => '外部充值号',
        'out_user_name' => '外部用户名',
        'out_user_phone' => '外部用户电话',
        'recharge_date' => '充值日期',
        'recharge_time' => '充值时间',
        'driver_name' => '司机名',
        'recharge_amount' => '充值金额',
        'recharge_first_pic' => '充值图片',
        'recharge_second_pic' => '充值图片',
        'recharge_third_pic' => '充值图片',
        'driver_verify_status' => '验证状态',
        'verify_status' => '审核状态',
        'verify_recharge_amount' => '审核金额',
        'verify_date' => '审核日期',
        'verify_time' => '审核时间',
        'verify_remark' => '审核备注',
        'additional_status' => '顺带功能',
        "id" => "",
        "company_id" => "公司ID",
        "merchant_id" => "商户ID",
        "batch_no" => "站点编号",
        "receiver_lon" => "收件人经度",
        "receiver_lat" => "收件人纬度",
        "created_at" => "创建时间",
        "updated_at" => "修改时间",
        "template" => "模板",
        "date" => "日期",
        "directions_times" => "智能优化数",
        "actual_directions_times" => "智能优化成功数",
        "api_directions_times" => "智能优化请求第三方数",
        "distance_times" => "计算距离数",
        "actual_distance_times" => "计算距离成功数",
        "api_distance_times" => "计算距离请求第三方数",
        "line_id" => "线路ID",
        "line_name" => "线路名",
        "exception_label" => "标签",
        "driver_phone" => "司机电话",
        "driver_rest_time" => "司机休息时长",
        "sort_id" => "排序ID",
        "is_skipped" => "是否跳过",
        "expect_pickup_quantity" => "预计取件数量(预计入库数量)",
        "actual_pickup_quantity" => "实际取件数量(实际入库数量)",
        "expect_pie_quantity" => "预计派件数量(预计出库数量)",
        "actual_pie_quantity" => "实际派件数量(实际出库数量)",
        "expect_arrive_time" => "预计到达时间",
        "actual_arrive_time" => "实际到达时间",
        "expect_distance" => "预计里程",
        "actual_distance" => "实际里程",
        "expect_time" => "预计耗时(秒)",
        "actual_time" => "实际耗时耗时(秒)",
        "sticker_amount" => "贴单费用",
        "delivery_amount" => "提货费用",
        "actual_replace_amount" => "实际代收货款",
        "actual_settlement_amount" => "实际结算金额",
        "auth_fullname" => "身份人姓名",
        "auth_birth_date" => "身份人出身年月",
        "batch_exception_no" => "异常编号",
        "receiver" => "收货方姓名",
        "deal_id" => "处理人ID(员工ID)",
        "deal_name" => "处理人姓名",
        "deal_time" => "处理时间",
        "attached_document" => "附件",
        "company_code" => "公司代码",
        "show_type" => "展示方式",
        "short" => "简称",
        "tel" => "区号",
        "messager" => "通讯标志",
        "encrypt" => "盐值",
        "workday" => "工作的时间",
        "business_range" => "业务范围",
        "auth_group_id" => "权限组/员工组",
        "forbid_login" => "禁止登录标志",
        "connection" => "",
        "queue" => "",
        "payload" => "",
        "exception" => "",
        "failed_at" => "",
        "holiday_id" => "放假ID",
        "parent" => "",
        "ancestor" => "",
        "descendant" => "",
        "distance" => "",
        "attempts" => "",
        "reserved_at" => "",
        "available_at" => "",
        "start" => "起始重量",
        "end" => "截止重量",
        "price" => "加价",
        "rule" => "",
        "creator_id" => "创建人ID(员工ID)",
        "creator_name" => "创建人姓名(员工姓名)",
        "schedule" => "取件日期(0-星期日",
        "user" => "操作用户",
        "operation" => "操作记录",
        "post_code_start" => "开始邮编",
        "post_code_end" => "结束邮编",
        "order_no" => "订单号",
        "expect_quantity" => "预计数量",
        "actual_quantity" => "实际数量",
        "key" => "key",
        "secret" => "secret",
        "recharge_status" => "充值通道",
        "fee_code" => "费用编码",
        "count" => "成员数量",
        "is_alone" => "是否独立取派",
        "migration" => "",
        "batch" => "",
        "mask_code" => "掩码",
        "unique_code" => "识别码",
        "sticker_no" => "贴单号",
        "out_status" => "是否可以出库",
        "log" => "日志",
        "success_order" => "导入订单成功数量",
        "fail_order" => "导入订单失败数量",
        "total_order" => "总订单数",
        "quantity" => "数量",
        "volume" => "体积",
        "feature_logo" => "特性",
        "is_auth" => "是否需要身份验证",
        "recharge_statistics_id" => "充值统计ID",
        "total_recharge_amount" => "充值金额",
        "recharge_count" => "充值单数",
        "verify_name" => "审核人",
        "tour_driver_event_id" => "派送事件ID",
        "stop_time" => "停留时间",
        "source_name" => "来源名称",
        "sequence" => "",
        "uuid" => "",
        "family_hash" => "",
        "should_display_on_index" => "",
        "entry_uuid" => "",
        "tag" => "",
        "driver_avt_id" => "取派件AVT设备ID",
        "warehouse_name" => "仓库名称",
        "warehouse_phone" => "仓库电话",
        "warehouse_country" => "仓库国家",
        "warehouse_post_code" => "仓库邮编",
        "warehouse_city" => "仓库城市",
        "warehouse_street" => "仓库街道",
        "warehouse_house_number" => "仓库门牌号",
        "warehouse_address" => "仓库详细地址",
        "warehouse_lon" => "仓库经度",
        "warehouse_lat" => "仓库纬度",
        "warehouse_expect_time" => "抵达仓库预计耗时",
        "warehouse_expect_distance" => "抵达仓库预计历程",
        "warehouse_expect_arrive_time" => "抵达仓库预计时间",
        "begin_time" => "出库时间",
        "end_time" => "入库时间",
        "actual_out_status" => "是否已确认出库",
        "lave_distance" => "剩余里程数",
        "icon_id" => "图标 id 预留",
        "icon_path" => "图标url地址",
        "route_tracking_id" => "对应的路线追踪中的点,预留",
        "action" => "对在途进行的操作",
        "finish_quantity" => "完成数量",
        "surplus_quantity" => "剩余数量",
        "uploader_email" => "上传者邮箱",
        "version" => "版本号",
        "change_log" => "更新日志",
        "to_id" => "用户ID",
        "data" => "数据",
        "company_auth" => "是否需要验证公司权限",
    



]
];