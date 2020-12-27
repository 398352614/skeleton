<?php
/**
 * 订单轨迹
 */

namespace App\Http\Controllers\api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\OrderTrailService;

/**
 * Class OrderTrailController
 * @package App\Http\Controllers\api\admin
 * @property OrderTrailService $service
 */
class OrderTrailController extends BaseController
{
    public function __construct(OrderTrailService $service)
    {
        parent::__construct($service);
    }

    public function index($orderNo)
    {
        return $this->service->index($orderNo);
    }
}
