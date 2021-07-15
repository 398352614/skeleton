<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/19/2021
 * Time : 2:19 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: EmailTemplateValidate.php
 */


namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

/**
 * Class EmailTemplateValidate
 * @package App\Http\Validate\Api\Admin
 */
class EmailTemplateValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'type' => 'required|integer|between:1,3',
        'title' => 'required|string|max:50',
        'content' => 'required|string|max:500',
        'status' => 'required|integer|between:1,2'
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'type',
            'title',
            'content',
        ],

        'update' => [
            'type',
            'title',
            'content',
            'status'
        ]
    ];
}
