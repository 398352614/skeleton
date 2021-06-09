<?php

namespace App\Http\Validate\Api\Merchant;

use App\Http\Validate\BaseValidate;

class AuthValidate extends BaseValidate
{
    public $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
        'new_password' => 'required|string|between:8,20|different:origin_password',
        'new_confirm_password' => 'required|string|same:new_password',
        'origin_password' => 'required|string|between:8,20',
    ];

    public $scene = [
        'login' => ['username', 'password'],
        'updatePassword' => ['origin_password', 'new_password', 'new_confirm_password']
    ];

    public $message = [
    ];
}

