<?php
/**
 * 取件线路 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class UploadValidate extends BaseValidate
{
    public $customAttributes = [
        'image' => '图片',
        'image.file' => '图片',
        'file' => '文件',
        'file.file' => '文件',
    ];


    public $rules = [
        'image' => 'required|array',
        'image.file' => 'required|image|mimes:jpeg,bmp,png',
        'file' => 'required|array',
        'file.file' => 'required|file|mimes:txt,excel,word,jpeg,bmp,png',
    ];

    public $scene = [
        'imageUpload' => ['image', 'image.file', 'dir'],
        'fileUpload' => ['file', 'file.file', 'dir'],
    ];

    public $message = [
        'image.file.image' => '必须是图片',
        'image.file.mimes' => ':attribute类型必须是jpeg,bmp,png类型',
        'file.file.file' => '必须是文件',
        'file.file.mimes' => ':attribute类型必须是txt,excel,word,jpeg,bmp,png类型',

    ];
}

