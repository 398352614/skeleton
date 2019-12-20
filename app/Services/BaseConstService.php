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
    //订单号编号类型
    const ORDER_NO_TYPE = 'order';
    //取派批次编号类型
    const BATCH_NO_TYPE = 'batch';
    //取件线路编号类型
    const TOUR_NO_TYPE = 'tour';

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


}