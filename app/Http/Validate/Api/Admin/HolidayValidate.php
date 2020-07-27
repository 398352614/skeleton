<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class HolidayValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'name' => 'required|string|max:100|uniqueIgnore:company,id',
        'date_list' => 'required|string',
        'status' => 'required|integer|in:1,2',
        'merchant_id_list' => 'required|string|checkIdList:10',
        'merchant_id' => 'required|integer'
    ];

    public $scene = [
        'store' => ['name', 'date_list'],
        'update' => ['name', 'date_list'],
        'storeMerchantList' => ['merchant_id_list'],
        'destroyMerchant' => ['merchant_id']
    ];
}

