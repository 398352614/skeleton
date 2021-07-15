<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/15/2021
 * Time : 11:42 AM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: CarMaintainDetail.php
 */


namespace App\Models;

/**
 * Class CarMaintainDetail
 * @package App\Models
 */
class CarMaintainDetail extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_maintain_detail';

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
        'maintain_name',
        'fitting_name',
        'fitting_brand',
        'fitting_model',
        'fitting_quantity',
        'fitting_unit',
        'fitting_price',
        'material_price',
        'hour_price',
        'created_at',
        'updated_at',
    ];
}
