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

    //订单类型1-取2-派
    public static $orderTypeList = [
        BaseConstService::ORDER_TYPE_1 => '取件',
        BaseConstService::ORDER_TYPE_2 => '派件',
    ];

    //订单结算方式1-寄付2-到付
    public static $orderSettlementTypeList = [
        BaseConstService::ORDER_SETTLEMENT_TYPE_1 => '寄付',
        BaseConstService::ORDER_SETTLEMENT_TYPE_2 => '到付',
    ];

    //订单状态类型1-未取派2-已分配3-取派中4-已签收5-异常6-收回站
    public static $orderStatusList = [
        BaseConstService::ORDER_STATUS_1 => '未取派',
        BaseConstService::ORDER_STATUS_2 => '已分配',
        BaseConstService::ORDER_STATUS_3 => '取派中',
        BaseConstService::ORDER_STATUS_4 => '已完成',
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

    //订单性质1-包裹2-材料3-文件4-增值服务5-其他
    public static $orderNatureList = [
        BaseConstService::ORDER_NATURE_1  => '包裹',
        BaseConstService::ORDER_NATURE_2  => '材料',
        BaseConstService::ORDER_NATURE_3  => '文件',
        BaseConstService::ORDER_NATURE_4  => '增值服务',
        BaseConstService::ORDER_NATURE_5  => '其他'
    ];

    //batch 批次状态 1-未取派2-已分配3-取派中4-已签收5-异常
    public static $batchStatusList = [
        BaseConstService::BATCH_WAIT_ASSIGN     =>  '待分配',
        BaseConstService::BATCH_ASSIGNED        =>  '已分配',
        BaseConstService::BATCH_DELIVERING      =>  '取派中',
        BaseConstService::BATCH_CHECKOUT        =>  '已签收',
        BaseConstService::BATCH_ERROR           =>  '异常',
    ];

    //在途类型-
    public static $tourStatusList = [
        BaseConstService::TOUR_STATUS_DELIVERYING   => '配送中',
        BaseConstService::TOUR_STATUS_COMPLETED     => '配送中',
        BaseConstService::TOUR_STATUS_ERROR         => '配送中',
    ];

    //司机合作类型
    public static $driverTypeList = [
        BaseConstService::DRIVER_HIRE               => '雇佣',
        BaseConstService::DRIVER_CONTRACTOR         => '包线',
    ];

    //司机合作类型
    public static $driverStatusList = [
        BaseConstService::DRIVER_TO_NORMAL              => '正常',
        BaseConstService::DRIVER_TO_LOCK                => '锁定',
    ];
}