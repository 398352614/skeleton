<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/14/2021
 * Time : 2:51 PM
 * Email: nzl199851@gmail.com
 * Blog : nizer.in
 * FileName: CarMaintainValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class CarMaintainValidate
 * @package App\Http\Validate\Api\Admin
 */
class CarMaintainValidate extends BaseValidate
{
    /**
     * @var array
     */
    public $customAttributes = [

    ];

    /**
     * @var string[]
     */
    public $rules = [
        'car_id' => 'required|integer|exists:car,id',
        'car_no' => 'required|string|max:50',
        'maintain_type' => 'required|in:1,2',
        'maintain_date' => 'required|date',
        'maintain_factory' => 'string|max:50',
        'is_ticket' => 'required|in:1,2',
        'maintain_description' => 'string',
        'maintain_detail' => 'required|array',
        'maintain_picture' => 'required|string',
        'maintain_price' => 'required|numeric|gte:0'
    ];

    /**
     * @var \string[][]
     */
    public $scene = [
        'store' => [
            'car_id',
            'car_no',
            'maintain_type',
            'maintain_date',
            'maintain_factory',
            'is_ticket',
            'maintain_description',
            'maintain_detail'
        ]
    ];
}
