<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
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
        parent::__construct($company);
        $this->query = $this->model::query()->withoutGlobalScope(CompanyScope::class);
    }

    /**
     * 创建或者更新信息
     * @param array $data
     * @return bool
     */
    public function createInfo(array $data): bool
    {
        $where = ['name' => $data['name']];
        if (!empty(auth()->user()->company_id)) {
            $where['id'] = ['<>', auth()->user()->company_id];
        }
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('公司名称已存在');
        }
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
