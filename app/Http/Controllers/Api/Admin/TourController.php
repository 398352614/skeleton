<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Admin\TourService;
use Illuminate\Http\Request;

/**
 * 在途的相关操作,目前设想是采用 redis 进行加锁.日志只是记录操作,不进行是否已完成标记
 */
class TourController extends BaseController
{
    /**
     * @var TourService
     */
    protected $service;

    public function __construct(TourService $service)
    {
        $this->service = $service;
        //事务包裹和数据传入
        // parent::__construct($service);
    }

    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }

    /**
     * @api {POST}  api/admin/tour/update-batch-index 管理员端:更新批次的派送顺序
     * @apiName update-batch-index
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 更新批次的派送顺序
     * @apiParam {String}   batch_ids                  有序的批次数组
     * @apiParam {String}   driver_location            司机当前位置
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
        return $this->service->dealCallback();
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
}
