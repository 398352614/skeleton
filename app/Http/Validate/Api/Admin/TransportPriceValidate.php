<?php
/**
 * 运价 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class TransportPriceValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '名称',
        'starting_price' => '起步价',
        'remark' => '特殊说明',
        'status' => '状态'
    ];


    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:transport_price,id',
        'starting_price' => 'required|numeric',
        'remark' => 'nullable|string|max:250',
        'status' => 'required|integer|in:1,2',
        //公里计费列表
        'km_list.*.start' => 'required_with:km_list|integer',
        'km_list.*.end' => 'required_with:km_list|integer|gt:km_list.*.start',
        'km_list.*.price' => 'required_with:km_list|numeric',
        //重量计费列表
        'weight_list.*.start' => 'required_with:weight_list|integer',
        'weight_list.*.end' => 'required_with:weight_list|integer|gt:weight_list.*.start',
        'weight_list.*.price' => 'required_with:weight_list|numeric',
        //特殊时段计费列表
        'special_time_list.*.start' => 'required_with:special_time_list|date_format:H:i:s',
        'special_time_list.*.end' => 'required_with:special_time_list|date_format:H:i:s|after:special_time_list.*.start',
        'special_time_list.*.price' => 'required_with:special_time_list|numeric',
    ];

    public $scene = [
        'store' => [
            'name', 'starting_price', 'remark', 'status',
            'km_list', 'km_list.*.start', 'km_list.*.end', 'km_list.*.price',
            'weight_list', 'weight_list.*.start', 'weight_list.*.end', 'weight_list.*.price',
            'special_time_list', 'special_time_list.*.start', 'special_time_list.*.end', 'special_time_list.*.price',
        ],
        'update' => [
            'name', 'starting_price', 'remark', 'status',
            'km_list', 'km_list.*.start', 'km_list.*.end', 'km_list.*.price',
            'weight_list', 'weight_list.*.start', 'weight_list.*.end', 'weight_list.*.price',
            'special_time_list', 'special_time_list.*.start', 'special_time_list.*.end', 'special_time_list.*.price',
        ],
    ];
}

