<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主组表
 * Class Employee
 * @package App\Models
 */
class MerchantGroup extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant_group';

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
        'transport_price_id',
        'transport_price_name',
        'count',
        'name',
        'is_default',
        'additional_status',
        'advance_days',
        'appointment_days',
        'delay_time',
        'pickup_count',
        'pie_count',
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
        'additional_status_name',
    ];

    public function getAdditionalStatusNameAttribute()
    {
        return empty($this->additional_status) ? null : ConstTranslateTrait::merchantAdditionalStatusList($this->additional_status);
    }


}
