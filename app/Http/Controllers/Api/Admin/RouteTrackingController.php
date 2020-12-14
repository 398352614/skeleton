<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\RouteTrackingService;
use Illuminate\Http\Request;

/**
 * Class RouteTrackingController
 * @package App\Http\Controllers\Api\Admin
 * @property RouteTrackingService $service
 */
class RouteTrackingController extends BaseController
{
    public function __construct(RouteTrackingService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @return
     * @throws BusinessLogicException
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"拉取成功",
     *  "data":{}
     * }
     * @api {POST}  api/admin/route-tracking/route 管理员端:获取已收集的司机的路线
     * @apiName route
     * @apiGroup route-tracking
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 获取已收集的司机的路线
     * @apiParam {String}   driver_id                    司机 id
     * @apiParam {String}   tour_no                      在途路线编号 -- 两参数二选一
     * @apiSuccessExample {json}  返回示例
     */
    public function show(Request $request)
    {
        return $this->service->show();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function index()
    {
        return $this->service->index();
    }
}
