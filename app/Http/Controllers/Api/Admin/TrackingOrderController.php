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
}
