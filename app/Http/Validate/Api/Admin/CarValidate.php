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
        'outgoing_time' => 'nullable|date_format:Y-m-d',
        'car_brand_id' => 'required|integer',
        'car_model_id' => 'required|integer',
        'ownership_type' => 'nullable|integer|between:1,4',
        'insurance_company' => 'nullable|string|max:50',
        'insurance_type' => 'nullable|string|max:50',
        'month_insurance' => 'nullable|numeric|gte:0',
        'rent_start_date' => 'nullable',
        'rent_end_date' => 'nullable|date_format:Y-m-d',
        'rent_month_fee' => 'nullable|numeric|gte:0',
        'repair' => 'nullable|integer|in:1,2',
        'remark' => 'nullable|string',
        'relate_material' => 'nullable|string',
        'relate_material_name' => 'nullable|string',
        'is_locked' => 'required|integer|in:1,2',
        'begin_date' => 'required|date',
        'end_date' => 'required|date',
        'cn_name' => 'required|string|uniqueIgnore:car_brand,id,company_id|uniqueIgnore:car_model,id,company_id',
        'en_name' => 'required|string|uniqueIgnore:car_brand,id,company_id|uniqueIgnore:car_model,id,company_id',
        'brand_id' => 'required',
        'car_length' => 'required|integer|between:1,18',
        'gps_device_number' => 'string',
        'car_model_type' => 'required|integer|between:1,13',

        //包裹列表
        'relate_material_list.*.name' => 'nullable|string|max:50',
        'relate_material_list.*.url' => 'nullable|string|max:250',
    ];
    public $scene = [
        //保存
        'store' => [
            'car_no',
            'car_brand_id',
//            'car_model_id',
            'ownership_type',
            'insurance_company',
            'insurance_type',
            'month_insurance',
            'rent_start_date',
            'rent_end_date',
            'rent_month_fee',
            'repair',
            'remark',
            'relate_material',
            'relate_material_name',
            //包裹列表
            'relate_material_list.*.material_name',
            'relate_material_list.*.material_url',
            'car_length',
            'gps_device_number',
            'car_model_type'
        ],
        'update' => [
            'car_no',
            'car_brand_id',
//            'car_model_id',
            'ownership_type',
            'insurance_company',
            'insurance_type',
            'month_insurance',
            'rent_start_date',
            'rent_end_date',
            'rent_month_fee',
            'repair',
            'remark',
            'relate_material',
            'relate_material_name',
            //包裹列表
            'relate_material_list.*.material_name',
            'relate_material_list.*.material_url',
            'car_length',
            'gps_device_number',
            'car_model_type'
        ],
        'lock' => [
            'is_locked',
        ],
        'distanceExport' => [
            'begin_date',
            'end_date'
        ]
    ];
}

