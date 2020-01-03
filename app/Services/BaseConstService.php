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
    const BATCH_EXCEPTION = 'BE';

    //订单号编号类型
    const ORDER_NO_TYPE = 'order';
    //站点编号类型
    const BATCH_NO_TYPE = 'batch';
    //取件线路编号类型
    const TOUR_NO_TYPE = 'tour';
    //站点编号类型
    const BATCH_EXCEPTION_NO_TYPE = 'batch_exception';

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

    //订单状态1-未取派2-已分配3-待出库4-取派中5-已签收6-异常7-取消取派8-收回站
    const ORDER_STATUS_1 = 1;
    const ORDER_STATUS_2 = 2;
    const ORDER_STATUS_3 = 3;
    const ORDER_STATUS_4 = 4;
    const ORDER_STATUS_5 = 5;
    const ORDER_STATUS_6 = 6;
    const ORDER_STATUS_7 = 7;
    const ORDER_STATUS_8 = 8;

    //订单异常状态1-正常2-在途异常3-装货异常
    const ORDER_EXCEPTION_TYPE_1 = 1;
    const ORDER_EXCEPTION_TYPE_2 = 2;
    const ORDER_EXCEPTION_TYPE_3 = 3;

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

    //batch 批次站点的状态 状态：1-待分配2-已分配3-待出库4-取派中5-已签收6-异常7-取消取派
    const BATCH_WAIT_ASSIGN = 1;
    const BATCH_ASSIGNED = 2;
    const BATCH_WAIT_OUT = 3;
    const BATCH_DELIVERING = 4;
    const BATCH_CHECKOUT = 5;
    const BATCH_ERROR = 6;
    const BATCH_CANCEL = 7;

    //batch exception状态1-未处理2-已处理
    const BATCH_EXCEPTION_1 = 1;
    const BATCH_EXCEPTION_2 = 2;

    //batch exception 异常阶段 1-在途异常2-装货异常
    const BATCH_EXCEPTION_STAGE_1 = 1;
    const BATCH_EXCEPTION_STAGE_2 = 2;

    //异常阶段1-在途阶段 异常类型1-道路2-车辆异常3-其他
    const BATCH_EXCEPTION_STAGE_1_TYPE_1 = 1;
    const BATCH_EXCEPTION_STAGE_1_TYPE_2 = 2;
    const BATCH_EXCEPTION_STAGE_1_TYPE_3 = 3;

    //异常阶段2-装货异常 异常类型1-少货2-货损3-其他
    const BATCH_EXCEPTION_STAGE_2_TYPE_1 = 1;
    const BATCH_EXCEPTION_STAGE_2_TYPE_2 = 2;
    const BATCH_EXCEPTION_STAGE_2_TYPE_3 = 3;

    //站点支付类型1-现金支付2-银行支付
    const BATCH_PAY_TYPE_1 = 1;
    const BATCH_PAY_TYPE_2 = 2;

    //driver 司机合作类型
    const DRIVER_HIRE = 1;
    const DRIVER_CONTRACTOR = 2;

    //driver 司机状态
    const DRIVER_TO_NORMAL = 1;
    const DRIVER_TO_LOCK = 2;

    //车辆状态
    const CAR_TO_NORMAL = 1;
    const CAR_TO_LOCK = 2;


    //管理员端 图片目录
    const ADMIN_IMAGE_DRIVER_DIR = 'driver';

    //司机端 图片目录
    const DRIVER_IMAGE_TOUR_DIR = 'tour';


    //管理员端 文件目录
    const ADMIN_FILE_DRIVER_DIR = 'driver';
    const ADMIN_FILE_CAR_DIR = 'car';

    //司机端 文件目录
    const DRIVER_FILE_TOUR_DIR = 'tour';

    //order_trail 订单轨迹常量  1-订单创建 2-加入线路 3-已分配司机 4-已收入网点 5-待出库 6-配送中 7-已签收 8-取消取派
    const ORDER_TRAIL_CREATED               = 1;
    const ORDER_TRAIL_JOIN_BATCH             = 2;
    const ORDER_TRAIL_ASSIGN_DRIVER         = 3;
    const ORDER_TRAIL_REVENUE_OUTLETS       = 4;
    const ORDER_TRAIL_LOCK                  = 5;
    const ORDER_TRAIL_DELIVERING            = 6;
    const ORDER_TRAIL_DELIVERED             = 7;
    const ORDER_TRAIL_CANNEL_DELIVER        = 8;
}
