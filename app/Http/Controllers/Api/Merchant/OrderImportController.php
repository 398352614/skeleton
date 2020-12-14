<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
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
        $this->service = $service;
    }

    /**
     * 上传导入模板
     * @throws BusinessLogicException
     */
    public function uploadTemplate()
    {
        return $this->service->uploadTemplate();
    }

    /**
     * 获取导入模板（无效）
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->service->getTemplate();
    }

    /**
     * 获取导入模板
     * @return array
     * @throws BusinessLogicException
     */
    public function templateExport()
    {
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
    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }

}
