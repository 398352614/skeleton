<?php


namespace App\Traits;


use App\Models\Driver;
use App\Models\Employee;
use App\Models\Merchant;
use App\Services\BaseConstService;

Trait UserTrait
{
    public static function get($id, $type)
    {
        $user = [];
        if ($type == BaseConstService::USER_MERCHANT) {
            $user = Merchant::query()->where('id', $id)->first();
        } elseif ($type == BaseConstService::USER_ADMIN) {
            $user = Employee::query()->where('id', $id)->first();
            $user['name'] = $user['username'] ?? '';
        } elseif ($type == BaseConstService::USER_DRIVER) {
            $user = Driver::query()->where('id', $id)->first();
            $user['name'] = $user['fullname'] ?? '';
        }
        $user['user_type'] = $type;
        return $user;
    }
}
