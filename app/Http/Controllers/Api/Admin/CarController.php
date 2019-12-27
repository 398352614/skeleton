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
     * @api {GET}  api/admin/car/{car} 管理员端:查询车辆详情
     * @apiName show
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询车辆详情
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }

    /**
     * @api {PUT}  api/admin/car/{car} 管理员端:车辆编辑
     * @apiName update
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆编辑
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function update(Request $request, $id)
    {
        return $this->service->updateCar($id);
    }

    /**
     * @api {DELETE}  api/admin/car/{car} 管理员端:车辆删除
     * @apiName destroy
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆删除
     * @throws BusinessLogicException
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function destroy(Request $request, $id)
    {
        return $this->service->destroy($id);
    }

    /**
     * @api {POST}  api/admin/car/lock 管理员端:车辆锁定
     * @apiName lock
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆锁定
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function lock(Request $request)
    {
        $payload = $this->validate($request, [
            'car_id' => 'required|integer|min:1',
            'is_locked' => 'required|boolean',
        ]);
        return Car::where('id', $payload['car_id'])->update(['is_locked' => $payload['is_locked']]);
    }

    /**
     * @api {GET}  api/admin/car/brands 管理员端:获取车辆品牌
     * @apiName brands
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
    public function getBrands()
    {
        return $this->brandService->getPageList();
    }

    /**
     * @api {POST}  api/admin/car/addbrand 管理员端:添加车辆品牌
     * @apiName addbrand
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 添加车辆品牌
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"车辆编辑",
     *  "data":{}
     * }
     */
    public function addBrand(Request $request)
    {
        return $this->brandService->create($request->all());
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
        return $this->modelService->getPageList();
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
