<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 司机表
 * Class Employee
 * @package App\Models
 */
class Driver extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'driver';

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
        'warehouse_id',
        'warehouse_name',
        'timezone',
        'email',
        'encrypt',
        'password',
        'fullname',
        'gender',
        'birthday',
        'phone',
        'duty_paragraph',
        'address',
        'country',
        'lisence_number',
        'lisence_valid_date',
        'lisence_type',
        'lisence_material',
        'lisence_material_name',
        'government_material',
        'government_material_name',
        'avatar',
        'bank_name',
        'iban',
        'bic',
        'is_locked',
        'crop_type',
        'created_at',
        'updated_at',
        'type'
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
        'is_locked_name',
        'country_name'
    ];

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
            'role' => 'driver',
        ];
    }

    /**
     * @return null
     */
    public function getIsLockedNameAttribute()
    {
        return empty($this->is_locked) ? null : ConstTranslateTrait::driverStatusList($this->is_locked);
    }

    /**
     * @param $value
     * @return string
     */
    public function getType($value)
    {
        return empty($value) ? '' : ConstTranslateTrait::driverTypeList($value);
    }
}
