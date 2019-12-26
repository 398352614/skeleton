<?php

namespace App\Services\Admin;

use App\Models\Company;
use App\Models\Scope\CompanyScope;
use App\Services\BaseService;

/**
 * 公司配置服务
 * Class CompanyService
 * @package App\Services\Admin
 */
class CompanyService extends BaseService
{
    public function __construct(Company $company)
    {
        $this->model = $company;
        $this->query = $this->model::query()->withoutGlobalScope(CompanyScope::class);
    }

    /**
     * 创建或者更新信息
     * @param  array  $data
     * @return bool
     */
    public function createInfo(array $data): bool
    {
        return $this->query->updateOrCreate(
            [
                'id' => auth()->user()->company_id,
            ],
            [
                'name' => $data['name'] ?? '',
                'contacts' => $data['contacts'] ?? '',
                'phone' => $data['phone'] ?? '',
                'country' => $data['country'] ?? '',
                'address' => $data['address'] ?? '',
            ]
        ) !== 0;
    }

    /**
     * 获取公司信息
     * @return Company
     */
    public function getCompanyInfo(): Company
    {
        /** @var Company $info */
        $info = $this->getInfo(['id' => auth()->user()->company_id], ['*'], false);

        if (!$info) {
            return new $this->model();
        }

        return $info;
    }
}