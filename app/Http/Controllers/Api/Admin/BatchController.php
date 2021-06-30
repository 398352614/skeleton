<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\BatchService;
use Illuminate\Support\Arr;

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
        return $this->service->cancel($id);
    }

    /**
     * 获取线路任务列表
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getTourList($id)
    {
        return $this->service->getTourListByBatch($id, $this->data);
    }

    /**
     * 分配站点至线路任务
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function assignToTour($id)
    {
        return $this->service->assignToTour($id, $this->data);
    }

    /**
     * 批量分配站点至线路任务
     * @return string
     * @throws BusinessLogicException
     */
    public function assignListToTour()
    {
        $idList = $this->data['id_list'];
        $data = Arr::except($this->data, ['id_list']);
        return $this->service->assignListToTour($idList, $data);
    }

    /**
     * 移除站点
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function removeFromTour($id)
    {
        return $this->service->removeFromTour($id);
    }

    /**
     * 批量移除站点
     * @return mixed
     * @throws BusinessLogicException
     */
    public function removeListFromTour()
    {
        return $this->service->removeListFromTour($this->data['id_list']);
    }

    /**
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getDateList($id)
    {
        if (!empty($this->data['line_id'])) {
            return $this->service->getLineDate($id, $this->data);
        } else {
            return $this->service->getTourDate($id);
        }
    }


    public function getLineList()
    {
        return $this->service->getLineList();

    }
}
