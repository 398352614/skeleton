<?php

namespace App\Http\Validate\Api\Consumer;

class PackageTrailValidate
{

    public $rules = [
        'company_id' => 'nullable|integer',
        'express_first_no' => 'required|string|max:50',
    ];

    public $scene = [
        'index' => [
            'company_id', 'express_first_no'
        ]
    ];

    public $message = [
        'express_first_no.required'=>'请输入单号'
    ];
}

