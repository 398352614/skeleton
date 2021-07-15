<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:22
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\OrderTemplateService;

/**
 * Class PrintTemplateController
 * @package App\Http\Controllers\Api\Admin
 * @property OrderTemplateService $service
 */
class OrderTemplateController extends BaseController
{
    public function __construct(OrderTemplateService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 展示模板
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 修改默认模板
     * @param $id
     * @return void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function changeDefault($id)
    {
        return $this->service->changeDefault($id);
    }
}
