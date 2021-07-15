<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\TourService;
use App\Traits\TourRedisLockTrait;
use Illuminate\Http\Request;

/**
 * Class TourController
 * @package App\Http\Controllers\Api\merchant
 * @property TourService $service
 */
class TourController extends BaseController
{
    use TourRedisLockTrait;

    public function __construct(TourService $service)
    {
        //事务包裹和数据传入
        parent::__construct($service, ['updateBatchIndex', 'autoOpTour', 'dealCallback']);
    }


    /**
     * @api {GET}  api/admin/tour 管理员端:查询任务列表
     * @apiName index
     * @apiGroup admin-tour
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询任务列表
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询任务列表",
     *  "data":{}
     * }
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     * @api {GET}  api/admin/tour/{tour} 管理员端:查看具体任务
     * @apiName show
     * @apiGroup admin-tour
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查看具体任务
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查看具体任务",
     *  "data":{}
     * }
     */
    public function show($id)
    {
        return $this->service->getBatchCountInfo($id);
    }

    /**
     * @api {POST}  api/admin/tour/update-batch-index 管理员端:更新批次的派送顺序
     * @apiName update-batch-index
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 更新批次的派送顺序
     * @apiParam {String}   batch_ids                  有序的批次数组
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
        return $this->service->updateBatchIndex();
    }

    /**
     * @throws \App\Exceptions\BusinessLogicException
     * @api {POST}  api/admin/tour/auto-op-tour 管理员端:自动优化线路任务
     * @apiName auto-op-tour
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 自动优化线路任务
     * @apiParam {String}   tour_no                    在途编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function autoOpTour()
    {
        return $this->service->autoOpTour();
    }

    /**
     * @throws \App\Exceptions\BusinessLogicException
     * @throws \Throwable
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

    /**
     * @api {GET}  api/admin/tour/unlock-redis 管理员端:取消 redis 锁
     * @apiName unlock-redis
     * @apiGroup admin
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 取消 redis 锁
     * @apiParam {String}   tour_no                    在途编号
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function unlockRedis(Request $request)
    {
        self::setTourLock($request->tour_no, 0);
        return '1';
    }
}
