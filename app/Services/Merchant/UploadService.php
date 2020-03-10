<?php
/**
 * 上传 服务
 * User: long
 * Date: 2019/12/28
 * Time: 14:54
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Exports\BatchListExport;
use App\Models\Driver;
use App\Models\Employee;
use App\Traits\ConstTranslateTrait;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadService
 * @package App\Services\Merchant
 * @property FilesystemAdapter $imageDisk
 * @property FilesystemAdapter $fileDisk
 */
class UploadService
{
    protected $imageDisk;

    protected $fileDisk;

    protected $excelDisk;

    public function __construct()
    {
        $this->imageDisk = Storage::disk('admin_image_public');
        $this->fileDisk = Storage::disk('admin_file_public');
        $this->excelDisk = Storage::disk('admin_excel_public');
        $this->txtDisk =Storage::disk('admin_txt_public');

    }

    /**
     * 获取图片目录
     * @param $dir
     * @return string
     * @throws BusinessLogicException
     */
    private function getImageDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$adminImageDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
    }

    /**
     * 获取文件目录
     * @param $dir
     * @return mixed
     * @throws BusinessLogicException
     */
    private function getFileDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$adminFileDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
    }


    /**
     * 获取表格目录
     * @param $dir
     * @return mixed
     * @throws BusinessLogicException
     */
    private function getExcelDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$adminExcelDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
    }

    /**
     * 获取文档目录
     * @param $dir
     * @return mixed
     * @throws BusinessLogicException
     */
    private function getTxtDir($dir)
    {
        if (!array_key_exists($dir, ConstTranslateTrait::$adminTxtDirList)) {
            throw new BusinessLogicException('没有对应目录');
        }
        return auth()->user()->company_id . DIRECTORY_SEPARATOR . $dir;
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
     * 文档上传
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function txtUpload($params){
        $subPath = $this->getTxtDir($params['dir']);
        $params['name'] = date('Ymd') . $params['name'].'.txt';
        try {
            $rowCount = $this->txtDisk->put($subPath.DIRECTORY_SEPARATOR.$params['name'],$params['txt']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('文档上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('文档上传失败,请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->txtDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }
    /**
     * 表格上传
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function excelUpload($params)
    {
        $subPath = $this->getExcelDir($params['dir']);
        $params['name'] = date('Ymd') . $params['name'];
        $path ='public\\Merchant\\excel\\'.$subPath . DIRECTORY_SEPARATOR . $params['name'].'.xlsx';
        try {
            $rowCount=Excel::store(new BatchListExport($params['name'],$params['excel']),$path);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('表格上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('表格上传失败,请重新操作');
        }
        return [
            'name' => $params['name'].'.xlsx',
            'path' => $this->excelDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'].'.xlsx')
        ];
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
            throw new BusinessLogicException('图片上传失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('图片上传失败,请重新操作');
        }
        return [
            'name' => $params['name'],
            'path' => $this->imageDisk->url($subPath . DIRECTORY_SEPARATOR . $params['name'])
        ];
    }

    /**
     * 图片下载
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function imageDownload($params)
    {
        $subPath = $this->getImageDir($params['dir']);
        $params['name'] = date('Ymd') . $params['name'].'.png';
        try {
            $rowCount = $this->imageDisk->put($subPath.DIRECTORY_SEPARATOR.$params['name'],$params['image']);
        } catch (\Exception $ex) {
            throw new BusinessLogicException('图片获取失败,请重新操作');
        }
        if ($rowCount === false) {
            throw new BusinessLogicException('图片获取失败,请重新操作');
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
        $data = array_values(collect(ConstTranslateTrait::adminImageDirList())->map(function ($value, $key) {
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
        $data = array_values(collect(ConstTranslateTrait::adminFileDirList())->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
    }

}
