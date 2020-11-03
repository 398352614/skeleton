<?php
return [
    "merchant" => [
        "type" => "类型",
        "name" => "名称",
        "email" => "邮箱",
        "settlement_type" => "结算方式",
        "merchant_group_id" => "商户组",
        "contacter" => "联系人",
        "phone" => "电话",
        "address" => "地址",
        "status" => "状态",
        'country' => "国家",
    ],

    "batchList" => [
        "id" => "顺序",
        "receiver" => "收件人",
        "receiver_phone" => "收件人电话",
        "out_user_id" => "外部用户ID",
        "receiver_address" => "收件人地址",
        "receiver_post_code" => "收件人邮编",
        "receiver_city" => "收件人城市",
        "merchant" => "商户",
        "expect_pickup_quantity" => "取件数量",
        "expect_pie_quantity" => "派件数量",
        "express_first_no_one" => "快递单号1",
        "express_first_no_two" => "快递单号2"
    ],

    "order" => [
        "type" => "*取派类型",
        "receiver_fullname" => "*收件人姓名",
        "receiver_phone" => "*收件人电话",
        "receiver_post_code" => "*收件人邮编",
        "receiver_house_number" => "*收件人门牌号",
        "execution_date" => "*取派日期",
        "settlement_type" => "*结算类型",
        "settlement_amount" => "运费金额",
        "replace_amount" => "代收货款",
        "out_order_no" => "外部订单号",
        "delivery" => "是否送货上门",
        "remark" => "备注",

        "item_type_1" => "*物品一类型",
        "item_number_1" => "*物品一编号",
        "item_name_1" => "物品一名称",
        "item_count_1" => "物品一数量",
        "item_weight_1" => "物品一重量",

        "item_type_2" => "物品二类型",
        "item_number_2" => "*物品二编号",
        "item_name_2" => "物品二名称",
        "item_count_2" => "物品二数量",
        "item_weight_2" => "物品二重量",

        "item_type_3" => "物品三类型",
        "item_number_3" => "*物品三编号",
        "item_name_3" => "物品三名称",
        "item_count_3" => "物品三数量",
        "item_weight_3" => "物品三重量",

        "item_type_4" => "物品四类型",
        "item_number_4" => "*物品四编号",
        "item_name_4" => "物品四名称",
        "item_count_4" => "物品四数量",
        "item_weight_4" => "物品四重量",

        "item_type_5" => "物品五类型",
        "item_number_5" => "*物品五编号",
        "item_name_5" => "物品五名称",
        "item_count_5" => "物品五数量",
        "item_weight_5" => "物品五重量",

    ],

    "tour" => [
        "tour_no" => "取件线路编号",
        "line_name" => "线路名称",
        "driver_name" => "司机姓名",
        "execution_date" => "取派日期",
        "expect_pie_package_quantity" => "预计派送包裹",
        "actual_pie_package_quantity" => "实际派送包裹",
        "expect_pickup_package_quantity" => "预计取件包裹",
        "actual_pickup_package_quantity" => "实际取件包裹",
        "expect_material_quantity" => "预计派送材料",
        "actual_material_quantity" => "实际派送材料",
        "receiver_out_user_id" => "客户ID",
        "receiver_fullname" => "客户姓名",
        "receiver_phone" => "客户电话",
        "receiver_post_code" => "客户邮编",
        "receiver_address" => "客户地址",
        "expect_pie_quantity" => "预计派件订单",
        "actual_pie_quantity" => "实际派件订单",
        "expect_pickup_quantity" => "预计取件订单",
        "actual_pickup_quantity" => "实际取件订单",
        "status" => "订单状态",
        "actual_arrive_time" => "司机到达时间",
        "expect_arrive_time" => "站点签收时预计到达时间",
        "out_arrive_expect_time" => "确认出库时预计到达时间",
    ],

/*    "orderOut" => [
        "order_no" => "订单号",
        "merchant_name" => "所属商户",
        "status" => "订单状态",
        "out_order_no" => "外部订单号",
        "receiver_post_code" => "邮编",
        "receiver_house_number" => "门牌号",
        "execution_date" => "取派日期",
        "driver_name" => "派送司机",
        "batch_no" => "站点编号",
        "tour_no" => "取件线路编号",
        "line_name" => "线路名称",
    ],*/

    "plan" => [
        'execution_date' => "取派日期",
        'line_name' => "线路名称",
        'driver_name' => "司机姓名",
        'car_no' => "车牌号",
        'batch_no' => "站点编号",
        'out_user_id' => "外部用户ID",
        'receiver_fullname' => "收件人姓名",
        'receiver_phone' => "收件人电话",
        'receiver_address' => "收件人地址",
        'receiver_post_code' => "收件人邮编",
        'receiver_city' => "收件人城市",
        'merchant_name' => "商户名称",
        'type' => "取派类型",
        'package_quantity' => "包裹数量",
        'out_order_no' => "外部订单号",
        'mask_code' => "掩码",
        'material_code_list' => '材料代码',
        'material_expect_quantity_list' => '材料数量'
    ],

    "carDistance" => [
        'car_no' => '车牌号',
        'driver_name' => '司机名',
        'execution_date' => '取派日期',
        'begin_distance' => '出库里程数',
        'end_distance' => '回库里程数',
        'expect_distance' => '预计里程数',
        'handmade_actual_distance' => '实际里程数'
    ],

    'batchCount' => [
        'date' => '日期',
        'driver' => '司机',
        'total_batch_count' => '站点总数',
        'erp_batch_count' => 'ERP站点数',
        'mes_batch_count' => '商城站点数',
        'mix_batch_count' => '混合站点数',
        'erp_batch_percent' => 'ERP占比',
        'mes_batch_percent' => '商城占比',
        'mix_batch_percent' => '混合占比',
    ],

    "trackingOrderOut"=>[
        'tracking_order_no'=>'运单号',
        'type'=>'运单类型',
        'order_no'=>'订单号',
        'merchant_name'=>'所属商户',
        'status'=>'运单状态',
        'out_user_id'=>'外部客户ID',
        'out_order_no'=>'外部订单号',
        'receiver_post_code'=>'邮编',
        'receiver_house_number'=>'门牌号',
        'execution_date'=>'取派日期',
        'driver_fullname'=>'派送司机',
        'batch_no'=>'站点编号',
        'tour_no'=>'取件线路编号',
        'line_name'=>'线路名称',
        'created_at'=>'创建时间',
    ],

    "orderOut"=>[
        'order_no'=>'订单编号',
        'merchant_id'=>'用户编码',
        'type'=>'订单类型',
        'merchant_name'=>'所属商户',
        'status'=>'订单状态',
        'out_user_id'=>'外部客户ID',
        'out_order_no'=>'外部订单号',
        'sender_post_code'=>'取件邮编',
        'sender_house_number'=>'取件门牌号',
        'receiver_post_code'=>'派件邮编',
        'receiver_house_number'=>'派件门牌号',
        'execution_date'=>'取派日期',
        'package_name'=>'包裹',
        'package_quantity'=>'包裹数量',
        'material_name'=>'材料',
        'material_quantity'=>'材料数量',
        'replace_amount'=>'运费',
        'sticker_amount'=>'代收货款',
        'settlement_amount'=>'贴单费',
        'created_at'=>'创建时间'
    ],
];
