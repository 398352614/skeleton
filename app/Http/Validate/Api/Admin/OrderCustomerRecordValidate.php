<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/26/2021
 * Time : 2:50 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderCustomerRecordValidate.php
 */


namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

/**
 * Class OrderCustomerRecordValidate
 * @package App\Http\Validate\Api\Admin
 */
class OrderCustomerRecordValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'order_no' => 'required|string|exists:order,order_no',
        'content' => 'string|max:500',
        'file_urls' => 'array',
        'picture_urls' => 'array'
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'order_no',
            'content',
            'file_urls',
            'picture_urls'
        ],

        'list' => [
            'order_no'
        ]
    ];
}
