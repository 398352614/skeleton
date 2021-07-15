<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/24/2021
 * Time : 2:28 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsStockValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class SparePartsStockValidate
 * @package App\Http\Validate\Api\Admin
 */
class SparePartsStockValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'sp_no' => 'required|string|max:50|exists:spare_parts,sp_no',
        'stock_quantity' => 'required|integer|gte:0'
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'sp_no',
            'stock_quantity'
        ]
    ];
}
