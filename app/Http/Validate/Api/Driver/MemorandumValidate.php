<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class MemorandumValidate extends BaseValidate
{
    public $customAttributes = [
        'content' => '内容',
    ];


    public $rules = [
        'content' => 'required|string|max:250',
    ];

    public $scene = [
        'store' => ['content'],
        'update' => ['content'],
    ];
}

