<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class BatchValidate extends BaseValidate
{
    public $customAttributes = [

    ];


    public $rules = [
        'batch_no' => 'nullable|string|max:50',
        'line_id'=>'required|integer',
        'execution_date' => 'required|date|after_or_equal:today',
        'cancel_type' => 'required|integer|in:1,2,3',
        'cancel_remark' => 'required|string|max:250',
        'cancel_picture' => 'nullable|string|max:250',
    ];

    public $scene = [
        'cancel' => ['cancel_type', 'cancel_remark', 'cancel_picture'],
        'getTourList' => ['execution_date'],
        'assignToTour' => ['execution_date', 'line_id']
    ];
}

