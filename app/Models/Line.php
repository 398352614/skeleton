<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 线路表
 * Class Employee
 * @package App\Models
 */
class Line extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'line';

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
        'rule',
        'name',
        'country',
        'remark',
        'warehouse_id',
        'pickup_max_count',
        'pie_max_count',
        'is_increment',
        'can_skip_batch',
        'order_deadline',
        'appointment_days',
        'status',
        'creator_id',
        'creator_name',
        'created_at',
        'updated_at',
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

    protected $appends = [
        'country_name',
        'can_skip_batch_name'
    ];

    public function getCanSkipBatchNameAttribute()
    {
        return empty($this->can_skip_batch) ? null : ConstTranslateTrait::canSkipBatchList($this->can_skip_batch);
    }
}
