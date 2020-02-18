<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 特殊时段计费表
 * Class Employee
 * @package App\Models
 */
class SpecialTimeCharging extends BaseModel
{
    /**
     * 特殊时段计费表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'special_time_charging';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'transport_price_id',
        'start',
        'end',
        'price',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function getStatusNameAttribute()
    {
        return ConstTranslateTrait::$orderStatusList[$this->status];
    }
}
