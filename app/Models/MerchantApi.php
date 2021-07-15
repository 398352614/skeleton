<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 货主api表
 * Class Employee
 * @package App\Models
 */
class MerchantApi extends Authenticatable
{
    /**
     * 司机实际取件导航
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant_api';

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
        'merchant_id',
        'key',
        'secret',
        'push_mode',
        'url',
        'white_ip_list',
        'status',
        'created_at',
        'updated_at',
        'recharge_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends = [
        'status_name'
    ];


    public function getStatusNameAttribute()
    {
        return empty($this->status) ? null : ConstTranslateTrait::statusList($this->status);
    }


    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->secret;
    }
}
