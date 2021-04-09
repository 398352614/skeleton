<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/4/7
 * Time: 16:27
 */

namespace App\Console\Commands;


use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Workerman\Worker;

class BasePermissionInit extends Command
{
    protected $signature = 'base-permission:init';

    protected $description = 'Base permission init';


    public function handle()
    {
        try {
            DB::table('permissions')->delete();
            $permissionList = json_decode(file_get_contents(config('tms.permission_path')));
            foreach ($permissionList as $k => $v) {
                $permissionList[$k] = collect($v)->toArray();
            }
            DB::table('permissions')->insert($permissionList);
            $this->info('successful');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
