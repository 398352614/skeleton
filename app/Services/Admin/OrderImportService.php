<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\OrderImportResource;
use App\Models\OrderImportLog;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class OrderImportService extends BaseService
{
    public function __construct(OrderImportLog $orderImportLog)
    {
        $this->model = $orderImportLog;
        $this->query = $this->model::query();
        $this->resource = OrderImportResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * 上传模板
     * @param $data
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function uploadTemplate()
    {
        $data=$this->formData;
            $data['dir']='template';
            if(empty((new UploadService())->fileUpload($data))){
                throw new BusinessLogicException('上传失败');
            };
    }

    /**
     *下载模板
     * @return mixed
     */
    public function getTemplate(){
        return Storage::disk('admin_file_public')->url(auth()->user()->company_id . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'order_import_template.xlsx');
    }


    public function showDetail($id){
        $info =$this->query->where('id',$id)->value('log');
        return $info;
    }
}
