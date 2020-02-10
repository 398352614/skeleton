<?php

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Services\BaseConstService;
use Illuminate\Http\Request;
use Psy\Formatter\Formatter;

class RouteTrackingController extends BaseController
{
    /**
     * @api {POST}  api/driver/route-tracking/collect 手持端:采集司机地址
     * @apiName collect
     * @apiGroup route-tracking
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 采集司机地址
     * @apiParam {String}   lon                    经度
     * @apiParam {String}   lat                    纬度
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"采集成功",
     *  "data":{}
     * }
     */
    public function collect(Request $request)
    {
        $payload = $request->validate([
            'lon'   => ['required', 'string'],
            'lat'   => ['required', 'string'],
        ]);

        $driverID = $request->user()->id;

        $tour = Tour::where('driver_id', $driverID)->where('status', BaseConstService::TOUR_STATUS_4)->first();

        throw_unless($tour != null, new BusinessLogicException('当前司机不存在派送中线路'));

        RouteTracking::create([
            'lon' => $payload['lon'],
            'lat' => $payload['lat'],
            'tour_no'   => $tour->tour_no,
            'driver_id' => $driverID,
        ]);

        return success('采集成功');
    }
}
