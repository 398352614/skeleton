<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/12/2021
 * Time : 4:18 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarMaintain.php
 */


namespace App\Models;


use App\Traits\ConstTranslateTrait;

/**
 * Class CarMaintain
 * @package App\Models
 */
class CarMaintain extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_maintain';

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
        'maintain_no',
        'car_id',
        'car_no',
        'distance',
        'maintain_type',
        'maintain_date',
        'maintain_factory',
        'is_ticket',
        'maintain_description',
        'maintain_picture',
        'maintain_price',
        'operator',
        'created_at',
        'updated_at',
    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getMaintainType($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carMaintainType($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getIsTicket($value): string
    {
        return empty($value) ? '' : ConstTranslateTrait::carMaintainTicket($value);
    }
}
