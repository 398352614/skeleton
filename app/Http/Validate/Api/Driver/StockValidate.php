<?php


namespace App\Http\Validate\Api\Driver;


use App\Http\Validate\BaseValidate;

class StockValidate extends BaseValidate
{
    public $customAttributes = [

    ];

    public $rules = [
        'express_first_no' => 'required|string|max:250',
    ];

    public $scene = [
        'packagePickOut' => ['express_first_no'],
    ];

    public $message = [
    ];
}
