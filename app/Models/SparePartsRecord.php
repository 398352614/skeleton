<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 3:30 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsRecord.php
 */


namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * Class SparePartsRecord
 * @package App\Models
 */
class SparePartsRecord extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spare_parts_record';

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
        'company_id',
        'sp_no',
        'car_id',
        'car_no',
        'receive_price',
        'receive_quantity',
        'receive_date',
        'receive_person',
        'receive_remark',
        'receive_status',
        'created_at',
        'updated_at',
    ];

    /**
     * @param  int  $value
     * @return mixed
     */
    public function getReceiveStatus(int $value)
    {
        return ConstTranslateTrait::sparePartsRecordStatus($value);
    }

    /**
     * @param  int  $value
     * @return mixed
     */
    public function getSpUnit(int $value)
    {
        return ConstTranslateTrait::sparePartsUnit($value);
    }
}
