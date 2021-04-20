<?php
/**
 * Hunan NLE Network Technology Co., Ltd
 * User : Zelin Ning(NiZerin)
 * Date : 4/7/2021
 * Time : 3:31 PM
 * Email: i@nizer.in
 * Blog : nizer.in
 * FileName: OrderDefaultConfigValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class OrderDefaultConfigValidate
 * @package App\Http\Validate\Api\Admin
 */
class OrderDefaultConfigValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'type' => 'required|integer|between:1,4',
        'settlement_type' => 'required|integer|between:1,5',
        'receipt_type' => 'required|integer|between:1,1',
        'receipt_count' => 'required|integer|gte:0',
        'control_mode' => 'required|integer|between:1,2',
        'nature' => 'required|integer|between:1,2,3',
        'address_template_id' => 'required|integer|between:1,2',
    ];

    /**
     * @var array
     */
    public $scene = [
        'update' => [
            'type',
            'settlement_type',
            'receipt_type',
            'receipt_count',
            'control_mode',
            'nature',
            'address_template_id',
        ]
    ];
}
