<?php
/**
 * 公司配置 服务
 * User: long
 * Date: 2019/12/26
 * Time: 15:56
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\CompanyCustomize;
use App\Services\BaseConstService;

class CompanyCustomizeService extends BaseService
{
    public function __construct(CompanyCustomize $model)
    {
        parent::__construct($model);
    }

    public $filterRules = [
        'admin_url,merchant_url' => ['like', 'url']
    ];

    /**
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show()
    {
        $info = parent::getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * @param $where
     * @param $data
     * @return int|void
     * @throws BusinessLogicException
     */
    public function update($where, $data)
    {
        $row =parent::update($where, $data);
        if($row ==false){
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * @return mixed
     * @throws BusinessLogicException
     */
    public function showByUrl()
    {
        $this->query->where('status', BaseConstService::YES)->whereNotNull('company_id');
        $info = parent::getPageList();
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return collect($info[0])->toArray();
    }
}
