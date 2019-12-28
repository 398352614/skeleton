<?php
/**
 * 常量定义
 * User: long
 * Date: 2019/12/20
 * Time: 14:50
 */

namespace App\Services;
class BaseConstService
{
    const TMS = 'TMS';
    const BATCH = 'BATCH';
    const TOUR = 'TOUR';

    //订单号编号类型
    const ORDER_NO_TYPE = 'order';
    //取派批次编号类型
    const BATCH_NO_TYPE = 'batch';
    //取件线路编号类型
    const TOUR_NO_TYPE = 'tour';

    //分配状态1-分配,2-取消分配
    const ASSIGN_YES = 1;
    const ASSIGN_CANCEL = 2;

    //星期定义
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 0;

    //订单类型1-取2-派
    const ORDER_TYPE_1 = 1;
    const ORDER_TYPE_2 = 2;

    //订单结算方式1-寄付2-到付
    const ORDER_SETTLEMENT_TYPE_1 = 1;
    const ORDER_SETTLEMENT_TYPE_2 = 2;

    //订单状态1-未取派2-已分配3-取派中4-已签收5-异常6-收回站
    const ORDER_STATUS_1 = 1;
    const ORDER_STATUS_2 = 2;
    const ORDER_STATUS_3 = 3;
    const ORDER_STATUS_4 = 4;
    const ORDER_STATUS_5 = 5;
    const ORDER_STATUS_6 = 6;

    //订单异常状态1-正常2-签收异常3-在途异常4-装货异常
    const ORDER_EXCEPTION_TYPE_1 = 1;
    const ORDER_EXCEPTION_TYPE_2 = 2;
    const ORDER_EXCEPTION_TYPE_3 = 3;
    const ORDER_EXCEPTION_TYPE_4 = 4;

    //订单性质1-包裹2-材料3-文件4-增值服务5-其他
    const ORDER_NATURE_1 = 1;
    const ORDER_NATURE_2 = 2;
    const ORDER_NATURE_3 = 3;
    const ORDER_NATURE_4 = 4;
    const ORDER_NATURE_5 = 5;

    //取件线路状态状态：1-待分配2-已分配-3-待出库4-取派中5-取派完成
    const TOUR_STATUS_1 = 1;
    const TOUR_STATUS_2 = 2;
    const TOUR_STATUS_3 = 3;
    const TOUR_STATUS_4 = 4;
    const TOUR_STATUS_5 = 5;

    //tour 在途状态  1-配送中 2-已完成 3-异常
    const TOUR_STATUS_DELIVERYING = 1;
    const TOUR_STATUS_COMPLETED = 2;
    const TOUR_STATUS_ERROR = 3;

    //tour_log 在途动作常量  1-初始化线路 2-更新司机位置 3-更新线路
    const TOUR_LOG_INIT = 1;
    const TOUR_LOG_UPDATE_DRIVER = 2;
    const TOUR_LOG_UPDATE_LINE = 3;

    //tour_log 在途日志状态  1-处理中 2-已完成 3-异常
    const TOUR_LOG_PENDING = 1;
    const TOUR_LOG_COMPLETE = 2;
    const TOUR_LOG_ERROR = 3;

    //batch 批次站点的状态 状态：1-待分配2-已分配3-取派中4-已签收5-异常
    const BATCH_WAIT_ASSIGN = 1;
    const BATCH_ASSIGNED = 2;
    const BATCH_DELIVERING = 3;
    const BATCH_CHECKOUT = 4;
    const BATCH_ERROR = 5;

    //driver 司机合作类型
    const DRIVER_HIRE = 1;
    const DRIVER_CONTRACTOR = 2;

    //driver 司机状态
    const DRIVER_TO_NORMAL = 1;
    const DRIVER_TO_LOCK = 2;

    //车辆状态
    const CAR_TO_NORMAL = 1;
    const CAR_TO_LOCK = 2;

}