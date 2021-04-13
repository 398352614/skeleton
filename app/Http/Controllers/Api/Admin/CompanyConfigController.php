<?php
/**
 * 公司配置 接口
 * User: long
 * Date: 2019/12/26
 * Time: 15:55
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\CompanyConfigService;

/**
 * Class CountryController
 * @package App\Http\Controllers\Api\Admin
 * @property CompanyConfigService $service
 */
class CompanyConfigController extends BaseController
{
    public function __construct(CompanyConfigService $service)
    {
        parent::__construct($service);
    }

    /**
     * 获取详情
     * @return mixed
     * @throws BusinessLogicException
     */
    public function show()
    {
        $info = $this->service->getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        return empty($info) ? "" : $info->toArray();
    }

    /**
     * 获取地址模板列表
     * @return array
     */
    public function getAddressTemplateList()
    {
        return $this->service->getAddressTemplateList();
    }

    /**
     * 更新
     * @throws BusinessLogicException
     */
    public function update()
    {
        return $this->service->createOrUpdate($this->data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function unit_show()
    {
        return $this->service->getUnitConfig();
    }

    /**
     * @return int
     */
    public function unit_update()
    {
        return $this->service->setUnitConfig($this->data);
    }
}
