<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use App\Traits\CountryTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 商户表
 * Class Employee
 * @package App\Models
 */
class Merchant extends Authenticatable implements JWTSubject
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant';

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
        'type',
        'name',
        'email',
        'password',
        'country',
        'settlement_type',
        'merchant_group_id',
        'contacter',
        'phone',
        'address',
        'avatar',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [
        'settlement_type_name',
        'status_name',
        'type_name',
        'country_name'
    ];


    public function getSettlementTypeNameAttribute()
    {
        return empty($this->settlement_type) ? null : ConstTranslateTrait::merchantSettlementTypeList($this->settlement_type);
    }

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::merchantStatusList($this->status);
    }

    public function getTypeNameAttribute()
    {
        return empty($this->type) ? null : ConstTranslateTrait::merchantTypeList($this->type);
    }

    public function getCountryNameAttribute()
    {
        return empty($this->country) ? null : CountryTrait::getCountryName($this->country);
    }


    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => 'merchant',
        ];
    }
}
