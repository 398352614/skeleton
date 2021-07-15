<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Services\Driver\HomeService;

/**
 * Class HomeController
 * @package App\Http\Controllers\Api\Admin
 * @property HomeService $service
 */
class HomeController extends BaseController
{
    public function __construct(HomeService $service)
    {
        parent::__construct($service);
    }

    /**
     * 主页
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function home()
    {
        return $this->service->home();
    }


}
