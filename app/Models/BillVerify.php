<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主余额
 * Class Employee
 * @package App\Models
 */
class BillVerify extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill_verify';

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
        'verify_no',
        'status',
        'pay_type',
        'expect_amount',
        'actual_amount',
        'remark',
        'picture_list',
        'operator_id',
        'operator_type',
        'operator_name',
        'verify_time',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pay_type_name',
        'operator_type_name',
        'status_name',
    ];

    protected $appends = [
    ];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::billVerifyStatusList($this->status);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->pay_type) ? null : ConstTranslateTrait::payTypeList($this->pay_type);
    }


    public function getOperatorTypeNameAttribute()
    {
        return empty($this->operator_type) ? null : ConstTranslateTrait::userTypeList($this->operator_type);
    }

}
