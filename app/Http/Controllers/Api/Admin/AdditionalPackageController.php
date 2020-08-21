<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\AdditionalPackageService;

class AdditionalPackageController extends BaseController
{
    public function __construct(AdditionalPackageService $service)
    {
        parent::__construct($service);
    }

    public function index(){
        return $this->service->getPageList();
    }
}
