<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 库存表
 * Class Employee
 * @package App\Models
 */
class Stock extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock';

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
        'warehouse_id',
        'line_id',
        'line_name',
        'order_no',
        'tracking_order_no',
        'express_first_no',
        'execution_date',
        'expiration_date',
        'expiration_status',
        'weight',
        'operator',
        'operator_id',
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

    protected $appends = [
        'expiration_status_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getExpirationStatusNameAttribute()
    {
        return (empty($this->expiration_status) ) ? null : ConstTranslateTrait::expirationStatusList($this->expiration_status);
    }
}
