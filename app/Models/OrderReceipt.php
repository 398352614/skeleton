<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/28/2021
 * Time : 4:25 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderReceipt.php
 */


namespace App\Models;

/**
 * Class OrderReceipt
 * @package App\Models
 */
class OrderReceipt extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_receipt';

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'order_no',
        'company_id',
        'file_name',
        'file_type',
        'file_size',
        'file_url',
        'operator_id',
        'operator_type',
        'created_at',
        'updated_at',
    ];
}
