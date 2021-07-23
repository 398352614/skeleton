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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        $date = ['execution_date', 'second_execution_date', 'expiration_date', 'lisence_valid_date', 'date', 'auth_birth_date', 'recharge_date', 'verify_date'];
        //填充创建时间和修改时间
        foreach ($data as &$item) {
            $item['created_at'] = $item['updated_at'] = now();
            foreach ($date as $v) {
                if (key_exists($v, $item) && $item[$v] == '') {
                    $item[$v] = null;
                }
            }
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
//            //若存在国家字段,则自动填充国家字段
//            $countryList = ['country', 'place_country', 'second_place__country', 'warehouse_country'];
//            $newColumns = array_flip($columns);
//            foreach ($countryList as $country) {
//                if (!empty($newColumns[$country]) && empty($model->$country)) {
//                    $model->$country = auth()->user() ? CompanyTrait::getCountry() : null;
//                }
//            }
            //若存在日期字段，则自动填充日期
            $date = ['execution_date', 'second_execution_date', 'expiration_date', 'lisence_valid_date', 'date', 'auth_birth_date', 'recharge_date', 'verify_date'];
            foreach ($date as $v) {
                if (in_array($v, $columns) && $model->$v == '') {
                    $model->$v = null;
                }
            }
            //若是司机端 则添加司机ID
            if (auth()->user() instanceof Driver) {
                if (!($model instanceof Tour)
                    && !($model instanceof Batch)
                    && !($model instanceof TrackingOrder)
                ) {
                    if (in_array('driver_id', $columns)) {
                        if (!isset($model->driver_id) || $model->driver_id === null) {
                            $model->driver_id = auth()->user()->id;
                        }
                    }
                }
            }
            //若是货主端,则添加货主ID
            if (auth()->user() instanceof Merchant) {
                if (in_array('merchant_id', $columns)) {
                    if (!isset($model->merchant_id) || $model->merchant_id === null) {
                        $model->merchant_id = auth()->user()->id;
                    }
                }
            }
            //若是货主授权端,则添加货主ID
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

    public function getWarehouseCountryNameAttribute()
    {
        return empty($this->warehouse_country) ? null : CountryTrait::getCountryName($this->warehouse_country);
    }

    public function getSecondPlaceCountryNameAttribute()
    {
        return empty($this->second_place_country) ? null : CountryTrait::getCountryName($this->second_place_country);
    }

    public function getPlaceCountryNameAttribute()
    {
        return empty($this->place_country) ? null : CountryTrait::getCountryName($this->place_country);
    }

    public function getCountryNameAttribute()
    {
        return empty($this->country) ? null : CountryTrait::getCountryName($this->country);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getCreatedAtAttribute()
    {
        return (new Carbon($this->attributes['created_at']))->setTimezone(auth()->user()->timezone ?? config('tms.timezone'))->toDateTimeString();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUpdatedAtAttribute()
    {
        return (new Carbon($this->attributes['updated_at']))->setTimezone(auth()->user()->timezone ?? config('tms.timezone'))->toDateTimeString();
    }
}
