<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/3/12
 * Time: 18:29
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\CarModelService;

/**
 * Class CarModel
 * @package App\Http\Controllers\Api\Admin
 * @property CarModelService $service
 */
class CarModelController extends BaseController
{
    public function __construct(CarModelService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 通过品牌获取列表
     * @return array
     */
    public function getListByBrand()
    {
        return $this->service->getListByBrand($this->data);
    }

    /**
     * 新增
     *
     * @return \App\Services\BaseService|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAll($id)
    {
        return $this->service->getAll($id);

    }
}
