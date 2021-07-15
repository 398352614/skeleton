<?php
/**
 * 运单 验证类
 * Created by PhpStorm
 * User: long
 * Date: 2020/11/02
 * Time: 15:06
 */

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class TrackingOrderValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'execution_date' => 'required|date|after_or_equal:today',
        'batch_no' => 'nullable|string|max:50',
        'tour_no' => 'required|string|max:50',
        'remark' => 'nullable|string|max:250',
        'id_list' => 'required|string|checkIdList:100',
        'tracking_order_id_list' => 'required|string|checkIdList:100',
        'out_status' => 'required|integer|in:1,2'
    ];

    public $scene = [
        'removeListFromBatch' => ['id_list'],
        'getAbleBatchList' => ['execution_date'],
        'assignToBatch' => ['execution_date', 'batch_no'],
        'assignListTour' => ['tracking_id_list', 'tour_no'],
        'changeOutStatus' => ['id_list', 'out_status']
    ];
}

