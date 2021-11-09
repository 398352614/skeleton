<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class ServiceAgreementValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'type' => 'required|string|in:1,2',
        'tittle' => 'nullable|string|max:50',
        'text' => 'nullable|string',
    ];

    public $scene = [
        'store' => [
            'type', 'tittle', 'text',
        ],
    ];

    public $message = [

    ];
}

