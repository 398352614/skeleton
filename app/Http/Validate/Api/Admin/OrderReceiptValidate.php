<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 3/29/2021
 * Time : 4:02 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: OrderReceiptValidate.php
 */


namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

/**
 * Class OrderReceiptValidate
 * @package App\Http\Validate\Api\Admin
 */
class OrderReceiptValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'order_no' => 'required|string|exists:order,order_no',
        'file_name' => 'required|string',
        'file_type' => 'required|string',
        'file_size' => 'required|integer',
        'file_url' => 'required|string|url'
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'order_no',
            'file_name',
            'file_type',
            'file_size',
            'file_url'
        ],

        'list' => [
            'order_no'
        ],

        'update' => [
            'file_name',
        ]
    ];
}
