<?php
/**
 * 异常管理
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\StockExceptionService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Admin
 * @property StockExceptionService $service
 */
class stockExceptionController extends BaseController
{
    public function __construct(StockExceptionService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }


    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 处理
     * @param $id
     * @return
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function deal($id)
    {
        return $this->service->deal($id, $this->data);
    }
}
