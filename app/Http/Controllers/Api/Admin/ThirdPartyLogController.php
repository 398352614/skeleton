<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\BaseController;
use App\Services\ThirdPartyLogService;

/**
 * Class ThirdPartyLogController
 * @package App\Http\Controllers\api\admin
 * @property ThirdPartyLogService $service
 */
class ThirdPartyLogController extends BaseController
{
    public function __construct(ThirdPartyLogService $service)
    {
        parent::__construct($service);
    }

    /**
     * 第三方日志
     * @param $id
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function index($id)
    {
        return $this->service->index($id);
    }
}
