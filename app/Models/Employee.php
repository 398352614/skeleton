<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 员工表
 * Class Employee
 * @package App\Models
 */
class Employee extends Authenticatable implements JWTSubject
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee';

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
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'email',
        'username',
        'phone',
        'encrypt',
        'password',
        'fullname',
        'auth_group_id',
        'institution_id',
        'remark',
        'forbid_login',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * @var array
     */
    protected $casts = [
        'forbid_login' => 'bool',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => 'employee',
        ];
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function companyConfig()
    {
        return $this->belongsTo(CompanyConfig::class, 'company_id', 'company_id');
    }
}
