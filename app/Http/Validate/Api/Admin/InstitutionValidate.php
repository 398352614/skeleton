<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class InstitutionValidate extends BaseValidate
{
    public $customAttributes = [
        'name' => '机构名称',
        'country' => '所在国家/城市',
        'contacts' => '负责人',
        'phone' => '联系电话',
        'address' => '详细地址',
        'parent_id' => '父节点ID',
    ];


    public $rules = [
        'name' => 'required|string|max:50',
        'country' => 'sometimes|nullable|string|max:50',
        'contacts' => 'sometimes|nullable|string|max:20',
        'phone' => 'sometimes|nullable|string|max:20',
        'address' => 'sometimes|nullable|string|max:250',
        'parent_id' => 'required|integer',
    ];

    public $scene = [
        'update' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
        ],
        'store' => [
            'name',
            'country',
            'contacts',
            'phone',
            'address',
            'parent_id'
        ],
    ];
}

