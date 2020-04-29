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
        'code' => '验证码',
        'origin_password' => '原密码',
        'new_confirm_password' => '新密码确认',
        'email' => '用户邮箱',
    ];


    public $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
        'new_password' => 'required|string|between:8,20|different:origin_password',
        'confirm_new_password' => 'required|string|same:new_password',
        'email' => 'required|email',
        'code' => 'required|string|digits_between:6,6',
        'origin_password' => 'required|string|between:8,20',
        'new_confirm_password' => 'required|same:new_password',
    ];

    public $scene = [
        'login' => ['username', 'password'],
        'resetPassword' => ['new_password', 'confirm_new_password', 'email', 'code'],
        'applyOfReset' => ['email'],
        'updatePassword' => ['origin_password', 'new_password', 'new_confirm_password']
    ];

    public $message = [
        'new_password.different'=>'新密码不能和旧密码一样'
    ];
}

