<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\CompanyCustomizeService;

/**
 * 公司管理
 * Class CompanyCustomizeController
 * @package App\Http\Controllers\Api\Admin
 * @property CompanyCustomizeService $service
 */
class CompanyCustomizeController extends BaseController
{
    public function __construct(CompanyCustomizeService $service)
    {
        parent::__construct($service);
    }

    /**
     *
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show()
    {
        return $this->service->show();
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function showByUrl()
    {
        return $this->service->showByUrl();
    }

}
