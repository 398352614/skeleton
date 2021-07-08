<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\EmployeeResource;
use App\Models\Employee;
use App\Models\Role;
use App\Services\BaseConstService;
use App\Traits\HasLoginControlTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EmployeeService extends BaseService
{
    use HasLoginControlTrait;

    public $filterRules = [
        'email' => ['like', 'email'],
        'fullname' => ['like', 'fullname'],
        'warehouse_id' => ['=', 'warehouse_id']
    ];

    public function __construct(Employee $employee)
    {
        parent::__construct($employee, EmployeeResource::class, EmployeeResource::class);
    }

    public function getPageList()
    {
        $this->per_page = $this->request->input('per_page', 10);
        $employeeIdList = [];
        if (!empty($this->formData['role_id'])) {
            $modelHasRolesTable = config('permission.table_names.model_has_roles');
            $eRoleList = DB::table($modelHasRolesTable)->where('role_id', $this->formData['role_id'])->paginate($this->per_page);
            $employeeIdList = $eRoleList->pluck('employee_id')->toArray();
        }
        $this->filters['id'] = ['in', $employeeIdList];
        $list = parent::getPageList();
        $warehouseIdList = $list->pluck('warehouse_id')->unique()->toArray();
        $warehouseList = $this->getWareHouseService()->getList(['id' => ['in', $warehouseIdList]], ['*'], false)->keyBy('id');
        if (empty($list)) return $list;
        foreach ($list as &$employee) {
            $role = $this->getEmployeeRole($employee->id);
            $employee['role_id'] = $role['id'] ?? null;
            $employee['role_id_name'] = $role['name'] ?? '';
            $employee['warehouse_name'] = $warehouseList[$employee['warehouse_id']]['name'] ?? '';
        }
        return $list;
    }

    public function show($id)
    {
        $employee = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($employee)) return [];
        $role = $this->getEmployeeRole($employee['id']);
        $employee['role_id'] = $role['id'] ?? null;
        $employee['role_id_name'] = $role['name'] ?? '';
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $employee['warehouse_id']],['*'],false);
        $employee['warehouse_name'] = $warehouse['name'] ?? '';
        return $employee;
    }

    /**
     * 获取员工权限组
     * @param $employeeId
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getEmployeeRole($employeeId)
    {
        $roleEmployee = DB::table(config('permission.table_names.model_has_roles'))->where('employee_id', $employeeId)->first();
        if (empty($roleEmployee)) return [];
        return $this->getRoleService()->getInfo(['id' => $roleEmployee->role_id], ['*'], false);
    }

    /**
     *
     *
     * @param array $data
     * @throws BusinessLogicException
     */
    public function store(array $data)
    {
        $data = $this->check($data);
        $role = Role::findById($data['role_id']);
        $employee = parent::create($data);
        if ($employee === false) {
            throw new BusinessLogicException('新增失败');
        }
        //员工添加权限组
        $employee->syncRoles($role);
    }

    /**
     * 修改
     *
     * @param int $id
     * @param array $data
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $data = $this->check($data);
        $role = Role::findById($data['role_id']);
        $employee = $this->model::findOrFail($id);
        $adminRoleId = $this->getRoleService()->getAdminRoleId();
        if (($employee->is_admin == 1) && $adminRoleId != $data['role_id']) {
            throw new BusinessLogicException('超级管理员只能在管理员组');
        }
        $rowCount = $employee->update($data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败');
        }
        $employee->syncRoles($role);
    }

    /**
     * @param $data
     * @return
     * @throws BusinessLogicException
     */
    public function check($data)
    {
        $warehouse = $this->getWareHouseService()->getInfo(['id' => $data['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        unset($data['password']);
        return $data;
    }

    /**
     * 修改密码
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws BusinessLogicException
     */
    public function resetPassword(int $id, array $data)
    {
        /** @var Employee $employee */
        $employee = $this->model::findOrFail($id);

        $res = $employee->update([
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        if ($res === false) {
            throw new BusinessLogicException('修改失败');
        }

        return $res;
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        if (auth()->user()->id === $id) {
            throw new BusinessLogicException('无法删除自己');
        }
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
        //删除员工权限
        $modelHasRolesTable = config('permission.table_names.model_has_roles');
        $rowCount = DB::table($modelHasRolesTable)->where('employee_id', $id)->delete();
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败');
        }
    }

    /**
     * 获取超级管理员
     * @return mixed|null
     */
    public function getAdminEmployeeId()
    {
        $companyId = auth()->user()->company_id;
        $adminEmployee = $this->model->newQuery()->where('company_id', $companyId)->where('is_admin', 1)->first();
        return $adminEmployee->id ?? null;
    }

    /**
     * 批量修改状态
     * @param $data
     */
    public function setLoginByList($data)
    {
        $idList = explode_id_string($data['id_list']);
        if ($data['status'] == BaseConstService::NO) {
            $data['status'] = false;
        }
        $this->forbidLogin($idList, $data['status']);
    }
}
