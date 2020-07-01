<?php

/**
 * BaseModel
 * User: long
 * Date: 2019/7/24
 * Time: 15:25
 */

namespace App\Models;

use App\Models\Scope\CompanyScope;
use App\Models\Scope\HasCompanyId;
use App\Traits\CompanyTrait;
use App\Traits\CountryTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use HasCompanyId;

    protected $perPage = 10;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::creating(self::fillId());
    }


    public function insertAll($data)
    {
        $companyId = null;
        if (!auth()->user()) {
            //未授权用户的情况下
            $companyId = self::getCompanyId();
        } elseif (in_array('company_id', $this->getFillable())) {
            $companyId = auth()->user()->company_id;
        }
        //填充company_id
        if (!empty($companyId)) {
            foreach ($data as &$item) {
                $item['company_id'] = $companyId;
            }
        }
        //填充创建时间和修改时间
        foreach ($data as &$item) {
            $item['created_at'] = $item['updated_at'] = now();
        }
        return $this->newQuery()->insert($data);
    }


    public static function fillId()
    {
        return function ($model) {
            /**@var \Illuminate\Database\Eloquent\Model $model */
            $columns = $model->getFillable();
            if (in_array('company_id', $columns)) {
                if (!isset($model->company_id) || $model->company_id === null) {
                    $model->company_id = auth()->user() ? auth()->user()->company_id : self::getCompanyId();
                }
            }
            //若存在国家字段,则自动填充国家字段
            $countryList = ['country', 'receiver_country', 'sender_country'];
            $newColumns = array_flip($columns);
            foreach ($countryList as $country) {
                if (!empty($newColumns[$country])) {
                    $model->$country = auth()->user() ? CompanyTrait::getCountry() : null;
                }
            }
            //若是司机端 则添加司机ID
            if (auth()->user() instanceof Driver) {
                if (in_array('driver_id', $columns)) {
                    if (!isset($model->driver_id) || $model->driver_id === null) {
                        $model->driver_id = auth()->user()->id;
                    }
                }
            }
            //若是商户端,则添加商户ID
            if (auth()->user() instanceof Merchant) {
                if (in_array('merchant_id', $columns)) {
                    if (!isset($model->merchant_id) || $model->merchant_id === null) {
                        $model->merchant_id = auth()->user()->id;
                    }
                }
            }
            //若是商户授权端,则添加商户ID
            if (auth()->user() instanceof MerchantApi) {
                if (in_array('merchant_id', $columns)) {
                    if (!isset($model->merchant_id) || $model->merchant_id === null) {
                        $model->merchant_id = auth()->user()->merchant_id;
                    }
                }
            }
        };
    }

    public static function translateName($list, $name)
    {
        return $list[$name]['name'];
    }

    public function getSenderCountryNameAttribute()
    {
        return empty($this->sender_country) ? null : CountryTrait::getCountryName($this->sender_country);
    }

    public function getReceiverCountryNameAttribute()
    {
        return empty($this->receiver_country) ? null : CountryTrait::getCountryName($this->receiver_country);
    }

    public function getCountryNameAttribute()
    {
        return empty($this->country) ? null : CountryTrait::getCountryName($this->country);
    }
}
