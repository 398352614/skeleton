<?php
/**
 * 任务报告 接口
 * User: long
 * Date: 2019/12/25
 * Time: 18:01
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\ReportService;

/**
 * Class ReportController
 * @package App\Http\Controllers\Api\Admin
 * @property ReportService $service
 */
class ReportController extends BaseController
{
    public function __construct(ReportService $service)
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