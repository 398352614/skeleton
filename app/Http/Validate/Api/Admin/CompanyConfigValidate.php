<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyConfigValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '线路分配规则',
        'weight_unit' => '重量单位',
        'currency_unit' => '货币单位',
        'volume_unit' => '体积单位',
        'map' => '地图',
    ];


    public $rules = [
        'line_rule' => 'nullable|string|max:50',
        'weight_unit' => 'nullable|string|max:50',
        'currency_unit' => 'nullable|string|max:50',
        'volume_unit' => 'nullable|string|max:50',
        'map' => 'nullable|string|max:50',
    ];

    public $scene = [
        'update' => [
            'line_rule',
            'weight_unit',
            'currency_unit',
            'volume_unit',
            'map',
        ],
    ];
}

