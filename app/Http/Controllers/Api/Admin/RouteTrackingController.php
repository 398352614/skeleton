<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Services\Admin\RouteTrackingService;
use App\Services\BaseConstService;
use Illuminate\Http\Request;
use Psy\Formatter\Formatter;

class RouteTrackingController extends BaseController
{
    public function __construct(RouteTrackingService $service)
    {
        parent::__construct($service);
    }

    /**
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
    public function route(Request $request)
    {
        $payload = $request->validate([
            'driver_id' => ['nullable'],
            'tour_no'   => ['required_without:driver_id'], // 两字段必须存在一个
        ]);
        $tour = null;
        if ($payload['driver_id'] ?? null) {
            $tour = Tour::where('driver_id', $payload['driver_id'])->first();
        } else {
            $tour = Tour::where('tour_no', $payload['tour_no'])->first();
        }
        if (!$tour) {
            throw new BusinessLogicException('没找到相关线路');
        }

        $routeTracking = $tour->routeTracking->sortBy('created_at');

        return success('', [
            'driver'                => $tour->driver,
            'route_tracking'        =>  $routeTracking,
            'time_consuming'        =>  '',
            'distance_consuming'    =>  '',
            'tour_event'            =>  $tour->tourDriverEvent,
        ]);
    }
}
