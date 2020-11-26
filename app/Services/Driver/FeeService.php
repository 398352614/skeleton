<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/6/28
 * Time: 15:50
 */

namespace App\Services\Driver;

use App\Models\Fee;

class FeeService extends BaseService
{
    public function __construct(Fee $model, $resource = null, $infoResource = null)
    {
        parent::__construct($model, $resource, $infoResource);
    }

    //获取所有费用列表
    public function getAllFeeList(){
        return parent::getList(['company_id'=>auth()->user()->company_id],['code','name','amount','level','status'],false)->toArray();
    }
}
