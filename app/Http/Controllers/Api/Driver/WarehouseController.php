<?php
/**
 * 网点管理
 * User: long
 * Date: 2019/12/25
 * Time: 15:16
 */

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\WareHouseService;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WarehouseController
 * @package App\Http\Controllers\Api\Admin
 * @property WareHouseService $service
 */
class WarehouseController extends BaseController
{
    /**
     * WareHouseController constructor.
     * @param  WareHouseService  $service
     */
    public function __construct(WareHouseService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 网点首页
     * @return array
     */
    public function home()
    {
        return $this->service->home();
    }

}
