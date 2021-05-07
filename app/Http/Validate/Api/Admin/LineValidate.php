<?php
/**
 * 线路 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class LineValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:line,id,company_id',
        'country' => 'nullable|string|max:50',
        'warehouse_id' => 'nullable|integer',
        'pickup_max_count' => 'required|integer|lte:10000|gte:0',
        'pie_max_count' => 'required|integer|lte:10000|gte:0',
        'is_increment' => 'required|integer|in:1,2',
        'can_skip_batch' => 'required|integer|in:1,2',
        'order_deadline' => 'required|date_format:H:i:s',
        'appointment_days' => 'required|integer|gte:0|lte:30',
        'remark' => 'nullable|string|max:250',
        'work_day_list' => 'required|string',
        'is_get_area' => 'nullable|integer|in:1,2',
        'status' => 'required|integer|in:1,2',
        'id_list' => 'required|string|checkIdList:100',
        'date' => 'required|date',
        //邮编列表
        'item_list.*.post_code_start' => 'required|integer|between:1000,99999',
        'item_list.*.post_code_end' => 'required|integer|between:1000,99999|gte:item_list.*.post_code_start',
        'coordinate_list' => 'required',
        //区域列表
        'coordinate_list.*.*.lat' => 'required|string|max:30',
        'coordinate_list.*.*.lon' => 'required|string|max:30',
``        'place_fullname' => 'required|string|max:50',
        'place_province' => 'nullable|string|max:50',
        'place_post_code' => 'required|string|max:50',
        'place_house_number' => 'required|string|max:50',
        'place_city' => 'nullable|string|max:50',
        'place_district' => 'nullable|string|max:50',
        'place_street' => 'nullable|string|max:50',
        'place_address' => 'nullable|string|max:250',
        'place_lon' => 'required|string|max:50',
        'place_lat' => 'required|string|max:50',
        'second_place_fullname' => 'required|string|max:50',
        'second_place_phone' => 'nullable|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'second_place_province' => 'nullable|string|max:50',
        'second_place_post_code' => 'required|string|max:50',
        'second_place_house_number' => 'required|string|max:50',
        'second_place_city' => 'nullable|string|max:50',
        'second_place_district' => 'nullable|string|max:50',
        'second_place_street' => 'nullable|string|max:50',
        'second_place_address' => 'nullable|string|max:250',
        'second_place_lon' => 'required|string|max:50',
        'second_place_lat' => 'required|string|max:50',
        'execution_date' => 'required|date|after_or_equal:today',
        'second_execution_date' => 'required|date|after_or_equal:today',
    ];

    public $scene = [
        /*****************************************************邮编*****************************************************/
        'postcodeStore' => [
            'name', 'country', 'can_skip_batch', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end', 'status'
        ],
        'postcodeUpdate' => [
            'name', 'country', 'can_skip_batch', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end', 'status'
        ],
        /*****************************************************区域*****************************************************/
        'areaIndex' => ['is_get_area'],
        'areaStore' => [
            'name', 'country', 'can_skip_batch', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark',
            'coordinate_list', 'coordinate_list.*.*.lat', 'coordinate_list.*.*.lon', 'status'
        ],
        'areaUpdate' => [
            'name', 'country', 'can_skip_batch', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark',
            'coordinate_list', 'coordinate_list.*.*.lat', 'coordinate_list.*.*.lon', 'status'
        ],
        'statusByList' => ['id_list', 'status'],
        'getListByDate' => ['date'],
        'test' => [
            'place_fullname',
            'place_phone',
            'place_post_code',
            'place_house_number',
            'place_city',
            'place_street',
            'place_address',
            'place_lon',
            'place_lat',
            'execution_date',
            'second_place_fullname',
            'second_place_phone',
            'second_place_country',
            'second_place_post_code',
            'second_place_house_number',
            'second_place_city',
            'second_place_street',
            'second_place_address',
            'second_place_lon',
            'second_place_lat',
            'second_execution_date',
        ]
    ];
}

