<?php
/**
 * 下载 服务
 * User: Yomi
 * Date: 2020/2/21
 * Time: 18:56
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

/**
 * Class DownloadloadService
 * @package App\Services\Admin
 */
class DownloadService
{
    protected $imageDisk;

    public function __construct()
    {
        $this->imageDisk = Storage::disk('admin_image_public');
    }

    /**
     * 图片下载
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function imageDownload($params)
    {
        $subPath = auth()->user()->company_id . DIRECTORY_SEPARATOR . $params['dir'];
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

}
