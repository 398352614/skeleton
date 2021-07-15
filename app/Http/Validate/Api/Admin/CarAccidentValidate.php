<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/11/2021
 * Time : 3:59 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarAccidentValidate.php
 */


namespace App\Http\Validate\Api\Admin;


use App\Http\Validate\BaseValidate;

/**
 * Class CarAccidentValidate
 * @package App\Http\Validate\Api\Admin
 */
class CarAccidentValidate extends BaseValidate
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
        'driver_id' => 'required|integer|exists:driver,id',
        'driver_fullname' => 'required|string|max:50',
        'driver_phone' => 'required|string|max:50',
        'deal_type' => 'required|in:1,2|integer',
        'accident_location' => 'required|string|max:20',
        'accident_date' => 'required|date',
        'accident_duty' => 'required|in:1,2|integer',
        'accident_description' => 'string',
        'accident_picture' => 'string',
        'insurance_indemnity' => 'required_if:deal_type,1|in:1,2|integer',
        'insurance_payment' => 'required_if:deal_type,1|numeric',
        'insurance_description' => 'string',
        'insurance_price' => 'required_if:insurance_indemnity,1|numeric',
        'insurance_date' => 'required_if:insurance_indemnity,1|date'
    ];

    /**
     * @var \string[][]
     */
    public $scene = [
        'store' => [
            'car_id',
            'driver_id',
            'car_no',
            'driver_fullname',
            'driver_phone',
            'deal_type',
            'accident_location',
            'accident_date',
            'accident_duty',
            'accident_description',
            'accident_picture',
            'insurance_indemnity',
            'insurance_payment',
            'insurance_description',
            'insurance_price',
            'insurance_date'
        ],

        'update' => [
            'car_id',
            'driver_id',
            'car_no',
            'driver_fullname',
            'driver_phone',
            'deal_type',
            'accident_location',
            'accident_date',
            'accident_duty',
            'accident_description',
            'accident_picture',
            'insurance_indemnity',
            'insurance_payment',
            'insurance_description',
            'insurance_price',
            'insurance_date'
        ]
    ];
}
