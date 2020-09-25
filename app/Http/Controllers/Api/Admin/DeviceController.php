<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/3/12
 * Time: 18:29
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\DeviceService;

/**
 * Class DeviceController
 * @package App\Http\Controllers\Api\Admin
 * @property DeviceService $service
 */
class DeviceController extends BaseController
{
    public function __construct(DeviceService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    public function getDriverPageList()
    {
        return $this->service->getDriverPageList();
    }

    /**
     * 新增
     * @return \App\Services\BaseService|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 绑定
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function bind($id)
    {
        return $this->service->bind($id, $this->data['driver_id']);
    }

    /**
     * 解绑
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function unBind($id)
    {
        return $this->service->unBind($id);
    }

    /**
     * 删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

}
