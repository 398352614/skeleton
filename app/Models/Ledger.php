<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主余额
 * Class Employee
 * @package App\Models
 */
class Ledger extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ledger';

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
        'balance',
        'credit',
        'create_date',
        'pay_type',
        'verify_type',
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
        'pay_type_name',
        'verify_type_name',
        'status_name'
    ];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::ledgerStatusList($this->status);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->payType) ? null : ConstTranslateTrait::payTypeList($this->payType);
    }

    public function getVerifyNameAttribute()
    {
        return empty($this->verifyType) ? null : ConstTranslateTrait::ledgerVerifyTypeList($this->verifyType);
    }


}
