<?php
/**
 * 运单 接口
 * Created by PhpStorm
 * User: long
 * Date: 2020/10/20
 * Time: 16:37
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\TrackingOrderService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property TrackingOrderService $service
 */
class TrackingOrderController extends BaseController
{
    public function __construct(TrackingOrderService $service)
    {
        parent::__construct($service);
    }


    /**
     * 查询初始化
     * @return array
     */
    public function initIndex()
    {
        return $this->service->initIndex();
    }

    /**
     * 运单统计
     * @return array
     * @throws BusinessLogicException
     */
    public function trackingOrderCount()
    {
        return $this->service->trackingOrderCount($this->data);
    }

    /**
     * 线路列表
     * @return array|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getLineList()
    {
        return $this->service->getLineList();
    }

    public function index()
    {
        return $this->service->getPageList();
    }

}
