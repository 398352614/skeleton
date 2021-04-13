<?php

namespace App\Models;
/**
 * 公司表
 * Class Employee
 * @package App\Models
 */
class Company extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';

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
        'company_code',
        'email',
        'name',
        'contacts',
        'phone',
        'country',
        'address',
        'lon',
        'lat',
        'web_site',
        'system_name',
        'logo_url',
        'login_logo_url',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [
        'country_name'
    ];

    public function companyConfig()
    {
        return $this->hasOne(CompanyConfig::class, 'company_id', 'id');
    }
}
