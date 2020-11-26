<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/1
 * Time: 17:00
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\TourDelayService;

/**
 * Class TourDriverController
 * @package App\Http\Controllers\Api\Admin
 * @property TourDelayService $service
 */
class TourDelayController extends BaseController
{
    public function __construct(TourDelayService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取字典
     * @return mixed
     */
    public function init()
    {
        return $this->service->init();
    }
}
