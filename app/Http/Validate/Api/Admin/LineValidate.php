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
        'warehouse_id' => 'required|integer',
        'pickup_max_count' => 'required|integer|lte:10000|gte:0',
        'pie_max_count' => 'required|integer|lte:10000|gte:0',
        'is_increment' => 'required|integer|in:1,2',
        'order_deadline' => 'required|date_format:H:i:s',
        'appointment_days' => 'required|integer|gte:0|lte:30',
        'remark' => 'nullable|string|max:250',
        'work_day_list' => 'required|string',
        'is_get_area' => 'nullable|integer|in:1,2',
        'status' => 'required|integer|in:1,2',
        'id_list' => 'required|string|checkIdList:100',
        //邮编列表
        'item_list.*.post_code_start' => 'required|integer|between:1000,9999',
        'item_list.*.post_code_end' => 'required|integer|between:1000,9999|gt:item_list.*.post_code_start',
        'coordinate_list' => 'required',
        //区域列表
        'coordinate_list.*.lat' => 'required|string|max:30',
        'coordinate_list.*.lon' => 'required|string|max:30',
    ];

    public $scene = [
        /*****************************************************邮编*****************************************************/
        'postcodeStore' => [
            'name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end', 'status'
        ],
        'postcodeUpdate' => [
            'name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark', 'work_days_list',
            'item_list.*.post_code_start', 'item_list.*.post_code_end', 'status'
        ],
        /*****************************************************区域*****************************************************/
        'areaIndex' => ['is_get_area'],
        'areaStore' => [
            'name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark',
            'coordinate_list', 'coordinate_list.*.lat', 'coordinate_list.*.lon', 'status'
        ],
        'areaUpdate' => [
            'name', 'country', 'warehouse_id', 'pickup_max_count', 'pie_max_count', 'is_increment', 'order_deadline', 'appointment_days', 'remark',
            'coordinate_list', 'coordinate_list.*.lat', 'coordinate_list.*.lon', 'status'
        ],
        'statusByList' => ['id_list', 'status']
    ];
}

