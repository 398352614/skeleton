<?php
return [
    "merchant"=>[
        "type"=>"类型",
        "name"=>"名称",
        "email"=>"邮箱" ,
        "settlement_type"=>"结算方式" ,
        "merchant_group_id"=>"商户组",
        "contacter"=>"联系人" ,
        "phone"=>"电话" ,
        "address"=>"地址" ,
        "status"=>"状态",
        'country'=>"国家",
    ],

    "batchList"=>[
        "id"=>"顺序",
        "receiver"=>"收件人",
        "receiver_phone"=>"收件人电话",
        "out_user_id"=>"外部用户ID",
        "receiver_address"=>"收件人地址",
        "receiver_post_code"=>"收件人邮编",
        "receiver_city"=>"收件人城市",
        "merchant"=>"商户",
        "expect_pickup_quantity"=>"取件数量",
        "expect_pie_quantity"=>"派件数量",
        "express_first_no_one"=>"快递单号1",
        "express_first_no_two"=>"快递单号2"
    ],

    "order"=>[
        "type"=>"*取派类型",
        "receiver"=>"*收件人姓名",
        "receiver_phone"=>"*收件人电话",
        "receiver_post_code"=>"*收件人邮编",
        "receiver_house_number"=>"*收件人门牌号",
        "receiver_address"=>"*收件人详细地址",
        "execution_date"=>"*取派日期",
        "settlement_type"=>"*结算类型",
        "settlement_amount"=>"运费金额",
        "replace_amount"=>"代收货款",
        "out_order_no"=>"外部订单号",
        "delivery"=>"是否送货上门",
        "remark"=>"备注",

        "item_type_1"=>"*物品一类型",
        "item_number_1"=>"*物品一编号",
        "item_name_1"=>"物品一名称",
        "item_count_1"=>"物品一数量",
        "item_weight_1"=>"物品一重量",

        "item_type_2"=>"物品二类型",
        "item_number_2"=>"*物品二编号",
        "item_name_2"=>"物品二名称",
        "item_count_2"=>"物品二数量",
        "item_weight_2"=>"物品二重量",

        "item_type_3"=>"物品三类型",
        "item_number_3"=>"*物品三编号",
        "item_name_3"=>"物品三名称",
        "item_count_3"=>"物品三数量",
        "item_weight_3"=>"物品三重量",

        "item_type_4"=>"物品四类型",
        "item_number_4"=>"*物品四编号",
        "item_name_4"=>"物品四名称",
        "item_count_4"=>"物品四数量",
        "item_weight_4"=>"物品四重量",

        "item_type_5"=>"物品五类型",
        "item_number_5"=>"*物品五编号",
        "item_name_5"=>"物品五名称",
        "item_count_5"=>"物品五数量",
        "item_weight_5"=>"物品五重量",

    ],
];
