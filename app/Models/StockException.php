<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 站点异常表
 * Class Employee
 * @package App\Models
 */
class StockException extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stock_exception';

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
        'stock_exception_no',
        'tracking_order_no',
        'order_no',
        'express_first_no',
        'driver_id',
        'driver_name',
        'remark',
        'status',

        'deal_remark',
        'deal_time',
        'operator',
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
        return ConstTranslateTrait::stockExceptionStatusList($this->status);
    }

}
