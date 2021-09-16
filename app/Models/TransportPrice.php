<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 运价表
 * Class Employee
 * @package App\Models
 */
class TransportPrice extends BaseModel
{
    /**
     * 运价表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transport_price';

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
        'starting_price',
        'type',
        'remark',
        'status',
        'pay_type',
        'payer_type',
        'payee_type',
        'pay_timing',
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

    protected $appends = [
        'type_name',
        'pay_timing_name',
        'pay_type_name',
        'payer_type_name',
        'payee_type_name',
    ];

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::transportPriceTypeList($this->type);
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
        return empty($this->payer_type) ? null : ConstTranslateTrait::feePayerTypeList($this->payer_type);
    }

    public function getPayeeTypeNameAttribute()
    {
        return empty($this->payee_type) ? null : ConstTranslateTrait::userTypeList($this->payee_type);
    }
}
