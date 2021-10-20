<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyCustomizeValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [

        'status'=>'required|integer|in:1,2',
        'admin_url'=> 'nullable|string',
        'admin_login_background'=> 'nullable|string',
        'admin_login_title'=> 'nullable|string',
        'admin_main_logo'=> 'nullable|string',
        'merchant_url'=> 'nullable|string',
        'merchant_login_background'=> 'nullable|string',
        'merchant_login_title'=> 'nullable|string',
        'merchant_main_logo'=> 'nullable|string',
        'driver_login_title'=> 'nullable|string',
        'consumer_url'=> 'nullable|string',
        'consumer_login_title'=> 'nullable|string',

    ];

    public $scene = [
        'update' => [
            'admin_url',
            'admin_login_background',
            'admin_login_title',
            'admin_main_logo',
            'merchant_url',
            'merchant_login_background',
            'merchant_login_title',
            'merchant_main_logo',
            'driver_login_title',
            'consumer_url',
            'consumer_login_title',
        ],
    ];
}

