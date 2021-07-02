<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Role;
use App\Traits\PermissionTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class InitPermission extends Command
{
    use PermissionTrait;

    /**
     * The name and signature of the console command.
     * mode=1为重置修改，否则为增量修改
     * @var string
     */
    protected $signature = 'init:permission {--mode= : mode} {--id= : company id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'permission init';

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
     * @return void
     */
    public function handle()
    {
        try {
            $companyList = empty($this->option('id'))
                ? Company::query()->get(['id'])->toArray()
                : Company::query()->where('id', $this->option('id'))->get(['id'])->toArray();

            $basePermissionList = self::getPermissionList();

            foreach ($companyList as $company) {
                $this->info($company['id']);
                $role = Role::query()->where('company_id', $company['id'])->where('is_admin', 1)->first();
                if (empty($role)) {
                    //新建管理员权限组
                    $role = Role::create([
                        'company_id'    => $company['id'],
                        'name'          => '管理员组',
                        'is_admin'      => 1,
                    ]);
                    $employee = Employee::query()->where('company_id', $company['id'])->orderBy('id')->first();
                    if (empty(DB::table('model_has_roles')->where('employee_id', $employee['id'])->first())) {
                        //将管理员用户加入管理员权限组
                        $this->addPermission($employee, $role);
                    }
                }
                //给管理员权限组补齐权限
                if ($this->option('mode') == 1) {
                    //重置修改
                    $role->syncPermissions($basePermissionList);
                } else {
                    //增量修改
                    $oldPermissionList = collect($role->getAllPermissions())->pluck('id')->toArray();
                    $addPermissionList = array_diff($basePermissionList, $oldPermissionList);
                    $role->givePermissionTo($addPermissionList);
                }
            }
            $this->info('successful');
        } catch (\Exception $e) {
            $this->info($e->getMessage() . "\n");
            $this->info('permission init failed');
        }
    }

    /**
     * @return mixed
     */
    public static function getPermissionList()
    {
        $tag = config('tms.cache_tags.permission');
        Artisan::call('permission:cache');
        $permissionList = Cache::tags($tag)->get('permission_list');
        return collect($permissionList)->pluck('id')->toArray();
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
