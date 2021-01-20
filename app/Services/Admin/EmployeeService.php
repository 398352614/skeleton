<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Admin\EmployeeResource;
use App\Models\Employee;
use App\Models\Role;
use App\Traits\HasLoginControlTrait;
use Illuminate\Support\Facades\DB;

class EmployeeService extends BaseService
{
    use HasLoginControlTrait;

    public $filterRules = [
        'email' => ['like', 'email'],
        'fullname' => ['like', 'fullname'],
    ];

    public function __construct(Employee $employee)
    {
        parent::__construct($employee, EmployeeResource::class, EmployeeResource::class);
    }

    public function getPageList()
    {
        $list = parent::getPageList();
        if (empty($list)) return $list;
        foreach ($list as &$employee) {
            $role = $this->getEmployeeRole($employee->id);
            $employee['role_id'] = $role['id'] ?? null;
            $employee['role_id_name'] = $role['name'] ?? '';
        }
        return $list;
    }

    public function show($id)
    {
        $employee = parent::getInfo(['id' => $id], ['*'], true);
        $role = $this->getEmployeeRole($employee->id);
        $employee['role_id'] = $role['id'] ?? null;
        $employee['role_id_name'] = $role['name'] ?? '';
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
        $role = Role::findById($data['role_id']);
        $employee = parent::create(
            [
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'remark' => $data['remark'] ?? '',
                'password' => bcrypt($data['password']),
            ]
        );
        if ($employee === false) {
            throw new BusinessLogicException('新建员工失败');
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
        $role = Role::findById($data['role_id']);
        $employee = $this->model::findOrFail($id);
        $rowCount = $employee->update([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'remark' => $data['remark'] ?? ''
        ]);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改员工失败');
        }
        $employee->syncRoles($role);
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
            throw new BusinessLogicException('修改员工密码失败');
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
            throw new BusinessLogicException('员工删除失败');
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
}
