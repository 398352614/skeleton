<?php
return [
    "merchant" => [
        "type" => "Type",
        "name" => "Name",
        "email" => "Email",
        "settlement_type" => "Settlement",
        "merchant_group_id" => "Group",
        "contacter" => "Contacter",
        "phone" => "Phone",
        "address" => "Address",
        "status" => "Status",
        'country' => "Country",
    ],

    "batchList" => [
        "id" => "No",
        "place_fullname" => "Name",
        "place_phone" => "Phone",
        "out_user_id" => "Acc",
        "place_address" => "Place Address",
        "place_post_code" => "Place Postcode",
        "place_city" => "Place City",
        "merchant" => "Merchant",
        "expect_pickup_quantity" => "Pickup Quantity",
        "expect_pie_quantity" => "Pie Quantity",
        "express_first_no_one" => "Barcode 1",
        "express_first_no_two" => "Barcode 2",
    ],


    "tour" => [
        "tour_no" => "Tour No",
        "line_name" => "Line Name",
        "driver_name" => "Driver Name",
        "execution_date" => "Execution Date",
        "expect_pie_package_quantity" => "Expect Delivery Package Quantity",
        "actual_pie_package_quantity" => "Actual Delivery Package Quantity",
        "expect_pickup_package_quantity" => "Expect Pickup Package Quantity",
        "actual_pickup_package_quantity" => "Actual Pickup Package Quantity",
        "expect_material_quantity" => "Expect Material Quantity",
        "actual_material_quantity" => "Actual Material Quantity",
        "place_out_user_id" => "Fullname ID",
        "place_fullname" => "Fullname",
        "place_phone" => "Phone",
        "place_post_code" => "Postcode",
        "place_address" => "Address",
        "expect_pie_quantity" => "Expect Delivery Order Quantity",
        "actual_pie_quantity" => "Actual Delivery Order Quantity",
        "expect_pickup_quantity" => "Expect Pickup Order Quantity",
        "actual_pickup_quantity" => "Actual Pickup Order Quantity",
        "status" => "Status",
        "actual_arrive_time" => "Arrival time",
        "expect_arrive_time" => "Expect Arrive Time",
        "out_arrive_expect_time" => "Out Expect Arrive Time",
    ],

    /*    "orderOut" => [
            "order_no" => "Order No",
            "merchant_name" => "Merchant Name",
            "status" => "Status",
            "out_order_no" => "Out Order No",
            "place_post_code" => "Post Code",
            "place_house_number" => "House Number",
            "execution_date" => "Execution Date",
            "driver_name" => "Driver Name",
            "batch_no" => "Batch No",
            "tour_no" => "Tour No",
            "line_name" => "Line Name",
        ],*/

    "plan" => [
        'execution_date' => "Date",
        'line_name' => "Line",
        'driver_name' => "Driver",
        'car_no' => "Car",

        'batch_no' => "No",
        'out_user_id' => "ID",
        'place_fullname' => "Name",
        'place_phone' => "Phone",
        'place_address' => "Address",
        'place_post_code' => "Postcode",
        'place_city' => "City",
        'merchant_name' => "Merchant",
        'type' => "Type",
        'package_quantity' => "Quantity",
        'out_order_no' => "Order No.",
        'mask_code' => "Eyecode",
        'material_code_list' => 'Material Code',
        'material_expect_quantity_list' => 'Material Quantity'
    ],

    "carDistance" => [
        'car_no' => 'Number Plate',
        'driver_name' => 'Driver',
        'execution_date' => 'Execution Date',
        'begin_distance' => 'Starting Distance',
        'end_distance' => 'End Distance',
        'expect_distance' => 'Expect Distance',
        'handmade_actual_distance' => 'Actual Distance',
    ],

    'batchCount' => [
        'date' => 'Date',
        'driver' => 'Driver',
        'total_batch_count' => 'Total Pick ups',
        'erp_batch_count' => 'Only Parcels',
        'mes_batch_count' => 'Only MES',
        'mix_batch_count' => 'Both',
        'erp_batch_percent' => 'Only Parcels %',
        'mes_batch_percent' => 'Only MES %',
        'mix_batch_percent' => 'Other %',
    ],


    "trackingOrderOut" => [
        'tracking_order_no' => 'tracking order number',
        'type' => 'Type',
        'order_no' => 'Order No',
        'merchant_name' => 'Merchant',
        'status' => 'Status',
        'out_user_id' => 'Out User ID',
        'out_order_no' => 'Out User No',
        'place_post_code' => 'Post Code',
        'place_house_number' => 'House Number',
        'execution_date' => 'Date',
        'driver_name' => 'Driver',
        'batch_no' => 'Batch No',
        'tour_no' => 'Tour No',
        'line_name' => 'Line',
        'created_at' => 'Creation time',
    ],

    "orderOut" => [
        'order_no' => 'Order number',
        'merchant_id' => 'Merchant ID',
        'type' => 'Type',
        'merchant_name' => 'Merchant Name',
        'status' => 'Status',
        'out_user_id' => 'Out User ID',
        'out_order_no' => 'Out Order No',
        'sender_post_code' => 'Sender Postcode',
        'sender_house_number' => 'Sender House Number',
        'sender_execution_date' => 'Sender Date',
        'receiver_post_code' => 'Receiver Postcode',
        'receiver_house_number' => 'Receiver House Number',
        'receiver_execution_date' => 'Receiver Date',
        'package_name' => 'Package',
        'package_quantity' => 'Package Quantity',
        'material_name' => 'Material',
        'material_quantity' => 'Material Quantity',
        'replace_amount' => 'Replace Amount',
        'sticker_amount' => 'Sticker Amount',
        'settlement_amount' => 'Settlement Amount',
        'created_at' => 'Creation Time'
    ],

    "order" => [
        [
            "base" => "Base",
            "sender" => "Sender",
            "receiver" => "Receiver",
            "amount" => "Amount",
            "settlement" => "Settlement",
            "other" => "Other",
            "package_1" => "Package 1",
            "package_2" => "Package 2",
            "package_3" => "Package 3",
            "package_4" => "Package 4",
            "package_5" => "Package 5",
            "material_1" => "Material 1",
            "material_2" => "Material 2",
            "material_3" => "Material 3",
            "material_4" => "Material 4",
            "material_5" => "Material 5",
        ],
        [
            'create_date' => "*Create date",
            "type" => "*Type",
            'merchant' => "*Merchant",
            'out_user_id' => "Out user id",
            'out_order_no' => "Out order number",

            "place_fullname" => "*Fullname",
            "place_phone" => "*Phone",
            "place_post_code" => "*Post Code",
            "place_house_number" => "*House number",
            "place_country_name" => "*Country",

            "place_city" => "*City",
            "place_street" => "*Street",
            "execution_date" => "*Execution date",


            "second_place_fullname" => "*Fullname",
            "second_place_phone" => "*Phone",
            "second_place_country_name" => "*Country",
            "second_place_post_code" => "*Post Code",
            "second_place_house_number" => "*House number",
            "second_place_city" => "*City",
            "second_place_street" => "*Street",
            "second_execution_date" => "*Execution date",

            "control_mode" => "Control mode",
            "receipt_type" => "Receipt type",
            "receipt_count" => "Receipt count",

            "amount_1" => "Basic freight cost",
            "amount_2" => "Cargo value",
            "amount_3" => "Premium",
            "amount_4" => "Packaging fee",
            "amount_5" => "Delivery fee",
            "amount_6" => "Upstairs delivery fee",
            "amount_7" => "Receiving fee",
            "amount_8" => "Loading fee",
            "amount_9" => "Other fee",
            "amount_10" => "Payment collection",
            "amount_11" => "Cargo payment service charge",

            "special_remark" => "Special remark",
            "mask_code" => "Mask code",
            "settlement_amount" => "Settlement amount",
            "settlement_type" => "Settlement type",

            "package_no_1" => "*Number",
            "package_name_1" => "Name",
            "package_weight_1" => "Weight",
            "package_feature_1" => "Feature",
            "package_remark_1" => "Remark",
            "package_expiration_date_1" => "Expiration date",
            "package_out_order_no_1" => "Out order number",

            "package_no_2" => "Number",
            "package_name_2" => "Name",
            "package_weight_2" => "Weight",
            "package_feature_2" => "Feature",
            "package_remark_2" => "Remark",
            "package_expiration_date_2" => "Expiration date",
            "package_out_order_no_2" => "Out order number",

            "package_no_3" => "Number",
            "package_name_3" => "Name",
            "package_weight_3" => "Weight",
            "package_feature_3" => "Feature",
            "package_remark_3" => "Remark",
            "package_expiration_date_3" => "Expiration date",
            "package_out_order_no_3" => "Out order number",

            "package_no_4" => "Number",
            "package_name_4" => "Name",
            "package_weight_4" => "Weight",
            "package_feature_4" => "Feature",
            "package_remark_4" => "Remark",
            "package_expiration_date_4" => "Expiration date",
            "package_out_order_no_4" => "Out order number",

            "package_no_5" => "Number",
            "package_name_5" => "Name",
            "package_weight_5" => "Weight",
            "package_feature_5" => "Feature",
            "package_remark_5" => "Remark",
            "package_expiration_date_5" => "Expiration date",
            "package_out_order_no_5" => "Out order number",

            "material_code_1" => "*Code",
            "material_name_1" => "Name",
            "material_count_1" => "Count",
            "material_weight_1" => "Weight",
            "material_size_1" => "Size",
            "material_type_1" => "Type",
            "material_pack_type_1" => "Pack type",
            "material_price_1" => "Price",
            "material_remark_1" => "Remark",
            "material_out_order_no_1" => "Out order number",

            "material_code_2" => "Code",
            "material_name_2" => "Name",
            "material_count_2" => "Count",
            "material_weight_2" => "Weight",
            "material_size_2" => "Size",
            "material_type_2" => "Type",
            "material_pack_type_2" => "Pack type",
            "material_price_2" => "Price",
            "material_remark_2" => "Remark",
            "material_out_order_no_2" => "Out order number",

            "material_code_3" => "Code",
            "material_name_3" => "Name",
            "material_count_3" => "Count",
            "material_weight_3" => "Weight",
            "material_size_3" => "Size",
            "material_type_3" => "Type",
            "material_pack_type_3" => "Pack type",
            "material_price_3" => "Price",
            "material_remark_3" => "Remark",
            "material_out_order_no_3" => "Out order number",

            "material_code_4" => "Code",
            "material_name_4" => "Name",
            "material_count_4" => "Count",
            "material_weight_4" => "Weight",
            "material_size_4" => "Size",
            "material_type_4" => "Type",
            "material_pack_type_4" => "Pack type",
            "material_price_4" => "Price",
            "material_remark_4" => "Remark",
            "material_out_order_no_4" => "Out order number",

            "material_code_5" => "Code",
            "material_name_5" => "Name",
            "material_count_5" => "Count",
            "material_weight_5" => "Weight",
            "material_size_5" => "Size",
            "material_type_5" => "Type",
            "material_pack_type_5" => "Pack type",
            "material_price_5" => "Price",
            "material_remark_5" => "Remark",
            "material_out_order_no_5" => "Out order number"
        ]
    ],

    "merchantOrder" => [
        [
            "base" => "Base",
            "other" => "Other",
            "sender" => "Sender",
            "receiver" => "Receiver",
            "package_1" => "Package 1",
            "package_2" => "Package 2",
            "package_3" => "Package 3",
            "package_4" => "Package 4",
            "package_5" => "Package 5",
            "material_1" => "Material 1",
            "material_2" => "Material 2",
            "material_3" => "Material 3",
            "material_4" => "Material 4",
            "material_5" => "Material 5",
        ],
        [
            "type" => "*Type",
            'out_user_id' => "Out user id",
            'out_order_no' => "Out order number",
            "special_remark" => "Special remark",

            "place_fullname" => "*Fullname",
            "place_phone" => "*Phone",
            "place_country_name" => "*Country",

            "place_post_code" => "*Post Code",
            "place_house_number" => "*House number",
            "place_city" => "*City",
            "place_street" => "*Street",
            "execution_date" => "*Execution date",


            "second_place_fullname" => "*Fullname",
            "second_place_phone" => "*Phone",
            "second_place_country_name" => "*Country",

            "second_place_post_code" => "*Post Code",
            "second_place_house_number" => "*House number",
            "second_place_city" => "*City",
            "second_place_street" => "*Street",
            "second_execution_date" => "*Execution date",

            "mask_code" => "Mask code",
            "settlement_amount" => "Settlement amount",
            "settlement_type" => "Settlement type",

            "package_no_1" => "*Number",
            "package_name_1" => "Name",
            "package_weight_1" => "Weight",
            "package_feature_1" => "Feature",
            "package_remark_1" => "Remark",
            "package_expiration_date_1" => "Expiration date",
            "package_out_order_no_1" => "Out order number",

            "package_no_2" => "Number",
            "package_name_2" => "Name",
            "package_weight_2" => "Weight",
            "package_feature_2" => "Feature",
            "package_remark_2" => "Remark",
            "package_expiration_date_2" => "Expiration date",
            "package_out_order_no_2" => "Out order number",

            "package_no_3" => "Number",
            "package_name_3" => "Name",
            "package_weight_3" => "Weight",
            "package_feature_3" => "Feature",
            "package_remark_3" => "Remark",
            "package_expiration_date_3" => "Expiration date",
            "package_out_order_no_3" => "Out order number",

            "package_no_4" => "Number",
            "package_name_4" => "Name",
            "package_weight_4" => "Weight",
            "package_feature_4" => "Feature",
            "package_remark_4" => "Remark",
            "package_expiration_date_4" => "Expiration date",
            "package_out_order_no_4" => "Out order number",

            "package_no_5" => "Number",
            "package_name_5" => "Name",
            "package_weight_5" => "Weight",
            "package_feature_5" => "Feature",
            "package_remark_5" => "Remark",
            "package_expiration_date_5" => "Expiration date",
            "package_out_order_no_5" => "Out order number",

            "material_code_1" => "*Code",
            "material_name_1" => "Name",
            "material_count_1" => "Count",
            "material_weight_1" => "Weight",
            "material_size_1" => "Size",
            "material_type_1" => "Type",
            "material_pack_type_1" => "Pack type",
            "material_price_1" => "Price",
            "material_remark_1" => "Remark",
            "material_out_order_no_1" => "Out order number",

            "material_code_2" => "Code",
            "material_name_2" => "Name",
            "material_count_2" => "Count",
            "material_weight_2" => "Weight",
            "material_size_2" => "Size",
            "material_type_2" => "Type",
            "material_pack_type_2" => "Pack type",
            "material_price_2" => "Price",
            "material_remark_2" => "Remark",
            "material_out_order_no_2" => "Out order number",

            "material_code_3" => "Code",
            "material_name_3" => "Name",
            "material_count_3" => "Count",
            "material_weight_3" => "Weight",
            "material_size_3" => "Size",
            "material_type_3" => "Type",
            "material_pack_type_3" => "Pack type",
            "material_price_3" => "Price",
            "material_remark_3" => "Remark",
            "material_out_order_no_3" => "Out order number",

            "material_code_4" => "Code",
            "material_name_4" => "Name",
            "material_count_4" => "Count",
            "material_weight_4" => "Weight",
            "material_size_4" => "Size",
            "material_type_4" => "Type",
            "material_pack_type_4" => "Pack type",
            "material_price_4" => "Price",
            "material_remark_4" => "Remark",
            "material_out_order_no_4" => "Out order number",

            "material_code_5" => "Code",
            "material_name_5" => "Name",
            "material_count_5" => "Count",
            "material_weight_5" => "Weight",
            "material_size_5" => "Size",
            "material_type_5" => "Type",
            "material_pack_type_5" => "Pack type",
            "material_price_5" => "Price",
            "material_remark_5" => "Remark",
            "material_out_order_no_5" => "Out order number"
        ]
    ],
];
