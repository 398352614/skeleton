<?php

namespace App\Services\Consumer;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Scope\CompanyScope;
use App\Services\BaseService;
use Illuminate\Support\Facades\Artisan;

/**
 * 公司配置服务
 * Class CompanyService
 * @package App\Services\Admin
 */
class CompanyService extends BaseService
{
    public function __construct(Company $company)
    {
        parent::__construct($company);
        $this->query = $this->model::query()->withoutGlobalScope(CompanyScope::class);
    }
}
