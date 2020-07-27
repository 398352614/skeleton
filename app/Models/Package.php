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
        'type',
        'name',
        'express_first_no',
        'express_second_no',
        'feature_logo',
        'out_order_no',
        'weight',
        'expect_quantity',
        'actual_quantity',
        'status',
        'sticker_no',
        'sticker_amount',
        'remark',
        'is_auth',
        'auth_fullname',
        'auth_birth_date',
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

    protected $appends = [
        'status_name',
        'type_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];


    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::packageStatusList($this->status);
    }

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::orderTypeList($this->type);
    }
}
