<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * 获取公司信息
     * @return array|Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function show()
    {
        return $this->getInfo(['id' => auth()->user()->company_id], ['*'], false);
    }
}
