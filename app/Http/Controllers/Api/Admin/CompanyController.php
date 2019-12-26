<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CompanyInfoResource;
use App\Services\Admin\CompanyService;
use Illuminate\Http\Request;

/**
 * 公司配置
 * Class OrderController
 * @package App\Http\Controllers\Api\Admin
 * @property CompanyService $service
 */
class CompanyController extends BaseController
{
    public function __construct(CompanyService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request)
    {
        return CompanyInfoResource::make($this->service->getCompanyInfo());
    }

    /**
     * 更新
     * @return array
     */
    public function update()
    {
        if ($this->service->createInfo($this->data)) {
            return success();
        }

        return failed();
    }
}