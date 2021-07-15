<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 包裹轨迹轨迹表
 * Class TrackingOrderTrail
 * @package App\Models
 */
class PackageTrail extends BaseModel
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_trail';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'merchant_id',
        'order_no',
        'type',
        'tracking_order_no',
        'content',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * 翻译
     * @param $value
     * @return array|string|null
     */
    public function getContentAttribute($value)
    {
        if (preg_match_all('/(?<=\[)[^]]+/', $value, $params)) {
            for ($i = 0, $j = count($params[0]); $i < $j; $i++) {
                    $data['params' . ($i + 1)] = $params[0][$i];
                    $value = str_replace_limit($data['params' . ($i + 1)], ':params' . ($i + 1), $value, 1);
                }
            return !empty($value) ? __($value, $data) : null;
        } else {
            return !empty($value) ? __($value) : null;
        }
    }

    protected $appends = [
        'type_name'
    ];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::packageTrailTypeList($this->type);
    }

}
