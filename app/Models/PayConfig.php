<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 轮播图
 * Class Carousel
 * @package App\Models
 */
class PayConfig extends BaseModel
{
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
     * 袋表
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pay_config';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'waiting_time',
        'paypal_sandbox_mode',
        'paypal_client_id',
        'paypal_client_secret',
        'paypal_status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $appends = [
        'paypal_status_name',
        'paypal_sandbox_mode_name',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public function getPaypalStatusNameAttribute()
    {
        return empty($this->paypal_status) ? null : ConstTranslateTrait::statusList($this->paypal_status);
    }

    public function getPaypalSandboxModeNameAttribute()
    {
        return empty($this->paypal_sandbox_mode) ? null : ConstTranslateTrait::paypalSandboxModeList($this->paypal_sandbox_mode);
    }

}
