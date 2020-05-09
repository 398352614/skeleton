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
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;

class CompanyConfigService extends BaseService
{
    public function __construct(CompanyConfig $companyConfig)
    {
        parent::__construct($companyConfig);
    }

    /**
     * 创建或更新
     * @param $params
     * @throws BusinessLogicException
     */
    public function createOrUpdate($params)
    {
        $rowCount = $this->query->updateOrCreate(['company_id' => auth()->user()->company_id], [
            'line_rule' => $params['line_rule'] ?? BaseConstService::LINE_RULE_POST_CODE,
            'weight_unit' => $params['weight_unit'] ?? '',
            'currency_unit' => $params['currency_unit'] ?? '',
            'volume_unit' => $params['volume_unit'] ?? '',
            'map' => $params['map'] ?? '',
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}
