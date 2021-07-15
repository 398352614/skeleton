<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/14
 * Time: 17:57
 */

namespace App\Traits;


use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

trait PermissionTrait
{
    /**
     * 获取权限列表
     * @return array
     */
    public static function getPermissionList()
    {
        $tag = config('tms.cache_tags.permission');
        $permissionList = Cache::tags($tag)->get('permission_list');
        if (empty($permissionList)) {
            Artisan::call('cache:permission');
            $permissionList = Cache::tags($tag)->get('permission_list');
        }
        return $permissionList;
    }
}
