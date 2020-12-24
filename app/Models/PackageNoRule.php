<?php

namespace App\Models;

/**
 *  顺带包裹单号规则表
 * Class Employee
 * @package App\Models
 */
class PackageNoRule extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'package_no_rule';

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
        'name',
        'prefix',
        'length',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $appends = [
    ];
}
