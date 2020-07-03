<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class TourTaskValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'batch_id' => 'required|integer',
        'order_id' => 'required|integer',
    ];

    public $scene = [
        'getBatchSpecialRemarkList' => ['batch_id'],
        'getSpecialRemark' => ['order_id'],
    ];
}

