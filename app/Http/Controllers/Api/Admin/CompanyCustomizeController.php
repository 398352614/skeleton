<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Admin\CompanyInfoResource;
use App\Services\Admin\CompanyCustomizeService;
use App\Services\Admin\CompanyService;
use Illuminate\Http\Request;

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

    /**
     * 修改
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update()
    {
        if(!empty($this->data['admin_url']) && !empty($this->service->getInfo(['admin_url'=>$this->data['admin_url']],['*'],false))){
            throw new BusinessLogicException('该网址已被占用');
        }
        return $this->service->update(['company_id' => auth()->user()->company_id], $this->data);
    }
}
