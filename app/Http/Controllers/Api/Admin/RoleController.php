<?php
/**
 * 角色管理
 * User: long
 * Date: 2020/5/13
 * Time: 16:21
 */

namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\RoleService;

/**
 * Class RoleController
 * @package App\Http\Controllers\Api\Admin
 * @property RoleService $service
 */
class RoleController extends BaseController
{
    /**
     * RoleController constructor.
     * @param  RoleService  $service
     * @param  array  $exceptMethods
     */
    public function __construct(RoleService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return mixed
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }

    /**
     * 新增
     * @throws BusinessLogicException
     */
    public function store()
    {
        $rowCount = $this->service->create($this->data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 获取角色权限树
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getRolePermissionTree($id)
    {
        return $this->service->getRolePermissionTree($id);
    }

    /**
     * 修改
     * @param $id
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        $rowCount = $this->service->updateById($id, $this->data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }

    /**
     * 分配权限
     * @param $id
     * @throws BusinessLogicException
     */
    public function assignPermission($id)
    {
        return $this->service->assignPermission($id, $this->data['permission_id_list']);
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }

    /**
     * 获取用户
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getEmployeeList()
    {
        return $this->service->getEmployeeList();
    }

    /**
     * 获取角色用户
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getRoleEmployeeList($id)
    {
        return $this->service->getRoleEmployeeList($id);
    }


    /**
     * 分配用户
     * @param $id
     * @throws BusinessLogicException
     */
    public function assignEmployeeList($id)
    {
        return $this->service->assignEmployeeList($id, $this->data['employee_id_list']);
    }

    /**
     * 移除用户
     * @param $id
     * @throws BusinessLogicException
     */
    public function removeEmployeeList($id)
    {
        return $this->service->removeEmployeeList($id, $this->data['employee_id_list']);
    }
}
