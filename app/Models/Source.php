<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 来源 表
 * Class Source
 * @package App\Models
 */
class Source extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'source';

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

        'source_name',

        'created_at',
        'updated_at',
        ];
}
