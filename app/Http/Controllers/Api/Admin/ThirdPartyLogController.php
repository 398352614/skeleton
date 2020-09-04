<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\BaseController;
use App\Services\ThirdPartyLogService;

/**
 * Class ThirdPartyLogController
 * @package App\Http\Controllers\api\admin
 * @property ThirdPartyLogService ThirdPartyLogService
 */
class ThirdPartyLogController extends BaseController
{
    public function __construct(ThirdPartyLogService $service)
    {
        parent::__construct($service);
    }

    public function index($id)
    {
        return $this->service->index($id);
    }
}
