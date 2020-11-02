<?php

namespace App\Http\Validate\Api\Driver;

use App\Http\Validate\BaseValidate;

class TourTaskValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'batch_id' => 'required|integer',
        'tracking_order_id' => 'required|integer'
    ];

    public $scene = [
        'getBatchSpecialRemarkList' => ['batch_id'],
        'getSpecialRemark' => ['tracking_order_id']
    ];
}

