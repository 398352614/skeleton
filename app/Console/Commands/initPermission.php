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

class initPermission extends Command
{
    use PermissionTrait;

    /**
     * The name and signature of the console command.
     * mode=1为重置修改，否则为增量修改
     * @var string
     */
    protected $signature = 'permission:init {--mode= : mode}';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            $companyList = Company::query()->get(['id'])->toArray();
            $basePermissionList = self::getPermissionList();
            foreach ($companyList as $company) {
                $this->info($company['id']);
                $role = Role::query()->where('company_id', $company['id'])->where('is_admin', 1)->first();
                if (empty($role)) {
                    //新建管理员权限组
                    $role = Role::create([
                        'company_id' => $company['id'],
                        'name' => '管理员组',
                        'is_admin' => 1,
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
                    $role->syncPermissions(array_column($basePermissionList, 'id'));
                } else {
                    //增量修改
                    $addPermissionList=[];
                    $oldPermissionList = collect($role->getAllPermissions())->pluck('id')->toArray();
                    $basePermissionList = collect($basePermissionList)->pluck('id')->toArray();
                    foreach ($basePermissionList as $k => $v) {
                        if (!in_array($v, $oldPermissionList)) {
                            $addPermissionList[] = $v;
                        }
                    }
                    $role->givePermissionTo($addPermissionList);
                }
            }
        } catch (\Exception $e) {
            $this->info($e);
            $this->info('permission init failed');
            return;
        }
        $this->info('successful');
        return;
    }

    public static function getPermissionList()
    {
        $tag = config('tms.cache_tags.permission');
        $permissionList = Cache::tags($tag)->get('permission_list');
        if (empty($permissionList)) {
            Artisan::call('permission:cache');
            $permissionList = Cache::tags($tag)->get('permission_list');
        }
        return $permissionList;
    }

    protected function addPermission($employee, $role)
    {
        $employee->syncRoles($role);
    }
}
