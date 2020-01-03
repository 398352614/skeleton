<?php
/**
 * 线路 接口
 * User: long
 * Date: 2019/12/21
 * Time: 10:04
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\LineService;

/**
 * Class LineController
 * @package App\Http\Controllers\Api\Admin
 * @property LineService $service
 */
class LineController extends BaseController
{
    public function __construct(LineService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }


    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
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