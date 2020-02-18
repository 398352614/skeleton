<?php

/**
 * @Author: h9471
 * @Created: 2019/10/21 17:03
 */

namespace App\Models;

use App\Models\Scope\CompanyScope;
use App\Models\Scope\HasCompanyId;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Support\Facades\Schema;

class Authenticatable extends BaseUser
{
    use HasCompanyId;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
        static::creating(self::fillCompanyId());
    }

    public static function fillCompanyId()
    {
        return function ($model) {
            /**@var \Illuminate\Database\Eloquent\Model $model */
            if (in_array('company_id', Schema::getColumnListing($model->getTable()))) {
                if (!isset($model->company_id) || $model->company_id === null) {
                    $model->company_id = auth()->user() ? auth()->user()->company_id : self::getCompanyId();
                }
            }
        };
    }
}
