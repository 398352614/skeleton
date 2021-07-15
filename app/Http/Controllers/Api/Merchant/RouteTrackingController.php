<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\RouteTrackingService;
use Illuminate\Http\Request;


class RouteTrackingController extends BaseController
{
    /**
     * RouteTrackingController constructor.
     * @param RouteTrackingService $service
     */
    public function __construct(RouteTrackingService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @return
     * @api {POST}  api/admin/route-tracking/route 管理员端:获取已收集的司机的路线
     * @apiName route
     * @apiGroup route-tracking
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 获取已收集的司机的路线
     * @apiParam {String}   driver_id                    司机 id
     * @apiParam {String}   tour_no                      在途路线编号 -- 两参数二选一
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"拉取成功",
     *  "data":{}
     * }
     */
    public function show()
    {
        return $this->service->show();
    }

    public function index()
    {
        return $this->service->index();
    }
}
