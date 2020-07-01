<?php

/**
 * @Author: h9471
 * @Created: 2019/10/21 17:03
 */

namespace App\Models;

use App\Models\Scope\CompanyScope;
use App\Models\Scope\HasCompanyId;
use App\Traits\CountryTrait;
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
            if (in_array('company_id', $this->getFillable())) {
                if (!isset($model->company_id) || $model->company_id === null) {
                    $model->company_id = auth()->user() ? auth()->user()->company_id : self::getCompanyId();
                }
            }
        };
    }

    public function getSenderCountryNameAttribute()
    {
        return empty($this->sender_country) ? null : CountryTrait::getCountryName($this->sender_country);
    }

    public function getReceiverCountryNameAttribute()
    {
        return empty($this->receive_country) ? null : CountryTrait::getCountryName($this->receive_country);
    }

    public function getCountryNameAttribute()
    {
        return empty($this->country) ? null : CountryTrait::getCountryName($this->country);
    }


}
