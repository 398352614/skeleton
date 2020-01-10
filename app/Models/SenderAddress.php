<?php

namespace App\Models;

class SenderAddress extends BaseModel
{
    protected $table = 'sender_address';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $hidden = [];
    protected $dates = [];

    protected $fillable = [
        'company_id',

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
