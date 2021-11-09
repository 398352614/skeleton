<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\MapConfig;

class MapConfigService extends BaseService
{


    /**
     * @var \string[][]
     */
    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id'],

    ];

    /**
     * AddressService constructor.
     * @param MapConfig $model
     */
    public function __construct(MapConfig $model)
    {
        parent::__construct($model);
    }

    /**
     * 修改
     * @param $params
     * @throws BusinessLogicException
     */
    public function updateByCompanyId($params)
    {
        $rowCount = $this->update(['company_id' => auth()->user()->company_id], $params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show()
    {
        $data= $this->getInfo(['company_id' => auth()->user()->company_id], ['*'], false);
        if(empty($data)){
            throw new BusinessLogicException('数据不存在');
        }
        return $data;
    }
}
