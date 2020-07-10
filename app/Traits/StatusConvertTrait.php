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
     * @return array
     */
    public static function statusConvert(array $params)
    {
            $tourConvertList = [
                BaseConstService::TOUR_STATUS_1 => BaseConstService::MERCHANT_TOUR_STATUS_1,
                BaseConstService::TOUR_STATUS_2 => BaseConstService::MERCHANT_TOUR_STATUS_1,
                BaseConstService::TOUR_STATUS_3 => BaseConstService::MERCHANT_TOUR_STATUS_1,
                BaseConstService::TOUR_STATUS_4 => BaseConstService::MERCHANT_TOUR_STATUS_2,
                BaseConstService::TOUR_STATUS_5 => BaseConstService::MERCHANT_TOUR_STATUS_3,
            ];
            if (!empty($params) && collect($params[0])->has('status')) {
                for ($i = 0, $j = count($params); $i < $j; $i++) {
                    //如果是前五个状态就翻译，否则不翻译
                    if (in_array($params[$i]['status'], $tourConvertList)) {
                        $params[$i]['merchant_status'] = $tourConvertList[$params[$i]['status']];
                        $params[$i]['merchant_status_name'] = ConstTranslateTrait::merchantTourStatusList($params[$i]['merchant_status']);
                    } else {
                        $params[$i]['merchant_status'] = $params[$i]['status'];
                        $params[$i]['merchant_status_name'] = ConstTranslateTrait::tourStatusList($params[$i]['status']);
                    }

                }
            }
        return $params;
    }
}
