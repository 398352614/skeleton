<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\TrackingOrderTrailService;
use Illuminate\Http\Request;

class OrderTrailController extends BaseController
{
    /**
     * @var TrackingOrderTrailService
     */
    public $service;

    public function __construct(TrackingOrderTrailService $service)
    {
        $this->service = $service;
    }

    /**
     * @api {GET}  api/admin/ order-trail 管理员端:车辆列表
     * @apiName index
     * @apiGroup admin-car
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 车辆列表
     * @apiParam {String}   order_no         需要查看的订单编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function index()
    {
        return $this->service->getTrackingNoPageList();
    }
}
