<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Admin\BatchService;
use Illuminate\Http\Request;

/**
 * Class BatchController
 * @package App\Http\Controllers\Api\Admin
 * @property BatchService $service
 */
class BatchController extends BaseController
{
    public $service;

    public function __construct(BatchService $service)
    {
        parent::__construct($service);
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
     * @throws BusinessLogicException
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询批次详情",
     *  "data":{}
     * }
     * @api {GET}  api/admin/batch/{batch} 管理员端:查询批次详情
     * @apiName show
     * @apiGroup admin-batch
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询批次详情
     * @apiSuccessExample {json}  返回示例
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 取消取派
     * @param $id
     * @throws BusinessLogicException
     */
    public function cancel($id)
    {
        return $this->service->cancel($id, $this->data);
    }

    /**
     * 获取取件线路列表
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getTourList($id)
    {
        return $this->service->getTourListByBatch($id, $this->data);
    }

    /**
     * 分配站点至取件线路
     * @param $id
     * @throws BusinessLogicException
     */
    public function assignToTour($id)
    {
        return $this->service->assignToTour($id, $this->data);
    }

    /**
     * 移除站点
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeFromTour($id)
    {
        return $this->service->removeFromTour($id);
    }

    /**
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getTourDate($id){
        return $this->service->getTourDate($id);
    }

    /**
     * @param $id
     * @param $lineId
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getLineDate($id,$lineId){
        return $this->service->getLineDate($id,$lineId);

    }
}
