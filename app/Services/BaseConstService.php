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
    const RECHARGE = 'RC';
    const CAR_ACCIDENT = 'CA';
    const CAR_MAINTAIN = 'CM';
    const SPARE_PARTS = 'SP';

    const CANCEL_TIMES = 3;

    //邮编国家
    const POSTCODE_COUNTRY_BE = 'BE';
    const POSTCODE_COUNTRY_NL = 'NL';
    const POSTCODE_COUNTRY_DE = 'DE';

    //是否
    const YES = 1;
    const NO = 2;

    //支付方1-货主2-客户
    const FEE_PAYER_1 = 1;
    const FEE_PAYER_2 = 2;

    //权限类型1-菜单2-按钮
    const PERMISSION_TYPE_1 = 1;
    const PERMISSION_TYPE_2 = 2;

    //快捷方式,值与数据库权限表一致
    const SHORT_CUT_ORDER_STORE = 'order.store';
    const SHORT_CUT_ORDER_INDEX = 'order.index';
    const SHORT_CUT_LINE_POST_CODE_INDEX = 'line.post-code-index';
    const SHORT_CUT_TRACKING_INDEX = 'tracking-order.index';
    const SHORT_CUT_BATCH_INDEX = 'batch.index';
    const SHORT_CUT_TOUR_INDEX = 'tour.index';
    const SHORT_CUT_TOUR_DISPATCH = 'tour.intelligent-scheduling';

    //流程图
    const FLOW_ORDER_STORE = 'order.store';
    const FLOW_MERCHANT_API_INDEX = 'merchant-api.index';
    const FLOW_ORDER_INDEX = 'order.index';
    const FLOW_PACKAGE_INDEX = 'package.index';
    const FLOW_MATERIAL_INDEX = 'material.index';
    const FLOW_TRACKING_ORDER_INDEX = 'tracking-order.index';
    const FLOW_BATCH_INDEX = 'batch.index';
    const FLOW_TOUR_INDEX = 'tour.index';
    const FLOW_TOUR_INTELLIGENT_SCHEDULING = 'tour.intelligent-scheduling';
    const FLOW_DRIVER_INDEX = 'driver.index';
    const FLOW_CAR_INDEX = 'car.index';
    const FLOW_CAR_MANAGEMENT_INDEX = 'car-management.index';


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
    //事故处理编号类型
    const CAR_ACCIDENT_NO_TYPE = 'accident';
    //车辆维护流水号
    const CAR_MAINTAIN_NO_TYPE = 'maintain';

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

    //临时处理 正式服-货主ID(有用到，请勿删除)
    const ERP_MERCHANT_ID_1 = 7;
    const SHOP_MERCHANT_ID_2 = 8;

    //临时处理 开发服-货主ID(有用到，请勿删除)
    const ERP_MERCHANT_ID_2 = 65;
    const SHOP_MERCHANT_ID_1 = 3;

    //订单类型1-取2-派3-取派
    const ORDER_TYPE_0 = 0;
    const ORDER_TYPE_1 = 1;
    const ORDER_TYPE_2 = 2;
    const ORDER_TYPE_3 = 3;
    const ORDER_TYPE_4 = 4;

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
    const ORDER_SETTLEMENT_TYPE_3 = 3;
    const ORDER_SETTLEMENT_TYPE_4 = 4;
    const ORDER_SETTLEMENT_TYPE_5 = 5;

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
    const CAR_OWNER_SHIP_TYPE_4 = 4;

    //车辆长度
    const CAR_LENGTH_TYPE_1 = 1;
    const CAR_LENGTH_TYPE_2 = 2;
    const CAR_LENGTH_TYPE_3 = 3;
    const CAR_LENGTH_TYPE_4 = 4;
    const CAR_LENGTH_TYPE_5 = 5;
    const CAR_LENGTH_TYPE_6 = 6;
    const CAR_LENGTH_TYPE_7 = 7;
    const CAR_LENGTH_TYPE_8 = 8;
    const CAR_LENGTH_TYPE_9 = 9;
    const CAR_LENGTH_TYPE_10 = 10;
    const CAR_LENGTH_TYPE_11 = 11;
    const CAR_LENGTH_TYPE_12 = 12;
    const CAR_LENGTH_TYPE_13 = 13;
    const CAR_LENGTH_TYPE_14 = 14;
    const CAR_LENGTH_TYPE_15 = 15;
    const CAR_LENGTH_TYPE_16 = 16;
    const CAR_LENGTH_TYPE_17 = 17;
    const CAR_LENGTH_TYPE_18 = 18;

    //车辆车型
    const CAR_MODEL_TYPE_1 = 1;
    const CAR_MODEL_TYPE_2 = 2;
    const CAR_MODEL_TYPE_3 = 3;
    const CAR_MODEL_TYPE_4 = 4;
    const CAR_MODEL_TYPE_5 = 5;
    const CAR_MODEL_TYPE_6 = 6;
    const CAR_MODEL_TYPE_7 = 7;
    const CAR_MODEL_TYPE_8 = 8;
    const CAR_MODEL_TYPE_9 = 9;
    const CAR_MODEL_TYPE_10 = 10;
    const CAR_MODEL_TYPE_11 = 11;
    const CAR_MODEL_TYPE_12 = 12;
    const CAR_MODEL_TYPE_13 = 13;

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
    const ADMIN_FILE_ADDRESS_TEMPLATE_DIR = 'addressTemplate';

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
    const TRACKING_ORDER_TRAIL_CUSTOMER = 15;

    //通知类型
    const NOTIFY_OUT_WAREHOUSE = 'out-warehouse';       //出库通知
    const NOTIFY_ACTUAL_OUT_WAREHOUSE = 'actual-out-warehouse';       //出库通知
    const NOTIFY_NEXT_BATCH = 'next-batch';             //下一个站点通知
    const NOTIFY_ARRIVED_BATCH = 'arrive-batch';        //到达站点通知
    const NOTIFY_ASSIGN_BATCH = 'assign-batch';         //签收站点通知
    const NOTIFY_CANCEL_BATCH = 'assign-batch';         //取消派送站点通知(也叫签收)
    const NOTIFY_BACK_WAREHOUSE = 'back-warehouse';     //返回网点通知
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


    //货主类型
    const MERCHANT_TYPE_1 = 1;
    const MERCHANT_TYPE_2 = 2;

    //货主支付方式
    const MERCHANT_SETTLEMENT_TYPE_1 = 1;
    const MERCHANT_SETTLEMENT_TYPE_2 = 2;
    const MERCHANT_SETTLEMENT_TYPE_3 = 3;

    //货主状态
    const MERCHANT_STATUS_1 = 1;
    const MERCHANT_STATUS_2 = 2;

    //司机事件1-司机出库2-司机到达站点3-司机从站点出发4-司机回仓
    const DRIVER_EVENT_OUT_WAREHOUSE = 1;
    const DRIVER_EVENT_BATCH_ARRIVED = 2;
    const DRIVER_EVENT_BATCH_DEPART = 3;
    const DRIVER_EVENT_BACK_WAREHOUSE = 4;

    //司机类型
    const DRIVER_TYPE_1 = 1;
    const DRIVER_TYPE_2 = 2;
    const DRIVER_TYPE_3 = 3;
    const DRIVER_TYPE_4 = 4;

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

    //充值入账状态
    const RECHARGE_STATISTICS_STATUS_1 = 1;
    const RECHARGE_STATISTICS_STATUS_2 = 2;

    //审核状态
    const RECHARGE_VERIFY_STATUS_1 = 1;
    const RECHARGE_VERIFY_STATUS_2 = 2;

    //货主充值API状态
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

    //货主端顺带功能状态
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
    const PACKAGE_TYPE_4 = 4;

    //包裹网点类型1-入库2-出库
    const WAREHOUSE_PACKAGE_TYPE_1 = 1;
    const WAREHOUSE_PACKAGE_TYPE_2 = 2;

    //货主端订单物流类型
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

    //超期状态
    const EXPIRATION_STATUS_1 = 1;
    const EXPIRATION_STATUS_2 = 2;
    const EXPIRATION_STATUS_3 = 3;

    //维保类型:1-保养2-维修
    const MAINTAIN_TYPE_1 = 1;
    const MAINTAIN_TYPE_2 = 2;

    //是否收票:1-是2-否
    const IS_TICKET_1 = 1;
    const IS_TICKET_2 = 2;

    //处理方式：1-保险2-公司赔付
    const CAR_ACCIDENT_DEAL_TYPE_1 = 1;
    const CAR_ACCIDENT_DEAL_TYPE_2 = 2;

    //主被动,责任方：1-主动2-被动
    const CAR_ACCIDENT_DUTY_TYPE_1 = 1;
    const CAR_ACCIDENT_DUTY_TYPE_2 = 2;

    //保险是否赔付：1-是2-否
    const CAR_ACCIDENT_INS_PAY_TYPE_1 = 1;
    const CAR_ACCIDENT_INS_PAY_TYPE_2 = 2;

    //订单目的地模式1-省市区2-省市3-市区4-邮编
    const ORDER_TEMPLATE_DESTINATION_MODE_1 = 1;
    const ORDER_TEMPLATE_DESTINATION_MODE_2 = 2;
    const ORDER_TEMPLATE_DESTINATION_MODE_3 = 3;
    const ORDER_TEMPLATE_DESTINATION_MODE_4 = 4;

    //订单模板1-模板一2-模板二
    const ORDER_TEMPLATE_TYPE_1 = 1;
    const ORDER_TEMPLATE_TYPE_2 = 2;

    //备品单位
    const SPARE_PARTS_UNIT_TYPE_1 = 1;
    const SPARE_PARTS_UNIT_TYPE_2 = 2;
    const SPARE_PARTS_UNIT_TYPE_3 = 3;
    const SPARE_PARTS_UNIT_TYPE_4 = 4;
    const SPARE_PARTS_UNIT_TYPE_5 = 5;
    const SPARE_PARTS_UNIT_TYPE_6 = 6;
    const SPARE_PARTS_UNIT_TYPE_7 = 7;
    const SPARE_PARTS_UNIT_TYPE_8 = 8;
    const SPARE_PARTS_UNIT_TYPE_9 = 9;
    const SPARE_PARTS_UNIT_TYPE_10 = 10;
    const SPARE_PARTS_UNIT_TYPE_11 = 11;

    //领取状态:1-正常2-已作废
    const SPARE_PARTS_RECORD_TYPE_1 = 1;
    const SPARE_PARTS_RECORD_TYPE_2 = 2;

    //运输方式
    const ORDER_TRANSPORT_MODE_1 = 1;
    const ORDER_TRANSPORT_MODE_2 = 2;

    //订单始发地
    const ORDER_ORIGIN_TYPE_1 = 1;
    const ORDER_ORIGIN_TYPE_2 = 2;

    //材料类型
    const MATERIAL_TYPE_1 = 1;
    const MATERIAL_TYPE_2 = 2;
    const MATERIAL_TYPE_3 = 3;
    const MATERIAL_TYPE_4 = 4;
    const MATERIAL_TYPE_5 = 5;
    const MATERIAL_TYPE_6 = 6;
    const MATERIAL_TYPE_7 = 7;
    const MATERIAL_TYPE_8 = 8;
    const MATERIAL_TYPE_9 = 9;
    const MATERIAL_TYPE_10 = 10;

    //材料包装
    const MATERIAL_PACK_TYPE_1 = 1;
    const MATERIAL_PACK_TYPE_2 = 2;
    const MATERIAL_PACK_TYPE_3 = 3;
    const MATERIAL_PACK_TYPE_4 = 4;
    const MATERIAL_PACK_TYPE_5 = 5;
    const MATERIAL_PACK_TYPE_6 = 6;
    const MATERIAL_PACK_TYPE_7 = 7;
    const MATERIAL_PACK_TYPE_8 = 8;
    const MATERIAL_PACK_TYPE_9 = 9;
    const MATERIAL_PACK_TYPE_10 = 10;
    const MATERIAL_PACK_TYPE_11 = 11;
    const MATERIAL_PACK_TYPE_12 = 12;

    //包裹特性
    const PACKAGE_FEATURE_1 = 1;
    const PACKAGE_FEATURE_2 = 2;
    const PACKAGE_FEATURE_3 = 3;
    const PACKAGE_FEATURE_4 = 4;

    //控货方式
    const ORDER_CONTROL_MODE_1 = 1;
    const ORDER_CONTROL_MODE_2 = 2;

    //费用类型
    const ORDER_AMOUNT_TYPE_1 = 1;
    const ORDER_AMOUNT_TYPE_2 = 2;
    const ORDER_AMOUNT_TYPE_3 = 3;
    const ORDER_AMOUNT_TYPE_4 = 4;
    const ORDER_AMOUNT_TYPE_5 = 5;
    const ORDER_AMOUNT_TYPE_6 = 6;
    const ORDER_AMOUNT_TYPE_7 = 7;
    const ORDER_AMOUNT_TYPE_8 = 8;
    const ORDER_AMOUNT_TYPE_9 = 9;
    const ORDER_AMOUNT_TYPE_10 = 10;
    const ORDER_AMOUNT_TYPE_11 = 11;

    //支付状态
    const ORDER_AMOUNT_STATUS_1 = 1;
    const ORDER_AMOUNT_STATUS_2 = 2;
    const ORDER_AMOUNT_STATUS_3 = 3;
    const ORDER_AMOUNT_STATUS_4 = 4;
    const ORDER_AMOUNT_STATUS_5 = 5;

    //订单回单要求
    const ORDER_RECEIPT_TYPE_1 = 1;

    //地址类型
    const ADDRESS_TYPE_1 = 1;
    const ADDRESS_TYPE_2 = 2;

    //地图前端类型
    const MAP_CONFIG_FRONT_TYPE_1 = 1;
    const MAP_CONFIG_FRONT_TYPE_2 = 2;
    const MAP_CONFIG_FRONT_TYPE_3 = 3;

    //地图后端类型
    const MAP_CONFIG_BACK_TYPE_1 = 1;
    const MAP_CONFIG_BACK_TYPE_2 = 2;
    const MAP_CONFIG_BACK_TYPE_3 = 3;

    //地图手持端类型
    const MAP_CONFIG_MOBILE_TYPE_1 = 1;
    const MAP_CONFIG_MOBILE_TYPE_2 = 2;
    const MAP_CONFIG_MOBILE_TYPE_3 = 3;

    const CREATED_TIME = '创建时间';
    const BEGIN_TIME = '发车时间';
    const SIGN_TIME = '签收时间';

    const ORDER_TEMPLATE_IS_DEFAULT_1 = 1;
    const ORDER_TEMPLATE_IS_DEFAULT_2 = 2;

    //重量单位
    const WEIGHT_UNIT_TYPE_1 = 1;
    const WEIGHT_UNIT_TYPE_2 = 2;

    //货币单位
    const CURRENCY_UNIT_TYPE_1 = 1;
    const CURRENCY_UNIT_TYPE_2 = 2;
    const CURRENCY_UNIT_TYPE_3 = 3;

    //体积单位
    const VOLUME_UNIT_TYPE_1 = 1;
    const VOLUME_UNIT_TYPE_2 = 2;

    //调度规则
    const SCHEDULING_TYPE_1 = 1;
    const SCHEDULING_TYPE_2 = 2;

    const WAREHOUSE_TYPE_1 = 1;
    const WAREHOUSE_TYPE_2 = 2;

    const WAREHOUSE_ACCEPTANCE_TYPE_1 = 1;
    const WAREHOUSE_ACCEPTANCE_TYPE_2 = 2;
    const WAREHOUSE_ACCEPTANCE_TYPE_3 = 3;

    const WAREHOUSE_IS_CENTER_1 = 1;
    const WAREHOUSE_IS_CENTER_2 = 2;

    //邮件模板类型
    const EMAIL_TEMPLATE_TYPE_1 = 1;
    const EMAIL_TEMPLATE_TYPE_2 = 2;
    const EMAIL_TEMPLATE_TYPE_3 = 3;

    //邮件模板状态
    const EMAIL_TEMPLATE_STATUS_1 = 1;
    const EMAIL_TEMPLATE_STATUS_2 = 2;
}
