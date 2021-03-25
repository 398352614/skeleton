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
     * 创建或修改
     * @param $id
     * @return bool|int
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }
}
