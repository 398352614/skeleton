<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyConfigValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'line_rule'                 => 'required|integer|in:1,2',
        'scheduling_rule'           => 'required|integer|in:1,2',
        'address_template_id'       => 'required|integer|in:1,2',
        'weight_unit'               => 'required|integer|in:1,2',
        'show_type'                 => 'nullable|string|max:50',
        'currency_unit'             => 'required|integer|in:1,2,3',
        'volume_unit'               => 'required|integer|in:1,2',
        'map'                       => 'required|string|max:50',
        'stock_exception_verify'    => 'nullable|integer|in:1,2'
    ];

    public $scene = [
        'update' => [
            'line_rule',
            'address_template_id',
            'show_type',
            'map',
            'stock_exception_verify'
        ],

        'unit_update' => [
            'weight_unit',
            'currency_unit',
            'volume_unit'
        ],

        'rule_update' => [
            'line_rule',
            'scheduling_rule'
        ]
    ];
}

