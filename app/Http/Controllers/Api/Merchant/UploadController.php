<?php
/**
 * 上传接口
 * User: long
 * Date: 2019/12/28
 * Time: 14:54
 */

namespace App\Http\Controllers\Api\Merchant;


use App\Http\Controllers\Controller;
use App\Services\Admin\UploadService;

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

    /**
     * 文件下载
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fileDownload()
    {
        return $this->service->fileDownload(request()->all());
    }
}
