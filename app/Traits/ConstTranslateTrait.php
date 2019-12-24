<?php
/**
 * 常量翻译 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 17:02
 */

namespace App\Traits;

use App\Services\BaseConstService;

trait ConstTranslateTrait
{
    //订单状态类型1-未取派2-已分配3-取派中4-已签收5-异常6-收回站
    public static $orderStatusList = [
        BaseConstService::ORDER_STATUS_1 => '未取派',
        BaseConstService::ORDER_STATUS_2 => '已分配',
        BaseConstService::ORDER_STATUS_3 => '取派中',
        BaseConstService::ORDER_STATUS_4 => '已签收',
        BaseConstService::ORDER_STATUS_5 => '异常',
        BaseConstService::ORDER_STATUS_6 => '收回站',
    ];

    //订单异常状态1-正常2-签收异常3-在途异常4-装货异常
    public static $orderExceptionTypeList = [
        BaseConstService::ORDER_EXCEPTION_TYPE_1 => '正常',
        BaseConstService::ORDER_EXCEPTION_TYPE_2 => '签收异常',
        BaseConstService::ORDER_EXCEPTION_TYPE_3 => '在途异常',
        BaseConstService::ORDER_EXCEPTION_TYPE_4 => '装货异常',
    ];

    //在途类型-
    public static $tourStatusList = [
        BaseConstService::TOUR_STATUS_DELIVERYING   => '配送中',
        BaseConstService::TOUR_STATUS_COMPLETED     => '配送中',
        BaseConstService::TOUR_STATUS_ERROR         => '配送中',
    ];
}