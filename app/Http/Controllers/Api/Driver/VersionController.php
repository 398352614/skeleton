<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\BaseController;
use App\Models\Version;
use App\Services\Admin\VersionService;

class VersionController extends BaseController
{
    public function __construct(VersionService $service)
    {
        parent::__construct($service);
    }

    public function check()
    {
        //找到最新的版本并返回
        $info = Version::query()->orderBy('version', 'desc')->first();
        return $info;
    }


}
