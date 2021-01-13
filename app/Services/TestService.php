<?php
/**
 * 测试 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 17:14
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\Permission;
use App\Models\Test;
use Carbon\Carbon;

class TestService extends BaseService
{

    public $filterRules = [
        'name' => ['like', 'name']
    ];

    public function __construct(Test $test)
    {
        parent::__construct($test);
    }

    public function show($id)
    {
        return parent::getInfo(['id' => $id], ['*'], true);
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function create($params)
    {
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('测试数据新增失败');
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
        $rowCount = parent::updateById($id, [
            'name' => $data['name']
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('测试数据修改失败');
        }
    }

    public function calDate()
    {
        $startDate = '2019-12-30';
        $endDate = Carbon::parse(date('Y-m-d'));
        $startDate = Carbon::parse($startDate);
        for ($i = 1; $startDate->lte($endDate); $i++) {
            $startDate = $startDate->modify("+1 days");
            $startDate = $startDate->format('Y-m-d');
        }
    }

    public function updateAll()
    {

    }

    public function authTree()
    {
        $permission = new Permission();
        $permissionList = $permission->newQuery()->where('type', 2)->get(['id', 'parent_id', 'name', 'route_as', 'type'])->toArray();
        $menuList = $permission->newQuery()->where('type', 1)->get(['id', 'parent_id', 'name', 'route_as', 'type'])->toArray();
        $menuList = TreeService::makeTree($menuList);
        return [
            'permission_list' => $permissionList,
            'menu_list' => $menuList
        ];
    }

}
