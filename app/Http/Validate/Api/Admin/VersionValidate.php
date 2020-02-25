<?php
/**
 * 版本 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class VersionValidate extends BaseValidate
{
    public $customAttributes = [
    ];


    public $rules = [
        'name' => 'nullable|string|max:20',
        'version' => 'required|string|max:50',
        'status' => 'nullable|integer|max:4',
        'file' => 'required|file',
        'change_log' => 'nullable|string',

    ];

    public $scene = [
        'store' => ['name', 'version', 'status', 'file', 'change_log'],
        'update' => ['name', 'version', 'status',  'change_log']
    ];

    public $message = [
        'file.file' => '必须是文件',
        ];
}

