<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\OrderImportService;

/**
 * Class OrderImportController
 * @package App\Http\Controllers\Api\Merchant
 * @property OrderImportService $service
 */
class OrderImportController extends BaseController
{
    public function __construct(OrderImportService $service)
    {
        parent::__construct($service);
    }


    /**
     * 获取导入模板
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function templateExport(){
        return $this->service->templateExport();
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
        return $this->service->getInfo(['id'=>$id],['*'],true);
    }

    /**
     * 订单导入
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function import()
    {
        return $this->service->import($this->data);
    }


    /**
     * 导入检查
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function importCheck()
    {
        return $this->service->importCheck($this->data);
    }

    /**
     * 批量新增
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function createByList()
    {
        return $this->service->createByList($this->data);
    }

}
