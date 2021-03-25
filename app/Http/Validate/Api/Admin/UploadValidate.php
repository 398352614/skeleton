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

    ];


    public $rules = [
        'image' => 'required|image|mimes:jpg,jpeg,bmp,png',
        'file' => 'required|file|mimes:txt,xls,xlsx,doc,docx,jpg,jpeg,bmp,png,pdf',
        'dir' => 'required|string|max:20'
    ];

    public $scene = [
        'imageUpload' => ['image', 'image.file', 'dir'],
        'fileUpload' => ['dir'],
    ];

    public $message = [
        'image.image' => '必须是图片',
        'image.mimes' => ':attribute类型必须是jpeg,bmp,png类型',
        'file.file' => '必须是文件',
        'file.mimes' => ':attribute类型必须是txt,excel,word,jpeg,bmp,png,pdf类型',

    ];
}

