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
    //星期
    public static $weekList = [
        BaseConstService::MONDAY => '星期一',
        BaseConstService::TUESDAY => '星期二',
        BaseConstService::WEDNESDAY => '星期三',
        BaseConstService::THURSDAY => '星期四',
        BaseConstService::FRIDAY => '星期五',
        BaseConstService::SATURDAY => '星期六',
        BaseConstService::SUNDAY => '星期日',
    ];

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
}