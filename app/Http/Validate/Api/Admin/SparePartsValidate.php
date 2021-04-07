<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/23/2021
 * Time : 5:02 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: SparePartsValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class SparePartsValidate
 * @package App\Http\Validate\Api\Admin
 */
class SparePartsValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $rules = [
        'sp_no' => 'required|string|max:50|unique:spare_parts,sp_no',
        'sp_name' => 'required|string|max:50',
        'sp_brand' => 'required|string|max:50',
        'sp_model' => 'required|string|max:50',
        'sp_unit' => 'required|integer|between:1,11',
    ];

    /**
     * @var array
     */
    public $scene = [
        'store' => [
            'sp_no',
            'sp_name',
            'sp_brand',
            'sp_model',
            'sp_unit'
        ],

        'update' => [
            'sp_name',
            'sp_brand',
            'sp_model',
            'sp_unit'
        ]
    ];
}
