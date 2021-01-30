<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Role;
use App\Services\BaseConstService;
use App\Traits\PermissionTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class initPermission extends Command
{
    use PermissionTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:init';

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
            foreach ($companyList as $company) {
                $this->info($company['id']);
                $role=Role::query()->where('company_id',$company['id'])->where('is_admin',1)->first();
                if(empty($role)){
                    $role = Role::create([
                        'company_id' => $company['id'],
                        'name' => '管理员组',
                        'is_admin' => 1,
                    ]);
                    $this->info(1);
                    $basePermissionList = self::getPermissionList();
                    $this->info(2);
                    $role->syncPermissions(array_column($basePermissionList, 'id'));
                    $this->info(3);
                    $employee = Employee::query()->where('company_id', $company['id'])->orderBy('id')->first();
                    $this->info(4);
                    if(empty(DB::table('model_has_roles')->where('employee_id',$employee['id'])->first())){
                        $this->addPermission($employee, $role);//初始化员工权限组
                    }
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
