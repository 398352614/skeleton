<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 订单表
 * Class Employee
 * @package App\Models
 */
class OrderAmount extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_amount';

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
        'order_no',
        'expect_amount',
        'actual_amount',
        'type',
        'remark',
        'in_total',
        'status',
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
        'type_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::orderAmountTypeList($this->type);
    }
}
