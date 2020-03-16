<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class AuthValidate extends BaseValidate
{
    public $customAttributes = [
        'username' => '用户名',
        'password' => '密码',
        'new_password' => '新密码',
        'confirm_new_password' => '新密码确认',
        'origin_password' => '原密码',
    ];


    public $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
        'new_password' => 'required|string|between:8,20',
        'confirm_new_password' => 'required|string|same:new_password',
        'origin_password' => 'required|string|between:8,20',
    ];

    public $scene = [
        'login' => ['username', 'password'],
        'updatePassword' => ['origin_password', 'new_password', 'new_confirm_password']
    ];
}

