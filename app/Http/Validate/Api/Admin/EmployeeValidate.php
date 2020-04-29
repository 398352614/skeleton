<?php
namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class EmployeeValidate extends BaseValidate
{
    public $customAttributes = [
        'fullname' => '姓名',
        'username' => '用户名',
        'email'  => '邮箱',
        'phone'  => '手机号码',
        'remark' => '备注',
        'group_id' => '用户组',
        'institution_id' => '组织机构',
        'password' => '密码',
        'confirm_password' => '确认密码',
    ];


    public $rules = [
        'fullname' => 'required|string|max:50|uniqueIgnore:employee,id,company_id',
        'username' => 'required|string|max:50|uniqueIgnore:employee,id,company_id',
        'password' => 'required|string|between:8,20',
        'email' => 'required|email|max:50|uniqueIgnore:employee,id',
        'phone' => 'sometimes|nullable|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'remark' => 'sometimes|nullable|string|max:250',
        'group_id' => 'nullable|integer',
        'institution_id' => 'sometimes|integer',
        'confirm_password' => 'required|same:password',
    ];

    public $scene = [
        'store' => [
            'fullname',
            'username',
            'email',
            'phone',
            'remark',
            'group_id',
            'institution_id',
            'password',
        ],
        'update' => [
            'fullname',
            'username',
            'email',
            'phone',
            'remark',
            'group_id',
            'institution_id',
        ],
        'resetPassword' => [
            'password',
            'confirm_password',
        ]
    ];
}

