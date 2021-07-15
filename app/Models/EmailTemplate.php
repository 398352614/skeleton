<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/19/2021
 * Time : 11:49 AM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: EmailTemplate.php
 */


namespace App\Models;

use App\Traits\ConstTranslateTrait;

/**
 * 邮件模板
 * Class EmailTemplate
 * @package App\Models
 */
class EmailTemplate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_template';

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
    protected $appends = [
        'type_name',
        'status_name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'type',
        'title',
        'content',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'int'
    ];

    /**
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return empty($this->type) ? '' : ConstTranslateTrait::emailTemplateTypeList($this->type);
    }

    /**
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return empty($this->status) ? '' : ConstTranslateTrait::emailTemplateStatusList($this->status);
    }
}
