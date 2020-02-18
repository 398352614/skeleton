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
        } elseif (in_array('company_id', Schema::getColumnListing($this->getTable()))) {
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
            if (in_array('company_id', Schema::getColumnListing($model->getTable()))) {
                if (!isset($model->company_id) || $model->company_id === null) {
                    $model->company_id = auth()->user() ? auth()->user()->company_id : self::getCompanyId();
                }
            }
            //若是司机端 则添加司机ID
            if (auth()->user() instanceof Driver) {
                /**@var \Illuminate\Database\Eloquent\Model $model */
                if (in_array('driver_id', Schema::getColumnListing($model->getTable()))) {
                    if (!isset($model->driver_id) || $model->driver_id === null) {
                        $model->driver_id = auth()->user()->id;
                    }
                }
            }
        };
    }

    public static function translateName($list, $name)
    {
        return $list[$name]['name'];
    }
}
