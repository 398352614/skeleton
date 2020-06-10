<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/28
 * Time: 10:22
 */

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\BaseController;
use App\Services\Admin\PrintTemplateService;

/**
 * Class PrintTemplateController
 * @package App\Http\Controllers\Api\Admin
 * @property PrintTemplateService $service
 */
class PrintTemplateController extends BaseController
{
    public function __construct(PrintTemplateService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        return $this->service->init();
    }


    public function show()
    {
        return $this->service->show();
    }

    /**
     * 创建或修改
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update()
    {
        return $this->service->createOrUpdate($this->data);
    }
}