<?php

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\RouteTracking;
use App\Models\Tour;
use App\Services\Driver\RouteTrackingService;
use App\Services\BaseConstService;
use Illuminate\Http\Request;

class RouteTrackingController extends BaseController
{

    public function __construct(RouteTrackingService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Throwable
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
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 批量采集位置
     * @return mixed
     */
    public function storeByList(){
        return $this->service->createBylist($this->data);
    }
}
