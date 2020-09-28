<?php
/**
 * 设备
 * User: long
 * Date: 2020/6/28
 * Time: 15:49
 */

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\DeviceService;

/**
 * Class FeeController
 * @package App\Http\Controllers\Api\Driver
 * @property DeviceService $service
 */
class DeviceController extends BaseController
{
    public function __construct(DeviceService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    public function show()
    {
        return $this->service->show();
    }

    /**
     * 绑定
     * @return string
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function bind()
    {
        return $this->service->bind($this->data);
    }

    /**
     * 解绑
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function unBind()
    {
        return $this->service->unBind();
    }

}