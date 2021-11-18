<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 支付单
 * Class Employee
 * @package App\Models
 */
class Paypal extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paypal';

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
        'merchant_id',
        'merchant_name',
        'payment_id',
        'status',
        'amount',
        'currency_unit_type',
        'verify_no',
        'object_no',
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
        'currency_unit_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::paypalStatusList($this->status);
    }

    /**
     * @return string
     */
    public function getCurrencyUnitNameAttribute()
    {
        return empty($this->currency_unit) ? '' : ConstTranslateTrait::currencyUnitTypeList($this->currency_unit);
    }
}
