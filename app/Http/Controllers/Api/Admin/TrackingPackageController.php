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
use App\Services\Admin\TrackingPackageService;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Driver
 * @property TrackingPackageService $service
 */
class TrackingPackageController extends BaseController
{
    public function __construct(TrackingPackageService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }
}
