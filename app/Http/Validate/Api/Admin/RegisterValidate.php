<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class RegisterValidate extends BaseValidate
{
    public $customAttributes = [
        'email'=>'邮箱',
        'password'=>'密码',
        'confirm_password'=>'密码确认',
        'code'=>'验证码',
        'new_password'=>'新密码',
        'confirm_new_password'=>'新密码确认'
    ];

    public $rules = [
        'email' => 'required|email',
        'password' => 'required|string|between:8,20',
        'confirm_password' =>'required|string|same:password',
        //'phone'     => 'required|string|phone:CN',
        'code'      => 'required|string|digits_between:6,6',
        'new_password' => 'required|string|between:8,20',
        'confirm_new_password' =>'required|string|same:new_password',
    ];

    public $scene = [
        'store'=>['email','password','confirm_password','code'],
        'applyOfRegister'=>['email'],
        'applyOfReset'=>['email'],
        'resetPassword'=>['new_password','confirm_new_password','code','email'],
    ];
}
