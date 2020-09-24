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
        "receiver" => "Name",
        "receiver_phone" => "Phone",
        "out_user_id" => "Acc",
        "receiver_address" => "Receiver Address",
        "receiver_post_code" => "Receiver Postcode",
        "receiver_city" => "Receiver City",
        "merchant" => "Merchant",
        "expect_pickup_quantity" => "Receive",
        "expect_pie_quantity" => "Send",
        "express_first_no_one" => "Barcode 1",
        "express_first_no_two" => "Barcode 2",
    ],

    "order" => [
        "type" => "*Type",
        "receiver_fullname" => "*Receiver",
        "receiver_phone" => "*Phone",
        "receiver_post_code" => "*Post Code",
        "receiver_house_number" => "*House Number",
        "execution_date" => "*Execution Date",
        "settlement_type" => "*Settlement Type",
        "settlement_amount" => "Settlement Amount",
        "replace_amount" => "Replace Amount",
        "out_order_no" => "Out Order No",
        "delivery" => "Delivery",
        "remark" => "Remark",

        "item_type_1" => "*Item Type 1",
        "item_number_1" => "*Item Code 1",
        "item_name_1" => "Item Name 1",
        "item_count_1" => "Item Count 1",
        "item_weight_1" => "Item Weight 1",

        "item_type_2" => "*Item Type 2",
        "item_number_2" => "*Item Code 2",
        "item_name_2" => "Item Name 2",
        "item_count_2" => "Item Count 2",
        "item_weight_2" => "Item Weight 2",

        "item_type_3" => "*Item Type 3",
        "item_number_3" => "*Item Code 3",
        "item_name_3" => "Item Name 3",
        "item_count_3" => "Item Count 3",
        "item_weight_3" => "Item Weight 3",

        "item_type_4" => "*Item Type 4",
        "item_number_4" => "*Item Code 4",
        "item_name_4" => "Item Name 4",
        "item_count_4" => "Item Count 4",
        "item_weight_4" => "Item Weight 4",

        "item_type_5" => "*Item Type 5",
        "item_number_5" => "*Item Code 5",
        "item_name_5" => "Item Name 5",
        "item_count_5" => "Item Count 5",
        "item_weight_5" => "Item Weight 5",

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
        "receiver_out_user_id" => "Receiver ID",
        "receiver_fullname" => "Receiver Name",
        "receiver_phone" => "Receiver Phone",
        "receiver_post_code" => "Receiver Postcode",
        "receiver_address" => "Receiver Address",
        "expect_pie_quantity" => "Expect Delivery Order Quantity",
        "actual_pie_quantity" => "Actual Delivery Order Quantity",
        "expect_pickup_quantity" => "Expect Pickup Order Quantity",
        "actual_pickup_quantity" => "Actual Pickup Order Quantity",
        "status" => "Status",
        "actual_arrive_time" => "Arrival time",
        "expect_arrive_time"=>"Expect Arrive Time",
        "out_arrive_expect_time"=>"Out Expect Arrive Time",
    ],

    "orderOut" => [
        "order_no" => "Order No",
        "merchant_name" => "Merchant Name",
        "status" => "Status",
        "out_order_no" => "Out Order No",
        "receiver_post_code" => "Receiver Post Code",
        "receiver_house_number" => "Receiver House Number",
        "execution_date" => "Execution Date",
        "driver_name" => "Driver Name",
        "batch_no" => "Batch No",
        "tour_no" => "Tour No",
        "line_name" => "Line Name",
    ],

    "plan" => [
        'execution_date' => "Date",
        'line_name' => "Line",
        'driver_name' => "Driver",
        'car_no' => "Car",

        'batch_no' => "No",
        'out_user_id' => "ID",
        'receiver_fullname' => "Name",
        'receiver_phone' => "Phone",
        'receiver_address' => "Address",
        'receiver_post_code' => "Postcode",
        'receiver_city' => "City",
        'merchant_name' => "Merchant",
        'type' => "Type",
        'package_quantity' => "Quantity",
        'out_order_no' => "Order No.",
        'mask_code' => "Eyecode",
        'material_code_list' => 'Material Code',
        'material_expect_quantity_list' => 'Material Quantity'
    ],

    "carDistance"=>[
        'car_no'=>'Number Plate',
        'driver_name'=>'Driver',
        'execution_date'=>'Execution Date',
        'begin_distance'=>'Starting Distance',
        'end_distance'=>'End Distance',
        'expect_distance'=>'Expect Distance',
        'handmade_actual_distance'=>'Actual Distance',
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
    ]
];
