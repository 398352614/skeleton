<?php
/**
 * 线路任务 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class UploadValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'image' => 'required|mimes:jpeg,bmp,png,jpg',
        'file' => 'required|file|mimes:txt,xls,xlsx,doc,docx,jpeg,bmp,png,pdf,apk,jpg',
        'dir' => 'required|string|max:20'
    ];

    public $scene = [
        'imageUpload' => ['image', 'image.file', 'dir'],
        'fileUpload' => ['file', 'file.file', 'dir'],
    ];

    public $message = [
        'image.image' => '必须是图片',
        'image.mimes' => ':attribute类型必须是jpeg,bmp,png,jpg类型',
        'file.file' => '必须是文件',
        'file.mimes' => ':attribute类型必须是txt,excel,word,jpeg,bmp,png,pdf类型',

    ];
}

