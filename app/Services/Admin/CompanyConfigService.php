<?php
/**
 * 公司配置 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:56
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\CompanyConfig;
use Illuminate\Support\Facades\Artisan;

class CompanyConfigService extends BaseService
{
    public function __construct(CompanyConfig $companyConfig)
    {
        parent::__construct($companyConfig);
    }

    public function getAddressTemplateList()
    {
        $data = [];
        $data['template_list'] = $this->getAddressTemplateService()->getList([], ['id'], false);
        return $data;
    }

    /**
     * 创建或更新
     * @param $params
     * @throws BusinessLogicException
     */
    public function createOrUpdate($params)
    {
        $addressTemplate = $this->getAddressTemplateService()->getInfo(['id' => $params['address_template_id']], ['id'], false);
        if (empty($addressTemplate)) {
            throw new BusinessLogicException('地址模板不存在');
        }
        $rowCount = $this->query->updateOrCreate(['company_id' => auth()->user()->company_id], [
            'line_rule' => $params['line_rule'],
            'show_type' => $params['show_type'] ?? 1,
            'address_template_id' => $params['address_template_id'],
            'stock_exception_verify' => $params['stock_exception_verify'] ?? 2,
            'weight_unit' => $params['weight_unit'],
            'currency_unit' => $params['currency_unit'],
            'volume_unit' => $params['volume_unit'],
            'map' => $params['map'],
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        Artisan::call('company:cache --company_id=' . auth()->user()->company_id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUnitConfig()
    {
        return $this->query->select(['weight_unit', 'currency_unit', 'volume_unit'])->first();
    }

    /**
     * @param  array  $data
     * @return int
     */
    public function setUnitConfig(array $data)
    {
        return $this->update(['company_id' => auth()->user()->company_id], [
            'weight_unit'   => $data['weight_unit'],
            'currency_unit' => $data['currency_unit'],
            'volume_unit'   => $data['volume_unit'],
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRuleConfig()
    {
        return $this->query->select(['line_rule', 'scheduling_rule'])->first();
    }

    /**
     * @param  array  $data
     * @return int
     */
    public function setRuleConfig(array $data)
    {
        return $this->update(['company_id' => auth()->user()->company_id], [
            'line_rule'          => $data['line_rule'],
            'scheduling_rule'   => $data['scheduling_rule']
        ]);
    }
}
