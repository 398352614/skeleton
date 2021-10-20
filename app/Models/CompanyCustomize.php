<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 自定义界面 表
 * Class Employee
 * @package App\Models
 */
class CompanyCustomize extends BaseModel
{
    /**
     * 自定义界面
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_customize';

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
        'status',
        'admin_url',
        'admin_login_background',
        'admin_login_title',
        'admin_main_logo',
        'merchant_url',
        'merchant_login_background',
        'merchant_login_title',
        'merchant_main_logo',
        'driver_login_title',
        'consumer_url',
        'consumer_login_title',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $appends = [
        'status_name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * @var string[]
     */
    protected $casts = [

    ];

    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::statusList($this->status);
    }

}
