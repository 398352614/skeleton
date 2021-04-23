<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class EmployeeValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'fullname' => 'required|string|max:50|uniqueIgnore:employee,id,company_id',
        'username' => 'required|string|max:50|uniqueIgnore:employee,id,company_id',
        'password' => 'required|string|between:8,20',
        'email' => 'required|email|max:50|uniqueIgnore:employee,id',
        'phone' => 'sometimes|nullable|string|max:20|regex:/^[0-9]([0-9-])*[0-9]$/',
        'remark' => 'sometimes|nullable|string|max:250',
        'confirm_password' => 'required|same:password',
        'role_id' => 'required|integer',
        'warehouse_id' => 'required|integer',
        'address' => 'nullable|string',
        'avatar' => 'nullable|string'

    ];

    public $scene = [
        'store' => [
            'fullname',
            'username',
            'email',
            'phone',
            'remark',
            'password',
            'role_id',
            'warehouse_id',
            'address',
            'avatar'
        ],
        'update' => [
            'fullname',
            'username',
            'email',
            'phone',
            'remark',
            'role_id',
            'warehouse_id',
            'address',
            'avatar'
        ],
        'resetPassword' => [
            'password',
            'confirm_password',
        ]
    ];
}

