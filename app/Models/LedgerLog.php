<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主余额
 * Class Employee
 * @package App\Models
 */
class LedgerLog extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ledger_log';

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
        'user_id',
        'user_type',
        'user_name',
        'user_code',
        'credit',
        'pay_type',
        'verify_type',
        'status',
        'operator_type',
        'operator_id',
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
        'pay_type_name',
        'status_name',
        'verify_type_name',
        'operator_type_name',
        'user_type_name'
    ];

    protected $appends = [
    ];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::ledgerStatusList($this->status);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->pay_type) ? null : ConstTranslateTrait::payTypeList($this->pay_type);
    }

    public function getVerifyTypeNameAttribute()
    {
        return empty($this->verify_type) ? null : ConstTranslateTrait::ledgerVerifyTypeList($this->verify_type);
    }

    public function getOperatorTypeNameAttribute()
    {
        return empty($this->operator_type) ? null : ConstTranslateTrait::userTypeList($this->operator_type);
    }

    public function getUserTypeNameAttribute()
    {
        return empty($this->user_type) ? null : ConstTranslateTrait::userTypeList($this->user_type);
    }
}
