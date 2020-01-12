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
        'car_no' => '车牌号',
        'outgoing_time' => '出厂日期',
        'car_brand_id' => '车辆品牌ID',
        'car_model_id' => '汽车型号id',
        'frame_number' => '车架号',
        'engine_number' => '发动机编号',
        'transmission' => '车型',
        'fuel_type' => '燃料类型',
        'current_miles' => '当前里程数',
        'annual_inspection_data' => '下次年检日期',
        'ownership_type' => '类型',
        'received_date' => '接收车辆日期',
        'month_road_tax' => '每月路税',
        'insurance_company' => '保险公司',
        'insurance_type' => '保险类型',
        'month_insurance' => '每月保险',
        'rent_start_date' => '起租时间',
        'rent_end_date' => '到期时间',
        'rent_month_fee' => '月租金',
        'repair' => '维修自理',
        'remark' => '备注',
        'relate_material' => '文件',
        'relate_material_name' => '相关文件名',
        'is_locked' => '是否锁定1-正常2-锁定',

        'cn_name' =>'中文名称',
        'en_name' =>'英文名称',
        'brand_id'      =>  ['品牌ID'],
    ];

    public $rules = [
        'car_no' => 'required|string|uniqueIgnore:car,id',
        'outgoing_time' => 'required|date_format:Y-m-d',
        'car_brand_id' => 'required|integer',
        'car_model_id' => 'required|integer',
        'frame_number' => 'nullable|string',
        'engine_number' => 'nullable|string',
        'transmission' => 'required|integer|in:1,2',
        'fuel_type' => 'nullable|integer|min:1|max:4',
        'current_miles' => 'nullable|numeric',
        'annual_inspection_date' => 'required|date_format:Y-m-d',
        'ownership_type' => 'required|integer|between:1,3',
        'received_date' => 'required|date_format:Y-m-d',
        'month_road_tax' => 'required|numeric',
        'insurance_company' => 'required|string',
        'insurance_type' => 'required',
        'month_insurance' => 'nullable|numeric',
        'rent_start_date' => 'nullable|required_unless:ownership_type,2|date_format:Y-m-d',
        'rent_end_date' => 'nullable|required_unless:ownership_type,2|date_format:Y-m-d',
        'rent_month_fee' => 'nullable|required_unless:ownership_type,2',
        'repair' => 'nullable|required_unless:ownership_type,2|integer|in:1,2',
        'remark' => 'nullable|string',
        'relate_material' => 'nullable|string',
        'relate_material_name'=> 'nullable|string',
        'is_locked' => 'required|integer|in:1,2',

        'cn_name' =>'required|string|uniqueIgnore:car_brand,id,company_id|uniqueIgnore:car_model,id,company_id',
        'en_name' =>'required|string|uniqueIgnore:car_brand,id,company_id|uniqueIgnore:car_model,id,company_id',
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
            'annual_inspection_date',
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
            'relate_material_name'
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
            'annual_inspection_date',
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
            'relate_material_name'

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

