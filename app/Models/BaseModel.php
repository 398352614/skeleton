<?php
/**
 * BaseModel
 * User: long
 * Date: 2019/7/24
 * Time: 15:25
 */

namespace App\Models;

use App\Models\Scope\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
        static::creating(self::fillCompanyId());
    }


    public function insertAll($data)
    {
        $companyId = null;
        if (in_array('company_id', Schema::getColumnListing($this->getTable()))) {
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


    public static function fillCompanyId()
    {
        return function ($model) {
            /**@var \Illuminate\Database\Eloquent\Model $model */
            if (in_array('company_id', Schema::getColumnListing($model->getTable()))) {
                if (!isset($model->company_id) || $model->company_id === null) {
                    $model->company_id = auth()->user()->company_id;
                }
            }
        };
    }


    public static function translateName($list, $name)
    {
        return $list[$name]['name'];
    }
}