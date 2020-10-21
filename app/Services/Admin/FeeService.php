<?php
/**
 * 费用服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:46
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\Api\Admin\Api\Admin\FeeResource;
use App\Models\Fee;
use App\Services\BaseConstService;
use App\Services\BaseService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Support\Arr;

class FeeService extends BaseService
{
    public $filterRules = [
        'name' => ['like', 'name'],
    ];

    public function __construct(Fee $model)
    {
        parent::__construct($model, FeeResource::class);
    }

    /**
     * 初始化
     * @return array
     */
    public function init()
    {
        $data = [];
        $data['level_list'] = ConstTranslateTrait::formatList(ConstTranslateTrait::$feeLevelList);
        return $data;
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['level'] = BaseConstService::FEE_LEVEL_2;
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        unset($data['company_id']);
        $fee = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($fee)) {
            throw new BusinessLogicException('数据不存在');
        }
        if (intval($fee['level']) == 1) {
            $data = Arr::only($data, ['name', 'amount']);
        }
        unset($data['company_id'], $data['level']);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $fee = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($fee)) {
            return 'true';
        }
        if (intval($fee['level']) == 1) {
            throw new BusinessLogicException('系统级费用不能删除');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        return 'true';
    }

    public function getPageList(){
        $this->query->orderBy('level')->orderBy('created_at');
        return parent::getPageList();
    }

}
