<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 流水
 * Class Employee
 * @package App\Models
 */
class Journal extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'journal';

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
        'journal_no',
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
        'actual_amount',
        'payer_id',
        'payer_type',//
        'payer_name',
        'payee_id',
        'payee_type',//
        'payee_name',
        'operator_id',
        'operator_type',//
        'operator_name',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'mode_name',
        'type_name',
        'object_type_name',
        'pay_type_name',
        'payer_type_name',
        'payee_type_name',
        'operator_type_name',
    ];

    protected $appends = [
    ];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::billStatusList($this->status);
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

    //type是自定义的
//    public function getTypeNameAttribute()
//    {
//        return empty($this->type) ? null : ConstTranslateTrait::billTypeList($this->type);
//    }

    public function getCreateTimingNameAttribute()
    {
        return empty($this->create_timing) ? null : ConstTranslateTrait::billCreateTimingList($this->create_timing);
    }

    public function getPayTimingNameAttribute()
    {
        return empty($this->pay_timing) ? null : ConstTranslateTrait::billPayTimingList($this->pay_timing);
    }
}
