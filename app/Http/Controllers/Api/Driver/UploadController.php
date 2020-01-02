<?php
/**
 * 上传接口
 * User: long
 * Date: 2019/12/28
 * Time: 14:54
 */

namespace App\Http\Controllers\Api\Driver;


use App\Http\Controllers\Controller;
use App\Services\Driver\UploadService;

/**
 * Class UploadController
 * @package App\Http\Controllers\Api\Admin
 * @property UploadService $service
 */
class UploadController extends Controller
{
    public function __construct(UploadService $service)
    {
        $this->service = $service;
    }

    /**
     * 获取图片目录列表
     * @return array
     */
    public function getImageDirList()
    {
        return $this->service->getImageDirList();
    }

    /**
     * 图片上传
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function imageUpload()
    {
        return $this->service->imageUpload(request()->all());
    }


    /**
     * 获取文件上传目录列表
     * @return array
     */
    public function getFileDirList()
    {
        return $this->service->getFileDirList();
    }

    /**
     * 文件上传
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function fileUpload()
    {
        return $this->service->fileUpload(request()->all());
    }
}