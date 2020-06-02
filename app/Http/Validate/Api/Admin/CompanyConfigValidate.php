<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CompanyConfigValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '线路分配规则',
        'address_template_id' => '地址模板',
        'weight_unit' => '重量单位',
        'currency_unit' => '货币单位',
        'volume_unit' => '体积单位',
        'map' => '地图',
    ];


    public $rules = [
        'line_rule' => 'required|integer',
        'address_template_id' => 'required|integer',
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

