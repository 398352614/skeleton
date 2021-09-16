<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主余额
 * Class Employee
 * @package App\Models
 */
class Bill extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bill';

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
        'bill_no',
        'verify_no',
        'object_type',//
        'object_no',
        'remark',
        'picture_list',
        'pay_type',//
        'mode',//
        'type',//
        'create_date',
        'expect_amount',
        'actual_amount',
        'status',//
        'verify_status',//
        'payer_id',
        'payer_type',//
        'payer_name',
        'payee_id',
        'payee_type',//
        'payee_name',
        'operator_id',
        'operator_type',//
        'operator_name',
        'create_timing',//
        'pay_timing',//
        'verify_time',
        'created_at',
        'updated_at',
        'fee_id',
        'fee_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $appends = [
        'mode_name',
        'create_timing_name',
        'pay_timing_name',
        'object_type_name',
        'pay_type_name',
        'payer_type_name',
        'payee_type_name',
        'operator_type_name',
        'status_name',
        'verify_status_name',
        'rest_amount',
        'type_name'
    ];

    public function getRestAmountAttribute()
    {
        return number_format($this->expect_amount - $this->actual—actual_amount, 2);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::billStatusList($this->status);
    }

    public function getObjectTypeNameAttribute()
    {
        return empty($this->object_type) ? null : ConstTranslateTrait::billObjectTypeList($this->object_type);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->pay_type) ? null : ConstTranslateTrait::payTypeList($this->pay_type);
    }

    public function getVerifyStatusNameAttribute()
    {
        return empty($this->verify_status) ? null : ConstTranslateTrait::billVerifyStatusList($this->verify_status);
    }

    public function getOperatorTypeNameAttribute()
    {
        return empty($this->operator_type) ? null : ConstTranslateTrait::userTypeList($this->operator_type);
    }

    public function getPayerTypeNameAttribute()
    {
        return empty($this->payer_type) ? null : ConstTranslateTrait::userTypeList($this->payer_type);
    }

    public function getPayeeTypeNameAttribute()
    {
        return empty($this->payee_type) ? null : ConstTranslateTrait::userTypeList($this->payee_type);
    }

    public function getModeNameAttribute()
    {
        return empty($this->mode) ? null : ConstTranslateTrait::billModeList($this->mode);
    }

    public function getTypeNameAttribute()
    {
        if (empty($this->type)) {
            return null;
        } else {
            if ($this->type < 3) {
                return ConstTranslateTrait::billTypeList($this->type);
            } else {
                return null;
            }
        }
    }

    public function getCreateTimingNameAttribute()
    {
        return empty($this->create_timing) ? null : ConstTranslateTrait::billCreateTimingList($this->create_timing);
    }

    public function getPayTimingNameAttribute()
    {
        return empty($this->pay_timing) ? null : ConstTranslateTrait::billPayTimingList($this->pay_timing);
    }
}
