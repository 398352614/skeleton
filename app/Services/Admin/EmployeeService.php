<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\CarResource;
use App\Http\Resources\EmployeeListResource;
use App\Models\Employee;
use App\Services\BaseService;

class EmployeeService extends BaseService
{
    use HasEnabledSet,
        HasLoginControl;

    public $filterRules = [
        'username' => ['like', 'username'],
        'fullname' => ['like', 'fullname'],
    ];

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
        $this->query = $this->model::query();
        $this->resource = EmployeeListResource::class;
        $this->infoResource = EmployeeListResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        $this->setFilterRules();
    }

    /**
     * @param  int  $institution
     * @return mixed
     */
    public function indexOfInstitution(int $institution)
    {
         $this->query->where('institution_id', $institution);

         return parent::getPaginate();
    }

    /**
     *
     *
     * @param  array  $data
     * @throws BusinessLogicException
     */
    public function createEmployee(array $data)
    {
        $res = $this->create(
            [
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'remark' => $data['remark'] ?? '',
                'auth_group_id' => $data['group_id'],
                'institution_id' => $data['institution_id'] ?? null,
                'password' => bcrypt($data['password']),
            ]
        );

        if ($res === false) {
            throw new BusinessLogicException('新建员工失败');
        }
    }

    /**
     * 修改
     *
     * @param  int  $id
     * @param  array  $data
     * @throws BusinessLogicException
     */
    public function updateEmployee(int $id, array $data)
    {
        /** @var Employee $employee */
        $employee = $this->model::findOrFail($id);

        $res = $employee->update([
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? '',
                'remark' => $data['remark'] ?? '',
                'auth_group_id' => $data['group_id'],
                'institution_id' => $data['institution_id'] ?? null,
        ]);

        if ($res === false) {
            throw new BusinessLogicException('修改员工失败');
        }
    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        if(auth()->user()->id === $id){
            throw new BusinessLogicException('无法删除自己');
        }
        $rowCount = parent::delete(['id' => $id]);

        if ($rowCount === false) {
            throw new BusinessLogicException('员工删除失败');
        }
    }
}
