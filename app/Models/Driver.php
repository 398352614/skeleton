<?php

namespace App\Models;
/**
 * 司机表
 * Class Employee
 * @package App\Models
 */
class Driver extends BaseModel
{
    /**
     * 司机实际取件导航
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
}
