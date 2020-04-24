<?php


namespace App\Http\Validate\Api\Merchant;


use App\Http\Validate\BaseValidate;

class RegisterValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '商家名称',
        'email' => '邮箱',
        'password' => '密码',
        'confirm_password' => '密码确认',
        'code' => '验证码',
        'new_password' => '新密码',
        'confirm_new_password' => '新密码确认'
    ];

    public $rules = [
        'email' => 'required|email',
        'password' => 'required|string|between:8,20',
        'confirm_password' => 'required|string|same:password',
        //'phone'     => 'required|string|phone:CN',
        'code' => 'required|string|digits:6',
        'new_password' => 'required|string|between:8,20',
        'confirm_new_password' => 'required|string|same:new_password',
        'name' => 'required|string|max:50|uniqueIgnore:company,id',
        'company_code' => 'required|string'
    ];

    public $scene = [
        'store' => ['name', 'email', 'password', 'confirm_password', 'company_code'],
        'applyOfRegister' => ['email'],
        'applyOfReset' => ['email'],
        'resetPassword' => ['new_password', 'confirm_new_password', 'code', 'email'],
    ];
}
