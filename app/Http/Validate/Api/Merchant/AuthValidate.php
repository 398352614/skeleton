<?php

namespace App\Http\Validate\Api\Merchant;

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
        'new_password' => 'required|string|between:8,20|different:origin_password',
        'confirm_new_password' => 'required|string|same:new_password',
        'origin_password' => 'required|string|between:8,20',
    ];

    public $scene = [
        'login' => ['username', 'password'],
        'updatePassword' => ['origin_password', 'new_password', 'confirm_new_password']
    ];

    public $message = [
        'new_password.different'=>'新密码不能和旧密码一样'
    ];
}

