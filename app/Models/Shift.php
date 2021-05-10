<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Carbon\CarbonInterval;

/**
 * bag 袋，转运过程中的包裹集合，相当于取派过程中的订单。
 * Class Bag
 * @package App\Models
 */
class Shift extends BaseModel
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
     * 袋表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shift';
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
        'shift_no',
        'status',
        'weight',
        'package_count',
        'driver_id',
        'driver_name',
        'car_no',
        'car_id',
        'remark',
        'begin_time',
        'end_time',
        'expect_time',
        'actual_time',
        'expect_distance',
        'actual_distance',
        'warehouse_id',
        'warehouse_name',
        'next_warehouse_id',
        'next_warehouse_name',
        'created_at',
        'updated_at',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'status_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::shiftStatusList($this->status);
    }
}
