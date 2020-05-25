<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class AuthValidate extends BaseValidate
{
    public $customAttributes = [
        'username' => '用户名',
        'password' => '密码',
        'origin_password'=>'旧密码',
        'new_password'=>'新密码',
        'new_confirm_password'=>'新密码确认'
    ];

    public $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
        'origin_password' => 'required|string|between:8,20',
        'new_password' => 'required|string|between:8,20|different:origin_password',
        'new_confirm_password' => 'required|same:new_password',
    ];

    public $scene = [
        'login' => ['username', 'password'],
        'updatePassword' => ['origin_password', 'new_password', 'new_confirm_password']
    ];

    public $message = [
    ];
}
