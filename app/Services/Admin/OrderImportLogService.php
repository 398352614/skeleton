<?php
/**
 * Created by PhpStorm
 * User: Yomi
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Admin;


use App\Http\Resources\OrderImportLogResource;
use App\Models\OrderImportLog;
use App\Services\BaseService;

class OrderImportLogService extends BaseService
{
    public function __construct(OrderImportLog $orderImportLog)
    {
        $this->model = $orderImportLog;
        $this->query = $this->model::query();
        $this->resource = OrderImportLogResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    public function showDetail($id){
        $info =$this->query->where('id',$id)->value('log');
        return $info;
    }
}
