<?php

namespace App\Services\Consumer;

use App\Exceptions\BusinessLogicException;
use App\Models\Company;
use App\Models\Package;
use App\Models\PackageTrail;
use App\Models\Scope\CompanyScope;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

/**
 * 公司配置服务
 * Class CompanyService
 * @package App\Services\Admin
 */
class PackageTrailService extends BaseService
{
    public $filterRules = [
        'company_id' => ['=', 'company_id'],
        'express_first_no' => ['=', 'express_first_no']
    ];

    public function __construct(PackageTrail $model)
    {
        parent::__construct($model);
    }

    /**
     * 查询
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws BusinessLogicException
     */
    public function getPageList()
    {
        $packageList = Package::query()->where('express_first_no', $this->formData['express_first_no'])->where('company_id', $this->formData['company_id'])->get();
        if ($packageList->isEmpty()) {
            throw new BusinessLogicException('查无结果，请检查单号和快递公司是否有误');
        }
        foreach ($packageList as $k => $v) {
            $company = collect(Company::query()->where('id', $v['company_id'])->first())->toArray();
            if (empty($company)) {
                throw new BusinessLogicException('公司不存在');
            }
            $packageList[$k]['company_name'] = $company['name'];
            $packageList[$k]['company_web_site'] = $company['web_site'];
            $packageList[$k]['company_logo_url'] = $company['logo_url'];
            $packageList[$k]['package_trail'] = parent::getList(['express_first_no' => $v['express_first_no'], 'company_id' => $v['company_id']], ['*'], false, [], ['id' => 'desc']);
        }
        return $packageList;
    }
}
