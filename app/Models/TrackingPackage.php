<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * bag 袋，转运过程中的包裹集合，相当于取派过程中的订单。
 * Class Bag
 * @package App\Models
 */
class TrackingPackage extends BaseModel
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
    protected $table = 'tracking_package';
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
        'express_first_no',
        'order_no',
        'bag_no',
        'shift_no',
        'status',
        'type',
        'distance_type',
        'weight',
        'warehouse_id',
        'warehouse_name',
        'next_warehouse_id',
        'next_warehouse_name',

        'pack_time',
        'pack_operator',
        'pack_operator_id',
        'unpack_time',
        'unpack_operator',
        'unpack_operator_id',
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
        return empty($this->status) ? null : ConstTranslateTrait::trackingPackageStatusList($this->status);
    }
}
