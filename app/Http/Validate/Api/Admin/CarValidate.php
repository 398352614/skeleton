<?php
/**
 * 订单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CarValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'car_no' => 'required|string|uniqueIgnore:car,id',
        'outgoing_time' => 'required|date_format:Y-m-d',
        'car_brand_id' => 'required|integer',
        'car_model_id' => 'required|integer',
        'frame_number' => 'string',
        'engine_number' => 'string',
        'transmission' => 'required|integer|in:1,2',
        'fuel_type' => 'required|integer|min:1|max:4',
        'current_miles' => 'numeric',
        'annual_inspection_data' => 'required|date_format:Y-m-d',
        'ownership_type' => 'required|integer|between:1,3',
        'received_date' => 'required|date_format:Y-m-d',
        'month_road_tax' => 'required|numeric',
        'insurance_company' => 'required|string',
        'insurance_type' => 'required',
        'month_insurance' => 'numeric',
        'rent_start_date' => 'nullable|required_unless:ownership_type,2|date_format:Y-m-d',
        'rent_end_date' => 'nullable|required_unless:ownership_type,2|date_format:Y-m-d',
        'rent_month_fee' => 'nullable|required_unless:ownership_type,2',
        'repair' => 'nullable|required_if:ownership_type,2|integer|in:1,2',
        'remark' => 'nullable|string',
        'relate_material' => 'required|string',
        'is_locked' => 'required|integer|in:1,2',

        'cn_name'      =>  ['required'],
        'en_name'       =>  ['nullable'],
        'brand_id'      =>  ['required'],
    ];
    public $scene = [
        //保存
        'store'             => [
            'car_no',
            'outgoing_time',
            'car_brand_id',
            'car_model_id',
            'frame_number',
            'engine_number',
            'transmission',
            'fuel_type',
            'current_miles',
            'annual_inspection_data',
            'ownership_type',
            'received_date',
            'month_road_tax',
            'insurance_company',
            'insurance_type',
            'month_insurance',
            'rent_start_date',
            'rent_end_date',
            'rent_month_fee',
            'repair',
            'remark',
            'relate_material',
        ],
        'update'             => [
            'car_no',
            'outgoing_time',
            'car_brand_id',
            'car_model_id',
            'frame_number',
            'engine_number',
            'transmission',
            'fuel_type',
            'current_miles',
            'annual_inspection_data',
            'ownership_type',
            'received_date',
            'month_road_tax',
            'insurance_company',
            'insurance_type',
            'month_insurance',
            'rent_start_date',
            'rent_end_date',
            'rent_month_fee',
            'repair',
            'remark',
            'relate_material',
        ],
        'addBrand'      => [
            'cn_name', 'en_name'
        ],
        'addModel'      => [
            'cn_name', 'en_name', 'brand_id'
        ],

        'lock' =>[
            'is_locked',
        ]
    ];
}

