<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/13
 * Time: 16:22
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\RoleEmployeeListResource;
use App\Http\Resources\Api\Admin\RoleResource;
use App\Models\Employee;
use App\Models\Role;
use App\Services\BaseService;
use App\Services\TreeService;
use App\Traits\PermissionTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


/**
 * Class RoleService
 * @package App\Services\Admin
 * @property Role $model
 */
class RoleService extends BaseService
{
    use PermissionTrait;

    public $filterRules = [
        'name' => ['like', 'keyword'],
    ];

    public $orderBy = ['id' => 'asc'];

    public function __construct(Role $role)
    {
        parent::__construct($role, RoleResource::class);
    }

    /**
     * 员工 服务
     * @return EmployeeService
     */
    private function getEmployeeService()
    {
        return self::getInstance(EmployeeService::class);
    }

    /**
     * 获取角色权限树
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function getRolePermissionTree($id)
    {
        $rolePermissionList = $this->model::findById($id)->getAllPermissions();
        $permissionIdList = array_column($rolePermissionList->toArray(), 'id');
        $permissionList = array_map(function ($permission) {
            return Arr::only($permission, ['id', 'parent_id', 'name', 'route_as', 'type']);
        }, self::getPermissionList());
        foreach ($permissionList as &$permission) {
            $permission['is_auth'] = (in_array($permission['id'], $permissionIdList)) ? 1 : 2;
        }
        return TreeService::makeTree($permissionList);
    }

    /**
     * 分配权限
     * @param $id
     * @param $permissionIdList
     * @throws BusinessLogicException
     */
    public function assignPermission($id, $permissionIdList)
    {
        if ($id == $this->getAdminRoleId()) {
            throw new BusinessLogicException('管理员组权限不允许操作');
        }
        //1.获取分配的权限列表
        $permissionIdList = explode_id_string($permissionIdList);
        $basePermissionList = self::getPermissionList();
        $permissionList = Arr::where($basePermissionList, function ($permission) use ($permissionIdList) {
            return in_array($permission['id'], $permissionIdList);
        });
        //2.权限分配
        $this->model::findById($id)->syncPermissions(array_column($permissionList, 'id'));
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        if ($id == $this->getAdminRoleId()) {
            throw new BusinessLogicException('管理员组权限不允许操作');
        }
        $modelHasRolesTable = config('permission.table_names.model_has_roles');
        $roleEmployee = DB::table($modelHasRolesTable)->where('role_id', $id)->first();
        if (!empty($roleEmployee)) {
            throw new BusinessLogicException('请先移除该权限组员工');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * 获取用户
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getEmployeeList()
    {
        $this->per_page = $this->request->input('per_page', 10);
        $employeeName = $this->formData['fullname'] ?? '';
        $modelHasRolesTable = config('permission.table_names.model_has_roles');
        $roleList = parent::getList([], ['id'], false)->toArray();
        $employeeIdList = DB::table($modelHasRolesTable)
            ->whereIn('role_id', array_column($roleList, 'id'))
            ->pluck('employee_id')
            ->toArray();
        $query = Employee::query();
        if (!empty($employeeName)) {
            $employeeName = str_replace('_', '\_', str_replace('%', '\%', $employeeName));
            $query->where('fullname', 'like', "%{$employeeName}%");
        }
        $employeeList = $query->whereNotIn('id', $employeeIdList)->paginate($this->per_page);
        return RoleEmployeeListResource::collection($employeeList);
    }


    /**
     * 获取角色用户
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws BusinessLogicException
     */
    public function getRoleEmployeeList($id)
    {
        $role = $this->model::findById($id);
        $this->per_page = $this->request->input('per_page', 10);
        $employeeName = $this->formData['fullname'] ?? '';
        $modelHasRolesTable = config('permission.table_names.model_has_roles');
        $query = Employee::query()
            ->join("$modelHasRolesTable as b", 'b.employee_id', '=', 'employee.id')
            ->where('b.role_id', $role->id)
            ->where('employee.company_id', auth()->user()->company_id);
        if (!empty($employeeName)) {
            $employeeName = str_replace('_', '\_', str_replace('%', '\%', $employeeName));
            $query->where('employee.fullname', 'like', "%{$employeeName}%");
        }
        $list = $query->paginate($this->per_page);
        return RoleEmployeeListResource::collection($list);
    }

    /**
     * 分配用户
     * @param $id
     * @param $employeeIdList
     * @throws BusinessLogicException
     */
    public function assignEmployeeList($id, $employeeIdList)
    {
        $role = $this->model::findById($id);
        //过滤员工ID
        $employeeIdList = explode(',', $employeeIdList);
        if (empty($employeeIdList)) return;
        if (in_array($this->getEmployeeService()->getAdminEmployeeId(), $employeeIdList)) {
            throw new BusinessLogicException('存在超级管理员，不能操作');
        }
        $roleEmployeeIdList = DB::table(config('permission.table_names.model_has_roles'))->whereIn('employee_id', $employeeIdList)->pluck('employee_id')->toArray();
        $employeeIdList = array_diff($employeeIdList, $roleEmployeeIdList);
        if (empty($employeeIdList)) return;

        $employeeList = $this->getEmployeeService()->getList(['id' => ['in', $employeeIdList]], ['id'], false);
        if ($employeeList->isEmpty()) return;
        //分配用户
        $employeeList->map(function ($employee, $key) use ($role) {
            /**@var \App\Models\Employee $employee */
            $employee->syncRoles($role);
        });
    }

    /**
     * 移除用户
     * @param $id
     * @param $employeeIdList
     * @throws BusinessLogicException
     */
    public function removeEmployeeList($id, $employeeIdList)
    {
        $role = $this->model::findById($id);
        //过滤员工ID
        $employeeIdList = explode(',', $employeeIdList);
        if (in_array($this->getEmployeeService()->getAdminEmployeeId(), $employeeIdList)) {
            throw new BusinessLogicException('存在超级管理员，不能操作');
        }
        $employeeList = $this->getEmployeeService()->getList(['id' => ['in', $employeeIdList]], ['id'], false);
        //分配用户
        $employeeList->map(function ($employee, $key) use ($role) {
            /**@var \App\Models\Employee $employee */
            $employee->removeRole($role);
        });
    }

    /**
     * 获取管理员组ID
     * @return mixed|null
     */
    public function getAdminRoleId()
    {
        $companyId = auth()->user()->company_id;
        $adminRole = $this->model->newQuery()->where('company_id', $companyId)->where('is_admin', 1)->first();
        return $adminRole->id ?? null;
    }

}
