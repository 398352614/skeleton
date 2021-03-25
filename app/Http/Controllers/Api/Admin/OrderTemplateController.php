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

    public function show()
    {
        return $this->service->show();
    }

    /**
     * 修改
     * @param $id
     * @return void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update()
    {
        return $this->service->updateByCompanyId($this->data);
    }

    /**
     * 展示模板
     * @return array
     */
    public function init()
    {
        return $this->service->init();
    }

    /**
     * 修改默认模板
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function changeDefault()
    {
        return $this->service->changeDefault($this->data);
    }
}
