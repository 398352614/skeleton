<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyConfigValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'line_rule' => 'required|integer|in:1,2',
        'address_template_id' => 'required|integer|in:1,2',
        'weight_unit' => 'required|string|max:50',
        'currency_unit' => 'required|string|max:50',
        'volume_unit' => 'required|string|max:50',
        'map' => 'required|string|max:50',
    ];

    public $scene = [
        'update' => [
            'line_rule',
            'address_template_id',
            'weight_unit',
            'currency_unit',
            'volume_unit',
            'map',
        ],
    ];
}

