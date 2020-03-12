<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\Admin\CarBrandService;
use App\Services\Admin\CarModelService;
use App\Services\Admin\CarService;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    public $service;

    public $brandService;

    public $modelService;

    public function __construct(
        CarService $service,
        CarBrandService $brandService,
        CarModelService $modelService
    )
    {
        $this->service = $service;
        $this->brandService = $brandService;
        $this->modelService = $modelService;
    }

    /**
     * @api {GET}  api/admin/car 管理员端:车辆列表
     * @apiName index
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆列表
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function index(Request $request)
    {
        return $this->service->getPageList();
    }


    public function init()
    {
        return $this->service->init();
    }


    /**
     * @api {POST}  api/admin/car 管理员端:车辆新增
     * @apiName index
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆新增
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function store(Request $request)
    {
        return $this->service->store();
    }

    /**
     * @throws
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     * @api {GET}  api/admin/car/{car} 管理员端:查询车辆详情
     * @apiName show
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询车辆详情
     * @apiSuccessExample {json}  返回示例
     */
    public function show($id)
    {
        $info = $this->service->getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * @throws BusinessLogicException
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     * @api {PUT}  api/admin/car/{car} 管理员端:车辆编辑
     * @apiName update
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆编辑
     */
    public function update(Request $request, $id)
    {
        return $this->service->updateCar($id);
    }

    /**
     * @throws BusinessLogicException
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     * @api {DELETE}  api/admin/car/{car} 管理员端:车辆删除
     * @apiName destroy
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆删除
     */
    public function destroy(Request $request, $id)
    {
        return $this->service->destroy($id);
    }

    /**
     * @throws BusinessLogicException
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     * @api {POST}  api/admin/car/lock 管理员端:车辆锁定
     * @apiName lock
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆锁定
     */
    public function lock(Request $request, $id)
    {
        return $this->service->lock($id, $request->input('is_locked'));
    }

    /**
     * @api {GET}  api/admin/car/models 管理员端:获取车辆模型
     * @apiName models
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 获取车辆品牌
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function getModels()
    {
        return $this->modelService->getList();
    }

    /**
     * @api {POST}  api/admin/car/addmodel 管理员端:添加车辆型号
     * @apiName addmodel
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 添加车辆型号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function addModel(Request $request)
    {
        return $this->modelService->create($request->all());
    }

}
