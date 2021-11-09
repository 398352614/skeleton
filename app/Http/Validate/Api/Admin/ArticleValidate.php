<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class ArticleValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'type' => 'required|string|in:1,2,3,4',
        'tittle' => 'nullable|string|max:50',
        'text' => 'nullable|text',

    ];

    public $scene = [
        'store' => [
            'type', 'tittle', 'text',
        ],
    ];

    public $message = [

    ];
}

