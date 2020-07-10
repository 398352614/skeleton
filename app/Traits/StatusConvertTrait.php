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

    /**
     * 取件线路状态映射
     * @param array $params
     * @param string $type
     * @return array
     */
    public static function statusConvert(array $params, $type = BaseConstService::PACKAGE)
    {
        if ($type == BaseConstService::PACKAGE) {
            return self::statusConvertByType($params, self::$packageConvertList);
        } else {
            return $params;
        }

    }

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

    static $packageConvertList = [
        BaseConstService::PACKAGE_STATUS_1 => BaseConstService::MERCHANT_PACKAGE_STATUS_1,
        BaseConstService::PACKAGE_STATUS_2 => BaseConstService::MERCHANT_PACKAGE_STATUS_1,
        BaseConstService::PACKAGE_STATUS_3 => BaseConstService::MERCHANT_PACKAGE_STATUS_1,
        BaseConstService::PACKAGE_STATUS_4 => BaseConstService::MERCHANT_PACKAGE_STATUS_2,
        BaseConstService::PACKAGE_STATUS_5 => BaseConstService::MERCHANT_PACKAGE_STATUS_3,
        BaseConstService::PACKAGE_STATUS_6 => BaseConstService::MERCHANT_PACKAGE_STATUS_4,
        BaseConstService::PACKAGE_STATUS_7 => BaseConstService::MERCHANT_PACKAGE_STATUS_5,
    ];

}
