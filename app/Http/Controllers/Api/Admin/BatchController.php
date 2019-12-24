<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BatchService;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public $service;

    public function __construct(BatchService $service)
    {
        $this->service = $service;
    }

    /**
     * @api {GET}  api/admin/batch 管理员端:查询批次列表
     * @apiName index
     * @apiGroup admin-batch
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询批次列表
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询批次列表",
     *  "data":{}
     * }
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @api {GET}  api/admin/batch/{batch} 管理员端:查询批次详情
     * @apiName show
     * @apiGroup admin-batch
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询批次详情
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询批次详情",
     *  "data":{}
     * }
     */
    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }
}
