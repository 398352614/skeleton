<?php
/**
 * 上传 服务
 * User: long
 * Date: 2019/12/28
 * Time: 14:54
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Traits\ConstTranslateTrait;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadService
 * @package App\Services\Admin
 * @property FilesystemAdapter $imageDisk
 * @property FilesystemAdapter $fileDisk
 */
class UploadService
{
    protected $imageDisk;

    protected $fileDisk;

    public function __construct()
    {
        $this->imageDisk = Storage::disk('driver_image_public');
        $this->fileDisk = Storage::disk('driver_file_public');
    }

    /**
     * 获取图片目录
     * @param $dir
     * @return string
     * @throws BusinessLogicException
     */
    private function getImageDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$driverImageDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . auth()->id() . DIRECTORY_SEPARATOR . $dir;
    }

    /**
     * 获取文件目录
     * @param $dir
     * @return mixed
     * @throws BusinessLogicException
     */
    private function getFileDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$driverImageDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . auth()->id() . DIRECTORY_SEPARATOR . $dir;
    }


    /**
     * 获取唯一名称
     * @param $file
     * @return string
     */
    private function makeRuleName(UploadedFile $file)
    {
        return date('YmdHis') . uniqid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * 获得相对路径
     * @param string $url
     * @return string
     */
    protected function getRelativeUrl(string $url): string
    {
        return str_replace(config('app.url'), '', $url);
    }


    /**
     * 图片上传
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function imageUpload($params)
    {
        $subPath = $this->getImageDir($params['dir']);
        $params['name'] = $this->makeRuleName($params['image']);
        try {
            $rowCount = $this->imageDisk->putFileAs($subPath, $params['image'], $params['name']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('图片上传失败，请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('图片上传失败，请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->imageDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }


    /**
     * 获取图片目录列表
     * @return array
     */
    public function getImageDirList()
    {
        $data = array_values(collect(ConstTranslateTrait::driverImageDirList())->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
    }


    /**
     * 文件上传
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function fileUpload($params)
    {
        $subPath = $this->getFileDir($params['dir']);
        $params['name'] = $this->makeRuleName($params['file']);
        try {
            $rowCount = $this->fileDisk->putFileAs($subPath, $params['file'], $params['name']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('文件上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('文件上传失败,请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->fileDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }

    /**
     * 获取文件目录列表
     * @return array
     */
    public function getFileDirList()
    {
        $data = array_values(collect(ConstTranslateTrait::driverImageDirList())->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
    }

}
