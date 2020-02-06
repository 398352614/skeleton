<?php


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

class SourceValidate extends BaseValidate
{
    public $customAttributes = [
        'source_name' => '来源名称'
    ];

    public $rules = [
        'source_name' => 'required|string|max:250|uniqueIgnore:source,id,company_id',
    ];

    public $scene = [
        'store' => ['source_name']
    ];
}
