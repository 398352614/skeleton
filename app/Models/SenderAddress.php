<?php

namespace App\Models;

/**
 * 发件人地址 表
 * Class Source
 * @package App\Models
 */
class SenderAddress extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sender_address';

    /**
     * The primary key for the model.
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
    protected $hidden = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'merchant_id',
        'sender',
        'sender_phone',
        'sender_country',
        'sender_post_code',
        'sender_house_number',
        'sender_city',
        'sender_street',
        'sender_address',

        'created_at',
        'updated_at',
    ];
}
