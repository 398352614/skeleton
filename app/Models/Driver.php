<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 司机表
 * Class Employee
 * @package App\Models
 */
class Driver extends Authenticatable implements JWTSubject
{
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
        'email',
        'encrypt',
        'password',
        'last_name',
        'first_name',
        'gender',
        'birthday',
        'phone',
        'duty_paragraph',
        'post_code',
        'door_no',
        'street',
        'city',
        'country',
        'lisence_number',
        'lisence_valid_date',
        'lisence_type',
        'lisence_material',
        'government_material',
        'avatar',
        'bank_name',
        'iban',
        'bic',
        'is_locked',
        'crop_type',
        'created_at',
        'updated_at',
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
        'is_locked_name'
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

    public function getIsLockedNameAttribute()
    {
        return empty($this->is_locked) ? null : ConstTranslateTrait::$driverStatusList[$this->is_locked];
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name . ' ' . $this->first_name;
    }
}
