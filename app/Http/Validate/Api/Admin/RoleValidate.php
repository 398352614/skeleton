<?php
/**
 * 角色 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class RoleValidate extends BaseValidate
{

    public $rules = [
        'name' => 'required|string|max:50|uniqueIgnore:roles,id,company_id',
        'permission_id_list' => 'required|string',
        'employee_id_list' => 'required|string',
    ];

    public $scene = [
        'store' => ['name'],
        'update' => ['name'],
        'assignPermission' => ['permission_id_list'],
        'assignEmployeeList' => ['employee_id_list'],
        'removeEmployeeList' => ['employee_id_list']
    ];
}

