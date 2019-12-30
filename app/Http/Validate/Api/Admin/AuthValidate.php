<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class AuthValidate extends BaseValidate
{
    public $customAttributes = [
        'username'=>'用户名',
        'password'=>'密码'
    ];

    public $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    public $scene = [
        'login' => ['username','password']
    ];
}
