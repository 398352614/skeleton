<?php
/**
 * 异常管理
 * User: long
 * Date: 2019/12/31
 * Time: 12:44
 */

namespace App\Http\Controllers\Api\Driver;


use App\Http\Controllers\BaseController;
use App\Services\Driver\BatchExceptionService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Driver
 * @property BatchExceptionService $service
 */
class BatchExceptionController extends BaseController
{
    public function __construct(BatchExceptionService $service)
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
}