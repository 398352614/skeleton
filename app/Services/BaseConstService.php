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
    const ORDER = 'ORDER';
    const TRACKING_ORDER = 'YD';
    const BATCH = 'BATCH';
    const TOUR = 'TOUR';
    const PACKAGE = 'PACKAGE';
    const MATERIAL = 'MATERIAL';
    const BATCH_EXCEPTION = 'BE';
    const STOCK_EXCEPTION = 'SE';
    const RECHARGE = '';

    const CANCEL_TIMES = 3;

    //邮编国家
    const POSTCODE_COUNTRY_BE = 'BE';

    //是否
    const YES = 1;
    const NO = 2;

    //权限类型1-菜单2-按钮
    const PERMISSION_TYPE_1 = 1;
    const PERMISSION_TYPE_2 = 2;

    //是否需要验证
    const IS_AUTH_1 = 1;
    const IS_AUTH_2 = 2;

    //1降序，2升序
    const SORT_BY_TIME_1 = 1;
    const SORT_BY_TIME_2 = 2;

    const TYPE_ORDER = 1;
    const TYPE_BATCH = 2;

    //线路规则1-邮编2-区域
    const LINE_RULE_POST_CODE = 1;
    const LINE_RULE_AREA = 2;

    //打印模板
    const PRINT_TEMPLATE_STANDARD = 1;
    const PRINT_TEMPLATE_GENERAL = 2;

    //订单号编号类型
    const ORDER_NO_TYPE = 'order';
    //运单类型
    const TRACKING_ORDER_NO_TYPE = 'tracking_order';
    //站点编号类型
    const BATCH_NO_TYPE = 'batch';
    //取件线路编号类型
    const TOUR_NO_TYPE = 'tour';
    //站点异常编号类型
    const BATCH_EXCEPTION_NO_TYPE = 'batch_exception';
    //入库异常编号类型
    const STOCK_EXCEPTION_NO_TYPE = 'stock_exception';
    //充值单号类型
    const RECHARGE_NO_TYPE = 'recharge';

    //初始密码
    const INITIAL_PASSWORD = '12345678';

    //费用编码
    const STICKER = 'STICKER';
    const DELIVERY = 'DELIVERY';

    //费用等级1-系统级2-自定义
    const FEE_LEVEL_1 = 1;
    const FEE_LEVEL_2 = 2;

    //编号规则最大长度
    const ORDER_NO_RULE_LENGTH = 13;

    //经纬度差距范围，小数点后6位为1米
    const LOCATION_DISTANCE_RANGE = 0.000001 * 1000;

    //停留记录时间(分钟)
    const STOP_TIME = 20;

    //坐标点上限
    const LOCATION_LIMIT = 100;

    //1-启用2-禁用
    const ON = 1;
    const OFF = 2;

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

    //临时处理 正式服-商户ID(有用到，请勿删除)
    const ERP_MERCHANT_ID_1 = 7;
    const SHOP_MERCHANT_ID_2 = 8;

    //临时处理 开发服-商户ID(有用到，请勿删除)
    const ERP_MERCHANT_ID_2 = 65;
    const SHOP_MERCHANT_ID_1 = 3;

    //订单类型1-取2-派3-取派
    const ORDER_TYPE_0 = 0;
    const ORDER_TYPE_1 = 1;
    const ORDER_TYPE_2 = 2;
    const ORDER_TYPE_3 = 3;

    //订单出库状态1-可出库2-不可出库
    const OUT_STATUS_1 = 1;
    const OUT_STATUS_2 = 2;

    //订单来源1-手动添加2-批量导入3-第三方
    const ORDER_SOURCE_1 = 1;
    const ORDER_SOURCE_2 = 2;
    const ORDER_SOURCE_3 = 3;

    //订单结算方式1-寄付2-到付
    const ORDER_SETTLEMENT_TYPE_1 = 1;
    const ORDER_SETTLEMENT_TYPE_2 = 2;

    //运单类型1-取2-派
    const TRACKING_ORDER_TYPE_0 = 0;
    const TRACKING_ORDER_TYPE_1 = 1;
    const TRACKING_ORDER_TYPE_2 = 2;

    //订单状态1-待取派2-取派中3-取派完成4-取派失败5-回收站
    const ORDER_STATUS_0 = 0;
    const ORDER_STATUS_1 = 1;
    const ORDER_STATUS_2 = 2;
    const ORDER_STATUS_3 = 3;
    const ORDER_STATUS_4 = 4;
    const ORDER_STATUS_5 = 5;

    //运单状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    const TRACKING_ORDER_STATUS_0 = 0;
    const TRACKING_ORDER_STATUS_1 = 1;
    const TRACKING_ORDER_STATUS_2 = 2;
    const TRACKING_ORDER_STATUS_3 = 3;
    const TRACKING_ORDER_STATUS_4 = 4;
    const TRACKING_ORDER_STATUS_5 = 5;
    const TRACKING_ORDER_STATUS_6 = 6;
    const TRACKING_ORDER_STATUS_7 = 7;


    //包裹列表1-未取派2-取派中3-已签收4-取派失败5-回收站
    const PACKAGE_STATUS_1 = 1;
    const PACKAGE_STATUS_2 = 2;
    const PACKAGE_STATUS_3 = 3;
    const PACKAGE_STATUS_4 = 4;
    const PACKAGE_STATUS_5 = 5;

    //顺带包裹状态1-启用2-禁用
    const ADDITIONAL_PACKAGE_STATUS_1 = 1;
    const ADDITIONAL_PACKAGE_STATUS_2 = 2;

    //订单异常标签1-正常2-异常
    const ORDER_EXCEPTION_LABEL_1 = 1;
    const ORDER_EXCEPTION_LABEL_2 = 2;

    //订单性质1-包裹2-材料
    const ORDER_NATURE_1 = 1;
    const ORDER_NATURE_2 = 2;

    //线路规划 是否新增取件线路 1-是2-否
    const IS_INCREMENT_1 = 1;
    const IS_INCREMENT_2 = 2;

    //订单/站点 1-运单2-站点
    const TRACKING_ORDER_OR_BATCH_1 = 1;
    const TRACKING_ORDER_OR_BATCH_2 = 2;

    //取件线路状态状态：1-待分配2-已分配-3-待出库4-取派中5-取派完成
    const TOUR_STATUS_1 = 1;
    const TOUR_STATUS_2 = 2;
    const TOUR_STATUS_3 = 3;
    const TOUR_STATUS_4 = 4;
    const TOUR_STATUS_5 = 5;

    //取件线路面向用户状态：1-未取派2-取派中-3-取派完成
    const MERCHANT_TOUR_STATUS_1 = 1;
    const MERCHANT_TOUR_STATUS_2 = 2;
    const MERCHANT_TOUR_STATUS_3 = 3;

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

    //batch 批次站点的状态 状态：1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
    const BATCH_WAIT_ASSIGN = 1;
    const BATCH_ASSIGNED = 2;
    const BATCH_WAIT_OUT = 3;
    const BATCH_DELIVERING = 4;
    const BATCH_CHECKOUT = 5;
    const BATCH_CANCEL = 6;

    //面向用户-站点状态:1-未取派2-取派中3-签收成功4-取派失败
    const MERCHANT_BATCH_STATUS_1 = 1;
    const MERCHANT_BATCH_STATUS_2 = 2;
    const MERCHANT_BATCH_STATUS_3 = 3;
    const MERCHANT_BATCH_STATUS_4 = 4;

    //batch exception状态1-未处理2-已处理
    const BATCH_EXCEPTION_1 = 1;
    const BATCH_EXCEPTION_2 = 2;

    //batch 站点异常标签1-正常2-异常
    const BATCH_EXCEPTION_LABEL_1 = 1;
    const BATCH_EXCEPTION_LABEL_2 = 2;

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

    //站点支付类型1-现金支付2-银行支付3-第三方支付4-无需支付
    const BATCH_PAY_TYPE_1 = 1;
    const BATCH_PAY_TYPE_2 = 2;
    const BATCH_PAY_TYPE_3 = 3;
    const BATCH_PAY_TYPE_4 = 4;

    //driver 司机合作类型
    const DRIVER_HIRE = 1;
    const DRIVER_CONTRACTOR = 2;

    //driver 司机状态
    const DRIVER_TO_NORMAL = 1;
    const DRIVER_TO_LOCK = 2;

    //设备状态1-在线2-离线
    const DEVICE_STATUS_1 = 1;
    const DEVICE_STATUS_2 = 2;

    //车辆状态
    const CAR_TO_NORMAL = 1;
    const CAR_TO_LOCK = 2;

    //车辆车型1自动档-2手动挡
    const CAR_TRANSMISSION_1 = 1;
    const CAR_TRANSMISSION_2 = 2;

    //燃料类型1-柴油2-汽油3-混合动力4-电动
    const CAR_FUEL_TYPE_1 = 1;
    const CAR_FUEL_TYPE_2 = 2;
    const CAR_FUEL_TYPE_3 = 3;
    const CAR_FUEL_TYPE_4 = 4;

    //租赁类型1-租赁（到期转私）2-私有3-租赁（到期转待定）
    const CAR_OWNER_SHIP_TYPE_1 = 1;
    const CAR_OWNER_SHIP_TYPE_2 = 2;
    const CAR_OWNER_SHIP_TYPE_3 = 3;

    //修理自理1-是2-否
    const CAR_REPAIR_1 = 1;
    const CAR_REPAIR_2 = 2;

    //管理员端 图片目录
    const ADMIN_IMAGE_DRIVER_DIR = 'driver';
    const ADMIN_IMAGE_TOUR_DIR = 'tour';
    const ADMIN_IMAGE_CANCEL_DIR = 'cancel';
    const ADMIN_IMAGE_MERCHANT_DIR = 'merchant';

    //司机端 图片目录
    const DRIVER_IMAGE_TOUR_DIR = 'tour';

    //管理员端 表格目录
    const ADMIN_EXCEL_TOUR_DIR = 'tour';

    //管理员端 文件目录
    const ADMIN_FILE_DRIVER_DIR = 'driver';
    const ADMIN_FILE_CAR_DIR = 'car';
    const ADMIN_TXT_TOUR_DIR = 'tour';
    const ADMIN_FILE_ORDER_DIR = 'order';
    const ADMIN_FILE_APK_DIR = 'package';
    const ADMIN_FILE_TEMPLATE_DIR = 'template';
    const ADMIN_FILE_LINE_DIR = 'line';

    //司机端 文件目录
    const DRIVER_FILE_TOUR_DIR = 'tour';

    /**
     * order_trail 订单轨迹常量
     * 1-订单创建 2-订单锁定 3-订单开始运输 4-订单完成 5-取派订单中转
     * 6-订单重启 7-订单修改 8-订单关闭 9-订单删除
     */
    const ORDER_TRAIL_CREATED = 1;
    const ORDER_TRAIL_LOCK = 2;
    const ORDER_TRAIL_UNLOCK = 3;
    const ORDER_TRAIL_START = 4;
    const ORDER_TRAIL_FINISH = 5;
    const ORDER_TRAIL_FAIL = 6;
    const ORDER_TRAIL_RESTART = 7;
    const ORDER_TRAIL_UPDATE = 8;
    const ORDER_TRAIL_CLOSED = 9;
    const ORDER_TRAIL_DELETE = 10;

    /**
     * order_trail 订单轨迹常量
     * 1-订单创建 2-加入站点 3-加入取件线路 4-已分配司机 5-加入网点 6-待出库 7-配送中 8-已签收
     * 9-取消取派 10-取消司机分配 11-取消待出库 12-移除站点 13-移除取件线路 14-订单删除
     */
    const TRACKING_ORDER_TRAIL_CREATED = 1;
    const TRACKING_ORDER_TRAIL_JOIN_BATCH = 2;
    const TRACKING_ORDER_TRAIL_JOIN_TOUR = 3;
    const TRACKING_ORDER_TRAIL_ASSIGN_DRIVER = 4;
    const TRACKING_ORDER_TRAIL_REVENUE_OUTLETS = 5;
    const TRACKING_ORDER_TRAIL_LOCK = 6;
    const TRACKING_ORDER_TRAIL_DELIVERING = 7;
    const TRACKING_ORDER_TRAIL_DELIVERED = 8;
    const TRACKING_ORDER_TRAIL_CANCEL_DELIVER = 9;
    const TRACKING_ORDER_TRAIL_CANCEL_ASSIGN_DRIVER = 10;
    const TRACKING_ORDER_TRAIL_UN_LOCK = 11;
    const TRACKING_ORDER_TRAIL_REMOVE_BATCH = 12;
    const TRACKING_ORDER_TRAIL_REMOVE_TOUR = 13;
    const TRACKING_ORDER_TRAIL_DELETE = 14;

    //通知类型
    const NOTIFY_OUT_WAREHOUSE = 'out-warehouse';       //出库通知
    const NOTIFY_ACTUAL_OUT_WAREHOUSE = 'actual-out-warehouse';       //出库通知
    const NOTIFY_NEXT_BATCH = 'next-batch';             //下一个站点通知
    const NOTIFY_ARRIVED_BATCH = 'arrive-batch';        //到达站点通知
    const NOTIFY_ASSIGN_BATCH = 'assign-batch';         //签收站点通知
    const NOTIFY_CANCEL_BATCH = 'assign-batch';         //取消派送站点通知(也叫签收)
    const NOTIFY_BACK_WAREHOUSE = 'back-warehouse';     //返回仓库通知
    const NOTIFY_ORDER_EXECUTION_DATE_UPDATE = 'update-execution-date'; //修改取派日期通知
    const NOTIFY_ORDER_CANCEL = 'cancel-order';     //订单取消通知
    const NOTIFY_ORDER_DELETE = 'delete-order';     //订单删除通知
    const NOTIFY_SYNC_ORDER_STATUS = 'sync-order-status';   //同步订单状态
    const NOTIFY_RECHARGE_PROCESS = 'recharge-process';   //同步订单状态
    const NOTIFY_RECHARGE_VALIDUSER = 'recharge-validuser';   //同步订单状态
    const NOTIFY_STORE_ORDER = 'store-order';               //订单新增
    const NOTIFY_PACKAGE_INFO = 'package-info';   //发送包裹信息
    const NOTIFY_PACKAGE_PICK_OUT = 'package-pick-out';   //包裹入库分拣

    //push类型
    const PUSH_TOUR_ADD_ORDER = 'add_tracking_order';   //线路加单
    const PUSH_CANCEL_BATCH = 'cancel-batch';           //站点取消取派


    //商户类型
    const MERCHANT_TYPE_1 = 1;
    const MERCHANT_TYPE_2 = 2;

    //商户支付方式
    const MERCHANT_SETTLEMENT_TYPE_1 = 1;
    const MERCHANT_SETTLEMENT_TYPE_2 = 2;
    const MERCHANT_SETTLEMENT_TYPE_3 = 3;

    //商户状态
    const MERCHANT_STATUS_1 = 1;
    const MERCHANT_STATUS_2 = 2;

    //司机事件1-司机出库2-司机到达站点3-司机从站点出发4-司机回仓
    const DRIVER_EVENT_OUT_WAREHOUSE = 1;
    const DRIVER_EVENT_BATCH_ARRIVED = 2;
    const DRIVER_EVENT_BATCH_DEPART = 3;
    const DRIVER_EVENT_BACK_WAREHOUSE = 4;

    //展示方式1-全部展示2-按线路规则展示
    const ALL_SHOW = 1;
    const LINE_RULE_SHOW = 2;

    //worker 组名
    const WORKER_GROUP_ADMIN = 'admin';
    const WORKER_GROUP_MERCHANT = 'merchant';
    const WORKER_GROUP_DRIVER = 'driver';

    //充值状态
    const RECHARGE_STATUS_1 = 1;
    const RECHARGE_STATUS_2 = 2;
    const RECHARGE_STATUS_3 = 3;

    //审核状态
    const RECHARGE_VERIFY_STATUS_1 = 1;
    const RECHARGE_VERIFY_STATUS_2 = 2;

    //商户充值API状态
    const MERCHANT_RECHARGE_STATUS_1 = 1;
    const MERCHANT_RECHARGE_STATUS_2 = 2;

    //充值审核状态
    const VERIFY_STATUS_1 = 1;
    const VERIFY_STATUS_2 = 2;

    //充值司机端验证状态
    const RECHARGE_DRIVER_VERIFY_STATUS_1 = 1;
    const RECHARGE_DRIVER_VERIFY_STATUS_2 = 2;

    //是否跳过
    const IS_SKIPPED = 1;
    const IS_NOT_SKIPPED = 2;

    //能否跳过
    const CAN_NOT_SKIP_BATCH = 1;
    const CAN_SKIP_BATCH = 2;

    //语言
    const CN = 'cn';
    const EN = 'en';
    const NL = 'nl';

    //商户端顺带功能状态
    const MERCHANT_ADDITIONAL_STATUS_1 = 1;
    const MERCHANT_ADDITIONAL_STATUS_2 = 2;

    //delay 延迟类型  1-用餐休息 2-交通堵塞 3-更换行车路线 4-其他
    const TOUR_DELAY_TYPE_1 = 1;
    const TOUR_DELAY_TYPE_2 = 2;
    const TOUR_DELAY_TYPE_3 = 3;
    const TOUR_DELAY_TYPE_4 = 4;

    //包裹类型1取2派3-取派
    const PACKAGE_TYPE_1 = 1;
    const PACKAGE_TYPE_2 = 2;
    const PACKAGE_TYPE_3 = 3;

    //包裹仓库类型1-入库2-出库
    const WAREHOUSE_PACKAGE_TYPE_1 = 1;
    const WAREHOUSE_PACKAGE_TYPE_2 = 2;

    //商户端订单物流类型
    const TRACK_STATUS_1 = 1;
    const TRACK_STATUS_2 = 2;
    const TRACK_STATUS_3 = 3;

    //入库异常状态1-未审核2-已审核3-审核拒绝
    const STOCK_EXCEPTION_STATUS_1 = 1;
    const STOCK_EXCEPTION_STATUS_2 = 2;
    const STOCK_EXCEPTION_STATUS_3 = 3;

    //入库异常审核1-关闭-2-开启
    const STOCK_EXCEPTION_VERIFY_1 = 1;
    const STOCK_EXCEPTION_VERIFY_2 = 2;

    //推送模式1-简单模式2-详细模式
    const SIMPLE_PUSH_MODE = 1;
    const DETAIL_PUSH_MODE = 2;

    //线路重试最大次数
    const ROUTE_RETRY_MAX_TIMES = 3;
    //线路重推间隔时间(分钟)
    const ROUTE_RETRY_INTERVAL_TIME = 1;

    //固定值法
    const ONLY_START_PRICE = 3;

    //操作类型
    const OPERATION_STORE = 1;
    const OPERATION_UPDATE = 2;
    const OPERATION_DESTROY = 3;
    const OPERATION_STATUS_ON = 4;
    const OPERATION_STATUS_OFF = 5;

    //运价类型
    const TRANSPORT_PRICE_TYPE_1 = 1;
    const TRANSPORT_PRICE_TYPE_2 = 2;

    //无限
    const INFINITY = 999999999;
}
