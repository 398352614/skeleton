<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Admin\TourService;
use Illuminate\Http\Request;

/**
 * Class TourController
 * @package App\Http\Controllers\Api\Admin
 * @property TourService $service
 */
class TourController extends BaseController
{
    public function __construct(TourService $service)
    {
        //事务包裹和数据传入
        parent::__construct($service, ['updateBatchIndex', 'autoOpTour']);
    }

    /**
     * @api {GET}  api/admin/tour 管理员端:查询任务列表
     * @apiName index
     * @apiGroup admin-tour
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询任务列表
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询任务列表",
     *  "data":{}
     * }
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @api {GET}  api/admin/tour/{tour} 管理员端:查看具体任务
     * @apiName show
     * @apiGroup admin-tour
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查看具体任务
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查看具体任务",
     *  "data":{}
     * }
     */
    public function show($id)
    {
        return $this->service->getBatchCountInfo($id);
    }

    /**
     * @api {POST}  api/admin/tour/update-batch-index 管理员端:更新批次的派送顺序
     * @apiName update-batch-index
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 更新批次的派送顺序
     * @apiParam {String}   batch_ids                  有序的批次数组
     * @apiParam {String}   tour_no                    在途编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function updateBatchIndex()
    {
        return $this->service->updateBatchIndex();
    }

    /**
     * @api {POST}  api/admin/tour/auto-op-tour 管理员端:自动优化线路任务
     * @apiName auto-op-tour
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 自动优化线路任务
     * @apiParam {String}   tour_no                    在途编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function autoOpTour()
    {
        return $this->service->autoOpTour();
    }

    /**
     * @api {GET}  api/admin/tour/callback 管理员端:服务端回调更新完成
     * @apiName callback
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 服务端回调更新完成
     * @apiParam {String}   tour_no                在途标识
     * @apiParam {String}   type                   更新类型  有初始化线路,更新司机位置,更新线路
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function callback()
    {
        return $this->service->dealCallback();
    }

    /**
     * 分配司机
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function assignDriver($id)
    {
        return $this->service->assignDriver($id, $this->data);
    }

    /**
     * 取消司机分配
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function cancelAssignDriver($id)
    {
        return $this->service->cancelAssignDriver($id);
    }

    /**
     * 分配车辆
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function assignCar($id)
    {
        return $this->service->assignCar($id, $this->data);
    }

    /**
     * 取消车辆分配
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function cancelAssignCar($id)
    {
        return $this->service->cancelAssignCar($id);
    }

    /**
     * 取消锁定
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function unlock($id)
    {
        return $this->service->unlock($id);
    }
}
