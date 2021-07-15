<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 3:58 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsRecordValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class SparePartsRecordValidate
 * @package App\Http\Validate\Api\Admin
 */
class SparePartsRecordValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'car_id' => 'required|integer|exists:car,id',
        'sp_no' => 'required|string|exists:spare_parts,sp_no',
        'car_no' => 'required|string',
        'receive_price' => 'required|numeric',
        'receive_quantity' => 'required|integer|gte:1',
        'receive_person' => 'required|string|max:50',
        'receive_remark' => 'string',
        'receive_date' => 'date',
        'receive_status' => 'integer|in:1,2'
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'car_id',
            'sp_no',
            'car_no',
            'receive_price',
            'receive_quantity',
            'receive_person',
            'receive_remark',
            'receive_date',
        ],

        'index' => [
            'receive_status'
        ]
    ];
}
