<?php
/**
 * @Author: h9471
 * @Created: 2019/10/21 17:03
 */

namespace App\Models;

use App\Models\Scope\CompanyScope;
use Illuminate\Foundation\Auth\User as BaseUser;

class Authenticatable extends BaseUser
{
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }
}
