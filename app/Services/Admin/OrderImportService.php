<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


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

    public function template(){
        return Storage::disk('admin_file_public')->url();
    }


    public function showDetail($id){
        $info =$this->query->where('id',$id)->value('log');
        return $info;
    }
}
