<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 费用 表
 * Class Employee
 * @package App\Models
 */
class Fee extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fee';

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
        'name',
        'code',
        'amount',
        'object_type',
        'level',
        'status',
        'is_valuable',
        'pay_type',
        'payer_type',
        'payee_type',
        'pay_timing',
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
        'level_name',
        'pay_timing_name',
        'object_type_name',
        'pay_type_name',
        'payer_type_name',
        'payee_type_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::merchantStatusList($this->status);
    }

    public function getLevelNameAttribute()
    {
        return empty($this->level) ? null : ConstTranslateTrait::feeLevelList($this->level);
    }

    public function getPayTimingNameAttribute()
    {
        return empty($this->pay_timing) ? null : ConstTranslateTrait::billPayTimingList($this->pay_timing);
    }

    public function getPayTypeNameAttribute()
    {
        return empty($this->pay_type) ? null : ConstTranslateTrait::payTypeList($this->pay_type);
    }

    public function getPayerTypeNameAttribute()
    {
        if (empty($this->pay_type)) {
            return null;
        } else {
            if ($this->pay_type > 3) {
                return ConstTranslateTrait::billTypeList($this->pay_type);
            } else {
                return null;
            }
        }
    }

    public function getPayeeTypeNameAttribute()
    {
        return empty($this->payee_type) ? null : ConstTranslateTrait::userTypeList($this->payee_type);
    }

    public function getObjectTypeNameAttribute()
    {
        return empty($this->object_type) ? null : ConstTranslateTrait::billObjectTypeList($this->object_type);
    }


}
