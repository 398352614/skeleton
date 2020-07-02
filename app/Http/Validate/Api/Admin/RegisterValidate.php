<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class RegisterValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'email' => 'required|email',
        'password' => 'required|string|between:8,20',
        'confirm_password' => 'required|string|same:password',
        //'phone'     => 'required|string|phone:CN',
        'code' => 'required|string|digits:6',
        'new_password' => 'required|string|between:8,20',
        'confirm_new_password' => 'required|string|same:new_password',
        'name' => 'required|string|max:50|uniqueIgnore:company,id'
    ];

    public $scene = [
        'store' => ['name', 'email', 'password', 'confirm_password', 'code'],
        'applyOfRegister' => ['email'],
        'applyOfReset' => ['email'],
        'resetPassword' => ['new_password', 'confirm_new_password', 'code', 'email'],
    ];

}
