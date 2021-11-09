<?php
/**
 * 单号规则 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/18
 * Time: 14:01
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\PackageNoRuleResource;
use App\Models\PackageNoRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PackageNoRuleService extends BaseService
{
    public function __construct(PackageNoRule $orderNoRule)
    {
        parent::__construct($orderNoRule, PackageNoRuleResource::class, PackageNoRuleResource::class);
    }

    public $filterRules = [
        'status' => ['=', 'status'],
        'name' => ['like', 'name']
    ];

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
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
        $data = Arr::only($data, ['name', 'prefix', 'length', 'status']);
        $this->check($data, $id);
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 验证
     * @param $params
     * @param null $id
     * @throws BusinessLogicException
     */
    private function check(&$params, $id = null)
    {
        if (strlen($params['prefix']) > $params['length']) {
            throw new BusinessLogicException("前缀长度不得超过总长度");
        }
        $info = DB::table('package_no_rule')->where('name', $params['name'])->where('company_id', '<>', -1)->first();
        if (!empty($info) && $info->id != $id) {
            throw new BusinessLogicException('名称已存在');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 顺带验证
     * @param $list
     * @throws BusinessLogicException
     */
    public function additionalCheck($list)
    {
        $this->query->where('status', BaseConstService::YES);
        $ruleList = parent::getList();
        if ($ruleList->isNotEmpty()) {
            $ruleList = collect($ruleList)->toArray();
            foreach ($list as $k => $v) {
                $bool = 0;
                foreach ($ruleList as $x => $y) {
                    if (str_starts_with($v['package_no'], $y['prefix']) && strlen($v['package_no']) == $y['length']) {
                        $bool = 1;
                    }
                }
                if ($bool == 0) {
                    throw new BusinessLogicException('该包裹非本系统包裹，无法顺带');
                }
            }
        }
    }
}
