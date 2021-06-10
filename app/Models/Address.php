<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 收货方 表
 * Class Employee
 * @package App\Models
 */
class Address extends BaseModel
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

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
        'place_fullname',
        'merchant_id',
        'place_phone',
        'place_country',
        'place_province',
        'place_post_code',
        'place_house_number',
        'place_city',
        'place_district',
        'place_street',
        'place_address',
        'place_lon',
        'place_lat',
        'created_at',
        'updated_at',
        'unique_code',
        'type'
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
        'type_name',
        'place_country_name',
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

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::addressTypeList($this->type);
    }

    public function getShortAttribute()
    {
        return empty($this->place_country) ? null : $this->getOriginal('place_country');
    }
}
