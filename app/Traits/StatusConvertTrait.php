<?php

/**
 * 状态转换 trait
 * User: long
 * Date: 2020/1/14
 * Time: 13:42
 */

namespace App\Traits;

use App\Services\BaseConstService;

trait StatusConvertTrait
{
    public static function statusConvertByType($params, $data)
    {
        if (!empty($params) && collect($params[0])->has('status')) {
            for ($i = 0, $j = count($params); $i < $j; $i++) {
                if (in_array($params[$i]['status'], array_keys($data))) {
                    $params[$i]['merchant_status'] = $data[$params[$i]['status']];
                    $params[$i]['merchant_status_name'] = ConstTranslateTrait::merchantPackageStatusList($params[$i]['merchant_status']);
                }
            }
        }
        return $params;
    }

}
