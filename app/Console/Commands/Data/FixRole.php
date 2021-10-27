<?php

namespace App\Console\Commands\Data;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Traits\PermissionTrait;
use Illuminate\Console\Command;

class FixRole extends Command
{
    use PermissionTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix role ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('fix begin');
        try {
            //处理流水表
            $companyList = Company::query()->get(['*'])->toArray();
            foreach ($companyList as $k => $company) {
                $role = Role::query()->where('company_id', $company['id'])->where('is_admin', 1)->first();
                $employeeList = Employee::query()->where('company_id', $company['id'])->get();
                foreach ($employeeList as $v){
                    //将管理员用户加入管理员权限组
                    $this->addPermission($v, $role);
                }
            }
        } catch (\Exception $e) {
            $this->info('fix fail:' . $e);
        }
        $this->info('fix end');
    }

    /**
     * @param $employee
     * @param $role
     */
    protected function addPermission($employee, $role)
    {
        $employee->syncRoles($role);
    }
}
