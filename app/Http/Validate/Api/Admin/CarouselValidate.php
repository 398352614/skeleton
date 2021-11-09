<?php

namespace App\Http\Validate\Api\Admin;

use App\Http\Validate\BaseValidate;

class CarouselValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'status'=>'nullable|integer|in:1,2',
        'name'=> 'nullable|string|max:50',
        'picture_url'=> 'required|string|max:250',
        'sort_id'=> 'nullable|string|max:50',
        'rolling_time'=> 'required|integer',
        'jump_type'=> 'required|string|in:1,2',
        'inside_jump_type'=> 'required_if:jump_type,1|integer|in:1,2,3,4',
        'outside_jump_url'=> 'required_if:jump_type,2|string|max:250',
    ];

    public $scene = [
        'store' => [
            'status', 'name', 'picture_url', 'sort_id',
            'rolling_time', 'jump_type', 'inside_jump_type', 'outside_jump_url'
        ],
        'update' => [
            'status', 'name', 'picture_url', 'sort_id',
            'rolling_time', 'jump_type', 'inside_jump_type', 'outside_jump_url'
        ],
        'updateSort' => [
            'id_list'
        ],
    ];

    public $message = [

    ];
}

