<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\OrderTrailService;

/**
 * Class OrderTrailController
 * @package App\Http\Controllers\Api\Merchant
 * @property OrderTrailService $service
 */
class OrderTrailController extends BaseController
{
    /**
     * @var OrderTrailService
     */
    public $service;

    /**
     * OrderTrailController constructor.
     * @param OrderTrailService $service
     */
    public function __construct(OrderTrailService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \App\Exceptions\BusinessLogicException
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
    public function show($id)
    {
        return $this->service->show($id);
    }
}
