<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderImportService;
use Illuminate\Http\Request;

class OrderImportController extends BaseController
{
    public function __construct(OrderImportService $service)
    {
        $this->service = $service;
    }

    /**
     * 上传导入模板
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function uploadTemplate(){
        return $this->service->uploadTemplate();
    }
    /**
     * 获取导入模板
     * @return mixed
     */
    public function getTemplate(){
        return $this->service->getTemplate();
    }

    /**
     * 订单导入记录查询
     * @return mixed
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 订单导入记录详情
     * @param $id
     * @return mixed
     */
    public function show($id){
        return $this->service->showDetail($id);
    }

}
