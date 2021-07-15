<?php

namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 运价操作日志
 * Class OrderImport
 * @package App\Models
 */
class TransportPriceOperation extends BaseModel
{
    /**
     *
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transport_price_operation';

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
        'transport_price_id',
        'operation',
        'operator',
        'content',
        'second_content',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'operation_name'
    ];

    public function getOperationNameAttribute()
    {
        return empty($this->operation) ? null : ConstTranslateTrait::operationList($this->operation);
    }

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
}
