<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class BatchValidate extends BaseValidate
{
    public $customAttributes = [
        'tour_no' => '取件线路编号',
        'execution_date' => '取派日期',
        'cancel_type' => '取消取派类型',
        'cancel_remark' => '取消取派内容',
        'cancel_picture' => '取消取派图片',
    ];


    public $rules = [
        'batch_no' => 'nullable|string|max:50',
        'execution_date' => 'required|date|after_or_equal:today',
        'cancel_type' => 'required|integer|in:1,2,3',
        'cancel_remark' => 'required|string|max:250',
        'cancel_picture' => 'nullable|string|max:250',
    ];

    public $scene = [
        'cancel' => ['cancel_type', 'cancel_remark', 'cancel_picture'],
        'getTourList' => ['execution_date'],
        'assignToTour' => ['execution_date', 'tour_no']
    ];
}

