<?php
/**
 * 测试接口
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 14:31
 */

namespace App\Http\Controllers\Api;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\TestService;

/**
 * Class TestController
 * @package App\Http\Controllers\Api
 * @property TestService $service;
 */
class TestController extends BaseController
{
    public function __construct(TestService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新增
     * @throws BusinessLogicException
     */
    public function store()
    {

        return $this->service->create($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    public function destroy($id)
    {
        return $this->service->delete(['id' => $id]);
    }

    public function calDate()
    {
        return $this->service->calDate();
    }
}