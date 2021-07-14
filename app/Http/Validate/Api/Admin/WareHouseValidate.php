<?php
/**
 * 网点 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class WareHouseValidate extends BaseValidate
{
    public $customAttributes = [
        'code' => '网点编码'
    ];


    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:warehouse,id,company_id',
        'code' => 'required|string|max:50|uniqueIgnore:warehouse,id,company_id',
        'type' => 'nullable|integer|in:1,2',
        'is_center' => 'required|integer|in:1,2',
        'can_select_all' => 'nullable|integer|in:1,2',
        'acceptance_type' => 'nullable|string',
        'line_ids' => 'nullable|text',
        'fullname' => 'nullable|string|max:50',
        'company_name' => 'nullable|string|max:50',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|string|max:250',
        'avatar' => 'nullable|string|max:250',
        'country' => 'nullable|string|max:50',
        'province' => 'nullable|string|max:50',
        'city' => 'required|string|max:50',
        'district' => 'required|string|max:50',
        'post_code' => 'required|string|max:50',
        'street' => 'required|string|max:50',
        'house_number' => 'required|string|max:50',
        'address' => 'checkAddress|nullable|string|max:250',
        'lon' => 'required|string|max:50',
        'lat' => 'required|string|max:50',
        'parent' => 'required|integer|gte:0',
    ];

    public $scene = [
        'store' => [
            'name', 'code', 'type', 'is_center', 'acceptance_type', 'fullname', 'company_name', 'phone', 'email', 'avatar',
            'phone', 'country', 'post_code', 'house_number', 'city', 'street', 'address', 'lon', 'lat', 'parent','can_select_all'
        ],
        'update' => [
            'name', 'code', 'type', 'is_center', 'acceptance_type', 'fullname', 'company_name', 'phone', 'email', 'avatar',
            'phone', 'country', 'post_code', 'house_number', 'city', 'street', 'address', 'lon', 'lat', 'parent','can_select_all'
        ],
    ];
}

