<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Scope\CompanyScope;
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
     * 创建或者更新信息
     * @param array $data
     * @return bool
     * @throws BusinessLogicException
     */
    public function createInfo(array $data)
    {
        $where = ['name' => $data['name']];
        if (!empty(auth()->user()->company_id)) {
            $where['id'] = ['<>', auth()->user()->company_id];
        }
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('公司名称已存在');
        }
        $rowCount = $this->query->updateOrCreate(
                [
                    'id' => auth()->user()->company_id,
                ],
                [
                    'name'              => $data['name'] ?? '',
                    'contacts'          => $data['contacts'] ?? '',
                    'phone'             => $data['phone'] ?? '',
                    'country'           => $data['country'] ?? '',
                    'address'           => $data['address'] ?? '',
                    'lon'               => $data['lon'] ?? '',
                    'lat'               => $data['lat'] ?? '',
                    'web_site'          => $data['web_site'] ?? '',
                    'system_name'       => $data['system_name'] ?? '',
                    'logo_url'          => $data['logo_url'] ?? '',
                    'login_logo_url'    => $data['login_logo_url'] ?? '',
                ]
            ) !== 0;
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        Artisan::call('company:cache --company_id=' . auth()->user()->company_id);
        return 'true';
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
