<?php

/**
 * 司机 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/16
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class DriverValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'email'                         => 'required|string|max:50|uniqueIgnore:driver,id',
        'password'                      => 'required|string|max:100',
        'confirm_password'              => 'required|string|max:100|same:password',
        'new_password'                  => 'required|string|max:100',
        'confirm_new_password'          => 'required|string|max:100|same:new_password',
        'fullname'                      => 'required|string|max:50|uniqueIgnore:driver,id,company_id',
        'gender'                        => 'nullable|string|max:10',
        'birthday'                      => 'nullable|date|date_format:Y-m-d',
        'phone'                         => 'required|string|max:20|uniqueIgnore:driver,id|regex:/^[0-9 ]([0-9- ])*[0-9 ]$/',
        'duty_paragraph'                => 'nullable|string|max:50',
        'address'                       => 'nullable|string|max:50',
        'country'                       => 'nullable|string|max:100',
        'lisence_number'                => 'nullable|string|max:50',
        'lisence_valid_date'            => 'nullable|date|date_format:Y-m-d',
        'lisence_type'                  => 'nullable|string|max:100',
        'lisence_material'              => 'nullable|string|max:250',
        'lisence_material_name'         => 'nullable|string|max:250',
        'government_material'           => 'nullable|string|max:250',
        'government_material_name'      => 'nullable|string|max:250',
        'avatar'                        => 'nullable|string|max:250',
        'bank_name'                     => 'nullable|string|max:100',
        'iban'                          => 'nullable|string|max:100',
        'bic'                           => 'nullable|string|max:100',
        'crop_type'                     => 'required|integer|in:1,2',
        'is_locked'                     => 'required|integer|in:1,2',
        'type'                          => 'required|integer|in:1,2,3,4',
        'warehouse_id'                  => 'nullable|integer'

    ];
    public $scene = [
        //注册
        'driverRegister'             => [
            'email',
            'password',
            'confirm_password',
            'fullname',
            'gender',
            'birthday',
            'phone',
            'duty_paragraph',
            'address',
            'country',
            'lisence_number',
            'lisence_valid_date',
            'lisence_type',
            'lisence_material',
            'lisence_material_name',
            'government_material',
            'government_material_name',
            'avatar',
            'bank_name',
            'iban',
            'bic',
            'type',
            'warehouse_id'
            // 'crop_type',
        ],
        'update'             => [
            'fullname',
            'gender',
            'birthday',
            'phone',
            'duty_paragraph',
            'address',
            'country',
            'lisence_number',
            'lisence_valid_date',
            'lisence_type',
            'lisence_material',
            'lisence_material_name',
            'government_material',
            'government_material_name',
            'avatar',
            'bank_name',
            'iban',
            'bic',
            'type',
            'warehouse_id'
            // 'crop_type',
        ],
        'resetPassword' => ['new_password','confirm_new_password'],
        'lockDriver' => ['is_locked']
    ];
}
