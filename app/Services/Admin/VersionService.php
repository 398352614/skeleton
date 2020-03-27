<?php

/**
 * 版本控制服务
 */
namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\VersionResource;
use App\Models\version;
use App\Services\BaseService;

class VersionService extends BaseService
{
    public function __construct(version $version)
    {
        parent::__construct($version, VersionResource::class);
    }

    /**
     * 获取上传服务
     * @return mixed
     */
    public function getUploadService()
    {
        return self::getInstance(UploadService::class);
    }

    /**
     * 新增版本
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function store($data){
        $data['dir']='package';
        $file=$this->getUploadService()->fileUpload($data);
        return parent::create([
            'company_id'=>auth()->user()->id,
            'uploader_email'=>auth()->user()->email,
            'name'=>$data['name']??'TMS',
            'url'=>$file['path'],
            'version'=>$data['version'],
            'change_log'=>$data['change_log']??'',
            'status'=>$data['status']??1,
        ]);
    }

    /**
     * 更新版本
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

}
