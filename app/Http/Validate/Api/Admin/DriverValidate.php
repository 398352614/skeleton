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
        'email'                         => '用户邮箱',
        'password'                      => '密码',
        'fullname'                      => '姓名',
        'gender'                        => '性别',
        'birthday'                      => '生日',
        'phone'                         => '手机',
        'duty_paragraph'                => '税号',
        'address'                       => '邮编',
        'country'                       => '国家',
        'lisence_number'                => '驾照编号',
        'lisence_valid_date'            => '有效期',
        'lisence_type'                  => '驾照类型',
        'lisence_material'              => '驾照材料',
        'lisence_material_name'              => '驾照材料名',
        'government_material'           => '政府信件',
        'government_material_name'           => '政府信件名',
        'avatar'                        => '头像',
        'bank_name'                     => '银行名称',
        'iban'                          => 'IBAN',
        'bic'                           => 'BIC',
        'crop_type'                     => '合作类型',
        'is_locked'                     => '是否锁定',
    ];

    public $rules = [
        'email'                         => 'required|string|max:50|uniqueIgnore:driver,id',
        'password'                      => 'required|string|max:100',
        'new_password'                  => 'required|string|max:100',
        'confirm_new_password'          => 'required|string|max:100|same:new_password',
        'fullname'                      => 'required|string|max:50',
        'gender'                        => 'nullable|string|max:10',
        'birthday'                      => 'nullable|date|date_format:Y-m-d',
        'phone'                         => 'required|string|max:20|uniqueIgnore:driver,id|regex:/^[0-9]([0-9-])*[0-9]$/',
        'duty_paragraph'                => 'nullable|string|max:50',
        'address'                       => 'nullable|string|max:50',
        'country'                       => 'required|string|max:100',
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
    ];
    public $scene = [
        //注册
        'driverRegister'             => [
            'email',
            'password',
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
            // 'crop_type',
        ],
        'resetPassword' => ['new_password','confirm_new_password'],
        'lockDriver' => ['is_locked']
    ];
}
