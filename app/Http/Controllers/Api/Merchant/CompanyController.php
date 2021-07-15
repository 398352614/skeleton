<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Api\Admin\CompanyInfoResource;
use App\Services\Merchant\CompanyService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * 公司管理
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

    /**
     * 更新
     * @return array|Builder|Model|object
     */
    public function show()
    {
        return $this->service->show();
    }
}
