<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 包裹表
 * Class Employee
 * @package App\Models
 */
class Package extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package';

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
        'tour_no',
        'batch_no',
        'order_no',
        'name',
        'express_first_no',
        'express_second_no',
        'out_order_no',
        'weight',
        'quantity',
        'remark',
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
