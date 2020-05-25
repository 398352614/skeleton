<?php

namespace App\Models;

use App\Traits\CountryTrait;

/**
 * 发件人地址 表
 * Class Source
 * @package App\Models
 */
class SenderAddress extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sender_address';

    /**
     * The primary key for the model.
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
    protected $hidden = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [
        'merchant_id_name',
        'short',
        'sender_country_name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'merchant_id',
        'sender_fullname',
        'sender_phone',
        'sender_country',
        'sender_post_code',
        'sender_house_number',
        'sender_city',
        'sender_street',
        'sender_address',
        'created_at',
        'updated_at',
    ];

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
        return empty($this->sender_country) ? null : $this->getOriginal('sender_country');
    }
}
