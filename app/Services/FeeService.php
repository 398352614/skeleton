<?php
/**
 * 费用服务
 * User: long
 * Date: 2020/6/22
 * Time: 13:49
 */

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Models\Fee;
use App\Traits\SearchTrait;

class FeeService
{
    /**
     * 获取费用
     * @param $where
     * @return float
     * @throws BusinessLogicException
     */
    public static function getFeeAmount($where)
    {
        $query = Fee::query();
        SearchTrait::buildQuery($query, $where);
        $fee = $query->first();
        if (empty($fee)) {
            return 0;
        }
        return floatval($fee->amount);
    }
}
