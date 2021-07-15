<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class MemorandumValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'content' => 'required|string|max:250',
        'image_list' => 'nullable|string'
    ];

    public $scene = [
        'store' => ['content','image_list'],
        'update' => ['content','image_list'],
    ];
}

