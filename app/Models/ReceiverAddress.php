<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;

/**
 * 收货方 表
 * Class Employee
 * @package App\Models
 */
class ReceiverAddress extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'receiver_address';

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
        'receiver_fullname',
        'merchant_id',
        'receiver_phone',
        'receiver_country',
        'receiver_post_code',
        'receiver_house_number',
        'receiver_city',
        'receiver_street',
        'receiver_address',
        'receiver_lon',
        'receiver_lat',
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
        'merchant_id_name',
        'short',
        'receiver_country_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getMerchantIdNameAttribute()
    {
        if (empty($this->merchant) || empty($this->merchant_id)) return '';
        return $this->merchant->name;
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function getShortAttribute()
    {
        return empty($this->receiver_country) ? null : $this->getOriginal('receiver_country');
    }
}
