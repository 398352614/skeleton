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
        BaseConstService::ORDER_STATUS_3 => '待出库',
        BaseConstService::ORDER_STATUS_4 => '取派中',
        BaseConstService::ORDER_STATUS_5 => '已完成',
        BaseConstService::ORDER_STATUS_6 => '取消取派',
        BaseConstService::ORDER_STATUS_7 => '收回站',
    ];

    //订单异常状态1-正常2-签收异常3-在途异常4-装货异常
    public static $orderExceptionTypeList = [
        BaseConstService::ORDER_EXCEPTION_TYPE_1 => '正常',
        BaseConstService::ORDER_EXCEPTION_TYPE_2 => '在途异常',
        BaseConstService::ORDER_EXCEPTION_TYPE_3 => '装货异常',
    ];

    //订单性质1-包裹2-材料3-文件4-增值服务5-其他
    public static $orderNatureList = [
        BaseConstService::ORDER_NATURE_1 => '包裹',
        BaseConstService::ORDER_NATURE_2 => '材料',
        BaseConstService::ORDER_NATURE_3 => '文件',
        BaseConstService::ORDER_NATURE_4 => '增值服务',
        BaseConstService::ORDER_NATURE_5 => '其他'
    ];

    //batch 批次状态 1-未取派2-已分配3-取派中4-已签收5-异常
    public static $batchStatusList = [
        BaseConstService::BATCH_WAIT_ASSIGN => '待分配',
        BaseConstService::BATCH_ASSIGNED => '已分配',
        BaseConstService::BATCH_WAIT_OUT => '待出库',
        BaseConstService::BATCH_DELIVERING => '取派中',
        BaseConstService::BATCH_CHECKOUT => '已签收',
        BaseConstService::BATCH_CANCEL => '取消取派',
    ];

    //batch exception 状态1-未处理2-已处理
    public static $batchExceptionStatusList = [
        BaseConstService::BATCH_EXCEPTION_1 => '未处理',
        BaseConstService::BATCH_EXCEPTION_2 => '已处理',
    ];

    //batch exception 异常阶段1-在途异常2-装货异常
    public static $batchExceptionStageList = [
        BaseConstService::BATCH_EXCEPTION_STAGE_1 => '在途异常',
        BaseConstService::BATCH_EXCEPTION_STAGE_2 => '装货异常',
    ];

    //batch exception 异常阶段1-在途阶段 异常类型1-道路2-车辆异常3-其他
    public static $batchExceptionFirstStageTypeList = [
        BaseConstService::BATCH_EXCEPTION_STAGE_1_TYPE_1 => '道路',
        BaseConstService::BATCH_EXCEPTION_STAGE_1_TYPE_2 => '车辆异常',
        BaseConstService::BATCH_EXCEPTION_STAGE_1_TYPE_3 => '其他',
    ];

    //batch exception 异常阶段2-装货异常 异常类型1-少货2-货损3-其他
    public static $batchExceptionSecondStageTypeList = [
        BaseConstService::BATCH_EXCEPTION_STAGE_2_TYPE_1 => '少货',
        BaseConstService::BATCH_EXCEPTION_STAGE_2_TYPE_2 => '货损',
        BaseConstService::BATCH_EXCEPTION_STAGE_2_TYPE_3 => '其他',
    ];


    //在途类型1-待分配-2-已分配3-待出库4-取派中5-取派完成
    public static $tourStatusList = [
        BaseConstService::TOUR_STATUS_1 => '待分配',
        BaseConstService::TOUR_STATUS_2 => '已分配',
        BaseConstService::TOUR_STATUS_3 => '待出库',
        BaseConstService::TOUR_STATUS_4 => '取派中',
        BaseConstService::TOUR_STATUS_5 => '取派完成',
    ];

    //司机合作类型
    public static $driverTypeList = [
        BaseConstService::DRIVER_HIRE => '雇佣',
        BaseConstService::DRIVER_CONTRACTOR => '包线',
    ];

    //司机合作类型
    public static $driverStatusList = [
        BaseConstService::DRIVER_TO_NORMAL => '正常',
        BaseConstService::DRIVER_TO_LOCK => '锁定',
    ];


    //管理员端 图片目录
    public static $adminImageDirList = [
        BaseConstService::ADMIN_IMAGE_DRIVER_DIR => '司机图片目录',
    ];

    //司机端 图片目录
    public static $driverImageDirList = [
        BaseConstService::DRIVER_IMAGE_TOUR_DIR => '取件线路图片目录'
    ];

    //管理员端 文件目录
    public static $adminFileDirList = [
        BaseConstService::ADMIN_FILE_DRIVER_DIR => '司机文件目录',
        BaseConstService::ADMIN_FILE_CAR_DIR => '车辆文件目录'
    ];

    //司机端 文件目录
    public static $driverFileDirList = [
        BaseConstService::DRIVER_FILE_TOUR_DIR => '取件线路文件目录'
    ];
}
