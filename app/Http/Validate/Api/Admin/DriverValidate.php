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

class DriverValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'email'                         => ['required','uniqueIgnore:driver,id'],
        'password'                      => ['required'],
        'last_name'                     => ['required'],
        'first_name'                    => ['required'],
        'gender'                        => ['required'],
        'birthday'                      => ['required'],
        'phone'                         => ['required','uniqueIgnore:driver,id'],
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
        'bank_name'                     => ['required'],
        'iban'                          => ['required'],
        'bic'                           => ['required'],
        'crop_type'                     =>  ['required'],
        // 'is_locked'                     => 0,
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
            'crop_type',
        ],
    ];
}

