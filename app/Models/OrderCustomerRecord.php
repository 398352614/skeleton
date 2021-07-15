<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/26/2021
 * Time : 2:34 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderCustomerRecord.php
 */


namespace App\Models;


/**
 * Class OrderCustomerRecord
 * @package App\Models
 */
class OrderCustomerRecord extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_customer_record';

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
        'content',
        'file_urls',
        'picture_urls',
        'operator_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @param $value
     */
    public function setFileUrlsAttribute($value)
    {
        $this->attributes['file_urls'] = empty($value) ? '[]' : json_encode($value, 256) ?? '[]';
    }

    /**
     * @param $value
     * @return array|mixed
     */
    public function getFileUrlsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * @param $value
     */
    public function setPictureUrlsAttribute($value)
    {
        $this->attributes['picture_urls'] = empty($value) ? '[]' : json_encode($value, 256) ?? '[]';
    }

    /**
     * @param $value
     * @return array|mixed
     */
    public function getPictureUrlsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
