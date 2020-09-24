<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class DeviceValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'number' => 'required|string|max:250',
    ];

    public $scene = [
        'bind' => ['number'],
    ];
}

