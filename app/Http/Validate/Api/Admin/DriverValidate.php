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
        'last_name'                     => '姓',
        'first_name'                    => '名',
        'gender'                        => '性别',
        'birthday'                      => '生日',
        'phone'                         => '手机',
        'duty_paragraph'                => '税号',
        'post_code'                     => '邮编',
        'door_no'                       => '门牌号',
        'street'                        => '街道',
        'city'                          => '城市',
        'country'                       => '国家',
        'lisence_number'                => '驾照编号',
        'lisence_valid_date'            => '有效期',
        'lisence_type'                  => '驾照类型',
        'lisence_material'              => '驾照材料',
        'government_material'           => '政府信件',
        'avatar'                        => '头像',
        'bank_name'                     => '银行名称',
        'iban'                          => 'IBAN',
        'bic'                           => 'BIC',
        'crop_type'                     => '合作类型',
        'is_locked'                     => '是否锁定',
    ];

    public $rules = [
        'email'                         => ['required', 'uniqueIgnore:driver,id'],
        'password'                      => ['required'],
        'last_name'                     => ['required'],
        'first_name'                    => ['required'],
        'gender'                        => ['required'],
        'birthday'                      => ['required', 'date_format:Y-m-d'],
        'phone'                         => ['required', 'uniqueIgnore:driver,id'],
        'duty_paragraph'                => ['required'],
        'post_code'                     => ['required'],
        'door_no'                       => ['required'],
        'street'                        => ['required'],
        'city'                          => ['required'],
        'country'                       => ['required'],
        'lisence_number'                => ['required'],
        'lisence_valid_date'            => ['required'],
        'lisence_type'                  => ['required'],
        'lisence_material'              => ['required'],
        'government_material'           => ['required'],
        'avatar'                        => ['required'],
        'bank_name'                     => ['nullable'],
        'iban'                          => ['nullable'],
        'bic'                           => ['nullable'],
        'crop_type'                     => ['required'],
        'is_locked'                     => ['required', 'integer', 'in:1,2'],
    ];
    public $scene = [
        //注册
        'driverRegister'             => [
            'email',
            'password',
            'last_name',
            'first_name',
            'gender',
            'birthday',
            'phone',
            'duty_paragraph',
            'post_code',
            'door_no',
            'street',
            'city',
            'country',
            'lisence_number',
            'lisence_valid_date',
            'lisence_type',
            'lisence_material',
            'government_material',
            'avatar',
            'bank_name',
            'iban',
            'bic',
            // 'crop_type',
        ],
        'update'             => [
            'last_name',
            'first_name',
            'gender',
            'birthday',
            'phone',
            'duty_paragraph',
            'post_code',
            'door_no',
            'street',
            'city',
            'country',
            'lisence_number',
            'lisence_valid_date',
            'lisence_type',
            'lisence_material',
            'government_material',
            'avatar',
            'bank_name',
            'iban',
            'bic',
            // 'crop_type',
        ],
        'resetPassword' => ['password'],
        'lockDriver' => ['is_locked']
    ];
}
