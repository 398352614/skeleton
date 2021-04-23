<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 员工表
 * Class Employee
 * @package App\Models
 */
class Employee extends Authenticatable implements JWTSubject
{
    use HasRoles;
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
        'is_admin',
        'warehouse_id',
        'address',
        'avatar',
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
     * @param \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Role $roleOrPermission
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        //不判断守卫
    }

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

    protected $appends = [
        'forbid_login_name',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function companyConfig()
    {
        return $this->belongsTo(CompanyConfig::class, 'company_id', 'company_id');
    }

    public function getForbidLoginNameAttribute()
    {
        return empty($this->forbid_login) ? null : ConstTranslateTrait::employeeForbidLoginList($this->forbid_login);
    }
}
