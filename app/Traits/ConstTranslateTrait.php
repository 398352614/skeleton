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
use Illuminate\Support\Facades\App;

/**
 * Trait ConstTranslateTrait
 * @package App\Traits
 * @method static statusList($args = null)
 * @method static noTypeList($args = null)
 * @method static shortCutList($args = null)
 * @method static shortCutRouteList($args = null)
 * @method static shortCutIconList($args = null)
 * @method static lineRuleList($args = null)
 * @method static printTemplateList($args = null)
 * @method static weekList($args = null)
 * @method static orderSourceList($arg = null)
 * @method static trackingOrderTypeList($args = null)
 * @method static orderTypeList($args = null)
 * @method static orderSettlementTypeList($args = null)
 * @method static merchantOrderTypeList($args = null)
 * @method static outStatusList($args = null)
 * @method static trackingOrderStatusList($args = null)
 * @method static orderStatusList($args = null)
 * @method static packageStatusList($args = null)
 * @method static packageTypeList($args = null)
 * @method static warehousePackageTypeList($args = null)
 * @method static orderExceptionLabelList($args = null)
 * @method static orderNatureList($args = null)
 * @method static batchPayTypeList($args = null)
 * @method static batchExceptionLabelList($args = null)
 * @method static batchStatusList($args = null)
 * @method static batchExceptionStatusList($args = null)
 * @method static batchExceptionStageList($args = null)
 * @method static batchExceptionFirstStageTypeList($args = null)
 * @method static batchExceptionSecondStageTypeList($args = null)
 * @method static tourStatusList($args = null)
 * @method static carTransmissionList($args = null)
 * @method static carFuelTypeList($args = null)
 * @method static carOwnerShipTypeList($args = null)
 * @method static carRepairList($args = null)
 * @method static driverTypeList($args = null)
 * @method static driverStatusList($args = null)
 * @method static deviceStatusList($args = null)
 * @method static adminImageDirList($args = null)
 * @method static driverImageDirList($args = null)
 * @method static adminFileDirList($args = null)
 * @method static adminExcelDirList($args = null)
 * @method static driverFileDirList($args = null)
 * @method static adminTxtDirList($args = null)
 * @method static merchantTypeList($args = null)
 * @method static merchantSettlementTypeList($args = null)
 * @method static merchantStatusList($args = null)
 * @method static driverEventList($args = null)
 * @method static merchantTourStatusList($args = null)
 * @method static merchantBatchStatusList($args = null)
 * @method static showTypeList($args = null)
 * @method static rechargeStatusList($args = null)
 * @method static verifyStatusList($args = null)
 * @method static isSkippedList($args = null)
 * @method static canSkipBatchList($args = null)
 * @method static languageList($args = null)
 * @method static merchantAdditionalStatusList($args = null)
 * @method static tourDelayTypeList($args = null)
 * @method static trackStatusList($args = null)
 * @method static stockExceptionStatusList($args = null)
 * @method static operationList($args = null)
 * @method static transportPriceTypeList($args = null)
 * @method static expirationStatusList($args = null)
 * @method static carMaintainType($args = null)
 * @method static carMaintainTicket($args = null)
 * @method static carAccidentDealType($args = null)
 * @method static carAccidentDuty($args = null)
 * @method static carAccidentInsPay($args = null)
 * @method static sparePartsUnit($args = null)
 * @method static sparePartsRecordStatus($args = null)
 * @method static orderTemplateDestinationModeList($args = null)
 * @method static orderTemplateTypeList($args = null)
 * @method static carLengthTypeList($args = null)
 * @method static carModelTypeList($args = null)
 * @method static orderAmountTypeList($args = null)
 * @method static orderAmountStatusList($args = null)
 * @method static materialTypeList($args = null)
 * @method static materialPackTypeList($args = null)
 * @method static orderReceiptTypeList($args = null)
 * @method static rechargeStatisticsStatusList($args = null)
 * @method static orderTransportModeList($args = null)
 * @method static orderOriginTypeList($args = null)
 * @method static trackingOrderTrailTypeList($args = null)
 * @method static addressTypeList($args = null)
 * @method static mapConfigMobileTypeList($args = null)
 * @method static mapConfigBackTypeList($args = null)
 * @method static mapConfigFrontTypeList($args = null)
 * @method static orderTemplateIsDefaultList($args = null)
 * @method static feePayerList($args = null)
 * @method static weightUnitTypeList($args = null)
 * @method static weightUnitTypeSymbol($args = null)
 * @method static currencyUnitTypeList($args = null)
 * @method static currencyUnitTypeSymbol($args = null)
 * @method static volumeUnitTypeList($args = null)
 * @method static volumeUnitTypeSymbol($args = null)
 * @method static warehouseTypeList($args = null)
 * @method static warehouseAcceptanceTypeList($args = null)
 * @method static warehouseIsCenterTypeList($args = null)
 * @method static emailTemplateTypeList($args = null)
 * @method static emailTemplateStatusList($args = null)
 * @method static lineTestStatusList($args = null)
 * @method static orderConfigNatureList($args = null)
 * @method static employeeForbidLoginList($args = null)
 * @method static shiftStatusList($args = null)
 * @method static bagStatusList($args = null)
 * @method static trackingPackageStatusList($args = null)
 * @method static shiftLoadTypeList($args = null)
 * @method static packageStageList($args = null)
 * @method static packageTrailTypeList($args = null)
 * @method static batchCancelTypeList($args = null)
 * @method static orderControlModeList($args = null)
 * @method static packageFeatureList($args = null)
 *
 */
trait ConstTranslateTrait
{
    //状态1-是2-否
    public static $statusList = [
        BaseConstService::YES => '是',
        BaseConstService::NO => '否'
    ];

    //包裹转运状态1-待装袋2-待装车3-待发车4-运输中5-已到车6-已卸货7-已拆袋
    public static $trackingPackageStatusList = [
        BaseConstService::TRACKING_PACKAGE_STATUS_1 => '已入库',
        BaseConstService::TRACKING_PACKAGE_STATUS_2 => '已装袋',
        BaseConstService::TRACKING_PACKAGE_STATUS_3 => '已装车',
        BaseConstService::TRACKING_PACKAGE_STATUS_4 => '已发车',
        BaseConstService::TRACKING_PACKAGE_STATUS_5 => '已到车',
        BaseConstService::TRACKING_PACKAGE_STATUS_6 => '未拆袋',
        BaseConstService::TRACKING_PACKAGE_STATUS_7 => '已拆袋',
    ];

    //袋号状态:1-待发车2-运输中3-待卸车4-待拆袋5-已拆袋
    public static $bagStatusList = [
        BaseConstService::BAG_STATUS_1 => '未发车',
        BaseConstService::BAG_STATUS_2 => '已发车',
        BaseConstService::BAG_STATUS_3 => '已到车',
        BaseConstService::BAG_STATUS_4 => '未拆袋',
        BaseConstService::BAG_STATUS_5 => '已拆袋',
    ];

    //车次状态1-待发车2-运输中3-已到车4-已卸货
    public static $shiftStatusList = [
        BaseConstService::SHIFT_STATUS_1 => '未发车',
        BaseConstService::SHIFT_STATUS_2 => '已发车',
        BaseConstService::SHIFT_STATUS_3 => '未卸车',
        BaseConstService::SHIFT_STATUS_4 => '已卸车',
    ];

    public static $trackingPackageList =
        [
            BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_1 => 1,
            BaseConstService::TRACKING_PACKAGE_DISTANCE_TYPE_2 => 2,
        ];

    //包裹阶段
    public static $packageStageList =
        [
            BaseConstService::PACKAGE_STAGE_1 => '提货',
            BaseConstService::PACKAGE_STAGE_2 => '中转',
            BaseConstService::PACKAGE_STAGE_3 => '配送'
        ];

    //编号类型
    public static $noTypeList = [
        BaseConstService::ORDER_NO_TYPE => '订单编号规则',
        BaseConstService::TRACKING_ORDER_NO_TYPE => '运单单号规则',
        BaseConstService::BATCH_NO_TYPE => '站点编号规则',
        BaseConstService::TOUR_NO_TYPE => '取件线路编号规则',
        BaseConstService::RECHARGE_NO_TYPE => '充值单号规则',
        BaseConstService::BATCH_EXCEPTION_NO_TYPE => '站点异常编号规则',
        BaseConstService::STOCK_EXCEPTION_NO_TYPE => '入库异常编号规则',
        BaseConstService::CAR_ACCIDENT_NO_TYPE => '事故处理单号规则',
        BaseConstService::CAR_MAINTAIN_NO_TYPE => '车辆维护流水号规则',
        BaseConstService::TRACKING_PACKAGE_NO_TYPE => '转运单号规则',
        BaseConstService::BAG_NO_TYPE => '袋号规则',
        BaseConstService::SHIFT_NO_TYPE => '车辆维护流水号规则',
    ];

    //快捷方式列表
    public static $shortCutList = [
        BaseConstService::SHORT_CUT_ORDER_STORE => '下单',
        BaseConstService::SHORT_CUT_ORDER_INDEX => '订单',
        BaseConstService::SHORT_CUT_LINE_POST_CODE_INDEX => '线路规划(邮编)',
        BaseConstService::SHORT_CUT_TRACKING_INDEX => '运单',
        BaseConstService::SHORT_CUT_BATCH_INDEX => '站点',
        BaseConstService::SHORT_CUT_TOUR_INDEX => '线路任务',
        BaseConstService::SHORT_CUT_TOUR_DISPATCH => '智能调度',
    ];

    //车次装车类型
    public static $shiftLoadTypeList = [
        BaseConstService::SHIFT_LOAD_TYPE_1 => '包裹单号',
        BaseConstService::SHIFT_LOAD_TYPE_2 => '袋号',
    ];

    //快捷方式列表
    public static $flowList = [
        BaseConstService::FLOW_ORDER_STORE => '手动录单',
        BaseConstService::FLOW_MERCHANT_API_INDEX => 'API对接',
        BaseConstService::FLOW_ORDER_INDEX => '订单管理',
        BaseConstService::FLOW_PACKAGE_INDEX => '包裹管理',
        BaseConstService::FLOW_MATERIAL_INDEX => '材料管理',
        BaseConstService::FLOW_TRACKING_ORDER_INDEX => '运单管理',
        BaseConstService::FLOW_BATCH_INDEX => '站点管理',
        BaseConstService::FLOW_TOUR_INDEX => '任务管理',
        BaseConstService::FLOW_TOUR_INTELLIGENT_SCHEDULING => '智能调度',
        BaseConstService::FLOW_DRIVER_INDEX => '司机管理',
        BaseConstService::FLOW_CAR_INDEX => '车辆管理',
        BaseConstService::FLOW_CAR_MANAGEMENT_INDEX => '智能管车'
    ];

    //快捷方式路由列表(前端需要)
    public static $shortCutRouteList = [
        BaseConstService::SHORT_CUT_ORDER_STORE => 'orderAdd',
        BaseConstService::SHORT_CUT_ORDER_INDEX => 'OrderList',
        BaseConstService::SHORT_CUT_LINE_POST_CODE_INDEX => 'LinePlanningPostCode',
        BaseConstService::SHORT_CUT_TRACKING_INDEX => 'WaybillManagement',
        BaseConstService::SHORT_CUT_BATCH_INDEX => 'stationList',
        BaseConstService::SHORT_CUT_TOUR_INDEX => 'lineTask',
        BaseConstService::SHORT_CUT_TOUR_DISPATCH => 'intelligentDispatch',
    ];

    //流程图路由列表(前端需要)
    public static $flowRouteList = [
        BaseConstService::SHORT_CUT_ORDER_STORE => 'orderAdd',
        BaseConstService::SHORT_CUT_ORDER_INDEX => 'OrderList',
        BaseConstService::SHORT_CUT_LINE_POST_CODE_INDEX => 'LinePlanningPostCode',
        BaseConstService::SHORT_CUT_TRACKING_INDEX => 'WaybillManagement',
        BaseConstService::SHORT_CUT_BATCH_INDEX => 'stationList',
        BaseConstService::SHORT_CUT_TOUR_INDEX => 'lineTask',
        BaseConstService::SHORT_CUT_TOUR_DISPATCH => 'intelligentDispatch',
    ];

    //快捷方式ICON列表(前端需要)
    public static $shortCutIconList = [
        BaseConstService::SHORT_CUT_ORDER_STORE => '&#xe65f;',
        BaseConstService::SHORT_CUT_ORDER_INDEX => '&#xe66a;',
        BaseConstService::SHORT_CUT_LINE_POST_CODE_INDEX => '&#xe65f;',
        BaseConstService::SHORT_CUT_TRACKING_INDEX => '&#xe661;',
        BaseConstService::SHORT_CUT_BATCH_INDEX => '&#xe67a;',
        BaseConstService::SHORT_CUT_TOUR_INDEX => '&#xea06;',
        BaseConstService::SHORT_CUT_TOUR_DISPATCH => '&#xe670;',
    ];

    //线路分配规则
    public static $lineRuleList = [
        BaseConstService::LINE_RULE_POST_CODE => '按邮编自动分配',
        BaseConstService::LINE_RULE_AREA => '按区域自动分配',
    ];

    //打印模板列表
    public static $printTemplateList = [
        BaseConstService::PRINT_TEMPLATE_STANDARD => '标准模板',
        BaseConstService::PRINT_TEMPLATE_GENERAL => '通用模板',
    ];

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

    //订单类型1-取件2-派件3-取派
    public static $orderTypeList = [
//        BaseConstService::ORDER_TYPE_0 => '全部',
        BaseConstService::ORDER_TYPE_1 => '提货->网点',
        BaseConstService::ORDER_TYPE_2 => '网点->配送',
        BaseConstService::ORDER_TYPE_3 => '提货->网点->配送',
    ];

    //订单类型1-取件2-派件3-取派
    public static $packageTypeList = [
        BaseConstService::PACKAGE_TYPE_1 => '提货->网点',
        BaseConstService::PACKAGE_TYPE_2 => '网点->配送',
        BaseConstService::PACKAGE_TYPE_3 => '提货->网点->配送',
        BaseConstService::PACKAGE_TYPE_4 => '提货->配送',
    ];

    //订单来源1-手动添加2-批量导入3-第三方
    public static $orderSourceList = [
        BaseConstService::ORDER_SOURCE_1 => '手动添加',
        BaseConstService::ORDER_SOURCE_2 => '批量导入',
        BaseConstService::ORDER_SOURCE_3 => '第三方'
    ];

    //订单结算方式1-寄付2-到付
    public static $orderSettlementTypeList = [
        BaseConstService::ORDER_SETTLEMENT_TYPE_1 => '现付',
        BaseConstService::ORDER_SETTLEMENT_TYPE_2 => '回单付',
        BaseConstService::ORDER_SETTLEMENT_TYPE_3 => '周结',
        BaseConstService::ORDER_SETTLEMENT_TYPE_4 => '月结',
        BaseConstService::ORDER_SETTLEMENT_TYPE_5 => '免费',

    ];

    //订单状态
    public static $orderStatusList = [
        BaseConstService::ORDER_STATUS_0 => '全部',
        BaseConstService::ORDER_STATUS_1 => '待受理',
        BaseConstService::ORDER_STATUS_2 => '取派中',
        BaseConstService::ORDER_STATUS_3 => '已完成',
        BaseConstService::ORDER_STATUS_4 => '取派失败',
        BaseConstService::ORDER_STATUS_5 => '回收站',
    ];

    //运单类型1-取2-派
    public static $trackingOrderTypeList = [
        BaseConstService::TRACKING_ORDER_TYPE_0 => '全部',
        BaseConstService::TRACKING_ORDER_TYPE_1 => '提货',
        BaseConstService::TRACKING_ORDER_TYPE_2 => '配送',
    ];

    //订单状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    public static $trackingOrderStatusList = [
        BaseConstService::TRACKING_ORDER_STATUS_0 => '全部',
        BaseConstService::TRACKING_ORDER_STATUS_1 => '待受理',
        BaseConstService::TRACKING_ORDER_STATUS_2 => '已接单',
        BaseConstService::TRACKING_ORDER_STATUS_3 => '已装货',
        BaseConstService::TRACKING_ORDER_STATUS_4 => '在途',
        BaseConstService::TRACKING_ORDER_STATUS_5 => '已签收',
        BaseConstService::TRACKING_ORDER_STATUS_6 => '取消',
        BaseConstService::TRACKING_ORDER_STATUS_7 => '回收站',
    ];

    //订单可出库状态1-是2-否
    public static $outStatusList = [
        BaseConstService::OUT_STATUS_1 => '是',
        BaseConstService::OUT_STATUS_2 => '否'
    ];

    //包裹状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    public static $packageStatusList = [
        BaseConstService::PACKAGE_STATUS_1 => '未取派',
        BaseConstService::PACKAGE_STATUS_2 => '取派中',
        BaseConstService::PACKAGE_STATUS_3 => '已完成',
        BaseConstService::PACKAGE_STATUS_4 => '取消取派',
        BaseConstService::PACKAGE_STATUS_5 => '回收站',
    ];

    //网点包裹类型1-入库2-出库
    public static $warehousePackageTypeList = [
        BaseConstService::WAREHOUSE_PACKAGE_TYPE_1,
        BaseConstService::WAREHOUSE_PACKAGE_TYPE_2,
    ];

    //订单异常标签1-正常2-异常
    public static $orderExceptionLabelList = [
        BaseConstService::ORDER_EXCEPTION_LABEL_1 => '正常',
        BaseConstService::ORDER_EXCEPTION_LABEL_2 => '异常'
    ];

    //订单性质1-包裹2-货物
    public static $orderConfigNatureList = [
        BaseConstService::ORDER_NATURE_1 => '包裹',
        BaseConstService::ORDER_NATURE_2 => '货物',
    ];

    //订单配置性质1-包裹2-货物
    public static $orderNatureList = [
        BaseConstService::ORDER_NATURE_1 => '全部',
        BaseConstService::ORDER_NATURE_2 => '包裹',
        BaseConstService::ORDER_NATURE_3 => '货物',
    ];

    //支付方式1-现金支付2-银行卡支付
    public static $batchPayTypeList = [
        BaseConstService::BATCH_PAY_TYPE_1 => '现金支付',
        BaseConstService::BATCH_PAY_TYPE_2 => '银行卡支付',
        BaseConstService::BATCH_PAY_TYPE_3 => '第三方支付',
        BaseConstService::BATCH_PAY_TYPE_4 => '无需支付',
    ];

    //站点异常标签1-正常2-异常
    public static $batchExceptionLabelList = [
        BaseConstService::BATCH_EXCEPTION_LABEL_1 => '正常',
        BaseConstService::BATCH_EXCEPTION_LABEL_2 => '异常',
    ];

    //batch 批次状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
    public static $batchStatusList = [
        BaseConstService::BATCH_WAIT_ASSIGN => '待分配',
        BaseConstService::BATCH_ASSIGNED => '已分配',
        BaseConstService::BATCH_WAIT_OUT => '待出库',
        BaseConstService::BATCH_DELIVERING => '取派中',
        BaseConstService::BATCH_CHECKOUT => '已签收',
        BaseConstService::BATCH_CANCEL => '取消取派',
    ];

    //batch 批次状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派
    public static $merchantBatchStatusList = [
        BaseConstService::MERCHANT_BATCH_STATUS_1 => '未取派',
        BaseConstService::MERCHANT_BATCH_STATUS_2 => '取派中',
        BaseConstService::MERCHANT_BATCH_STATUS_3 => '已签收',
        BaseConstService::MERCHANT_BATCH_STATUS_4 => '取派失败',
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

    //货主端在途类型1-未取派-2-取派中3-取派完成
    public static $merchantTourStatusList = [
        BaseConstService::MERCHANT_TOUR_STATUS_1 => '未取派',
        BaseConstService::MERCHANT_TOUR_STATUS_2 => '取派中',
        BaseConstService::MERCHANT_TOUR_STATUS_3 => '取派完成',
    ];

    //车辆车型1自动档-2手动挡
    public static $carTransmissionList = [
        BaseConstService::CAR_TRANSMISSION_1 => '自动挡',
        BaseConstService::CAR_TRANSMISSION_2 => '手动挡'
    ];

    //燃料类型1-柴油2-汽油3-混合动力4-电动
    public static $carFuelTypeList = [
        BaseConstService::CAR_FUEL_TYPE_1 => '柴油',
        BaseConstService::CAR_FUEL_TYPE_2 => '汽油',
        BaseConstService::CAR_FUEL_TYPE_3 => '混合动力',
        BaseConstService::CAR_FUEL_TYPE_4 => '电动'
    ];

    //租赁类型1-租赁（到期转私）2-私有3-租赁（到期转待定）4-临调车
    public static $carOwnerShipTypeList = [
        BaseConstService::CAR_OWNER_SHIP_TYPE_1 => '租赁（到期转私）',
        BaseConstService::CAR_OWNER_SHIP_TYPE_2 => '私有',
        BaseConstService::CAR_OWNER_SHIP_TYPE_3 => '租赁（到期转待定）',
        BaseConstService::CAR_OWNER_SHIP_TYPE_4 => '临调车',
    ];

    //车辆长度
    public static $carLengthTypeList = [
        BaseConstService::CAR_LENGTH_TYPE_1 => '4.2',
        BaseConstService::CAR_LENGTH_TYPE_2 => '5.2',
        BaseConstService::CAR_LENGTH_TYPE_3 => '6.2',
        BaseConstService::CAR_LENGTH_TYPE_4 => '6.8',
        BaseConstService::CAR_LENGTH_TYPE_5 => '7.2',
        BaseConstService::CAR_LENGTH_TYPE_6 => '7.6',
        BaseConstService::CAR_LENGTH_TYPE_7 => '8.2',
        BaseConstService::CAR_LENGTH_TYPE_8 => '9.6',
        BaseConstService::CAR_LENGTH_TYPE_9 => '12.5',
        BaseConstService::CAR_LENGTH_TYPE_10 => '13.0',
        BaseConstService::CAR_LENGTH_TYPE_11 => '13.5',
        BaseConstService::CAR_LENGTH_TYPE_12 => '14.6',
        BaseConstService::CAR_LENGTH_TYPE_13 => '15.0',
        BaseConstService::CAR_LENGTH_TYPE_14 => '16.0',
        BaseConstService::CAR_LENGTH_TYPE_15 => '16.5',
        BaseConstService::CAR_LENGTH_TYPE_16 => '17.5',
        BaseConstService::CAR_LENGTH_TYPE_17 => '18.0',
        BaseConstService::CAR_LENGTH_TYPE_18 => '19.5',
    ];

    //车辆车型列表
    public static $carModelTypeList = [
        BaseConstService::CAR_MODEL_TYPE_1 => '厢车',
        BaseConstService::CAR_MODEL_TYPE_2 => '高低板',
        BaseConstService::CAR_MODEL_TYPE_3 => '平板车',
        BaseConstService::CAR_MODEL_TYPE_4 => '高栏车',
        BaseConstService::CAR_MODEL_TYPE_5 => '挂车',
        BaseConstService::CAR_MODEL_TYPE_6 => '冷藏车',
        BaseConstService::CAR_MODEL_TYPE_7 => '牵引车',
        BaseConstService::CAR_MODEL_TYPE_8 => '新能源',
        BaseConstService::CAR_MODEL_TYPE_9 => '吊车',
        BaseConstService::CAR_MODEL_TYPE_10 => '叉车',
        BaseConstService::CAR_MODEL_TYPE_11 => '油罐车',
        BaseConstService::CAR_MODEL_TYPE_12 => '托平车',
        BaseConstService::CAR_MODEL_TYPE_13 => '其他',
    ];

    //维修自理1-是2-否
    public static $carRepairList = [
        BaseConstService::CAR_REPAIR_1 => '是',
        BaseConstService::CAR_REPAIR_2 => '否',
    ];

    //司机合作类型
    public static $driverTypeList = [
        BaseConstService::DRIVER_TYPE_1 => '自有车司机',
        BaseConstService::DRIVER_TYPE_2 => '承包车司机',
        BaseConstService::DRIVER_TYPE_3 => '长期合作司机',
        BaseConstService::DRIVER_TYPE_4 => '临时调用司机',
    ];

    //司机锁定状态
    public static $driverStatusList = [
        BaseConstService::DRIVER_TO_NORMAL => '正常',
        BaseConstService::DRIVER_TO_LOCK => '锁定',
    ];

    //设备状态
    public static $deviceStatusList = [
        BaseConstService::DEVICE_STATUS_1 => '在线',
        BaseConstService::DEVICE_STATUS_2 => '离线',
    ];

    //管理员端 图片目录
    public static $adminImageDirList = [
        BaseConstService::ADMIN_IMAGE_DRIVER_DIR => '司机图片目录',
        BaseConstService::ADMIN_IMAGE_TOUR_DIR => '取件线路图片目录',
        BaseConstService::ADMIN_IMAGE_CANCEL_DIR => '取消取派图片目录',
        BaseConstService::ADMIN_IMAGE_MERCHANT_DIR => '货主图片目录',
    ];

    //司机端 图片目录
    public static $driverImageDirList = [
        BaseConstService::DRIVER_IMAGE_TOUR_DIR => '取件线路图片目录'
    ];

    //管理员端 文件目录
    public static $adminFileDirList = [
        BaseConstService::ADMIN_FILE_DRIVER_DIR => '司机文件目录',
        BaseConstService::ADMIN_FILE_CAR_DIR => '车辆文件目录',
        BaseConstService::ADMIN_FILE_ORDER_DIR => '订单文件目录',
        BaseConstService::ADMIN_FILE_APK_DIR => '安装包目录',
        BaseConstService::ADMIN_FILE_TEMPLATE_DIR => '订单表格模板目录',
        BaseConstService::ADMIN_FILE_ADDRESS_TEMPLATE_DIR => '地址表格模板目录',
        BaseConstService::ADMIN_FILE_LINE_DIR => '线路目录'
    ];

    //管理员端 表格目录
    public static $adminExcelDirList = [
        BaseConstService::ADMIN_EXCEL_TOUR_DIR => '取件线路表格目录',
    ];

    //管理员端 文档目录
    public static $adminTxtDirList = [
        BaseConstService::ADMIN_TXT_TOUR_DIR => '取件线路文件目录',
    ];

    //司机端 文件目录
    public static $driverFileDirList = [
        BaseConstService::DRIVER_FILE_TOUR_DIR => '取件线路文件目录'
    ];

    //货主类型
    public static $merchantTypeList = [
        BaseConstService::MERCHANT_TYPE_1 => '个人',
        BaseConstService::MERCHANT_TYPE_2 => '货主',
    ];

    //货主支付方式
    public static $merchantSettlementTypeList = [
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_1 => '票结',
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_2 => '日结',
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_3 => '月结',

    ];

    //货主状态
    public static $merchantStatusList = [
        BaseConstService::MERCHANT_STATUS_1 => '启用',
        BaseConstService::MERCHANT_STATUS_2 => '禁用',
    ];


    public static $merchantOrderTypeList = [
        BaseConstService::MERCHANT_ORDER_TYPE_1 => '取件',
        BaseConstService::MERCHANT_ORDER_TYPE_2 => '派件',
        BaseConstService::MERCHANT_ORDER_TYPE_3 => '取派件',
    ];

    public static $driverEventList = [
        BaseConstService::DRIVER_EVENT_OUT_WAREHOUSE => '司机从网点出发',
        BaseConstService::DRIVER_EVENT_BATCH_ARRIVED => '司机到达客户家',
        BaseConstService::DRIVER_EVENT_BATCH_DEPART => '司机从客户家离开',
        BaseConstService::DRIVER_EVENT_BACK_WAREHOUSE => '司机返回网点',
    ];

    //费用等级
    public static $feeLevelList = [
        BaseConstService::FEE_LEVEL_1 => '系统级',
        BaseConstService::FEE_LEVEL_2 => '自定义'
    ];

    public static $showTypeList = [
        BaseConstService::ALL_SHOW => '全部展示',
        BaseConstService::LINE_RULE_SHOW => '全部展示',
    ];

    public static $rechargeStatusList = [
        BaseConstService::RECHARGE_STATUS_1 => '充值中',
        BaseConstService::RECHARGE_STATUS_2 => '充值失败',
        BaseConstService::RECHARGE_STATUS_3 => '充值完成',
    ];

    public static $rechargeStatisticsStatusList = [
        BaseConstService::RECHARGE_STATISTICS_STATUS_1 => '未上交',
        BaseConstService::RECHARGE_STATISTICS_STATUS_2 => '已上交'
    ];

    public static $verifyStatusList = [
        BaseConstService::VERIFY_STATUS_1 => '未审核',
        BaseConstService::VERIFY_STATUS_2 => '已审核',
    ];

    public static $isSkippedList = [
        BaseConstService::IS_SKIPPED => '已跳过',
        BaseConstService::IS_NOT_SKIPPED => '未跳过',
    ];

    public static $canSkipBatchList = [
        BaseConstService::CAN_NOT_SKIP_BATCH => '不能跳过',
        BaseConstService::CAN_SKIP_BATCH => '可以跳过',
    ];

    public static $languageList = [
        BaseConstService::CN => '汉语',
        BaseConstService::EN => '英语',
        BaseConstService::NL => '荷兰语',
    ];

    public static $merchantAdditionalStatusList = [
        BaseConstService::MERCHANT_ADDITIONAL_STATUS_1 => '开启',
        BaseConstService::MERCHANT_ADDITIONAL_STATUS_2 => '禁用'
    ];

    public static $tourDelayTypeList = [
        BaseConstService::TOUR_DELAY_TYPE_1 => '用餐休息',
        BaseConstService::TOUR_DELAY_TYPE_2 => '交通堵塞',
        BaseConstService::TOUR_DELAY_TYPE_3 => '更换行车路线',
        BaseConstService::TOUR_DELAY_TYPE_4 => '其他'
    ];

    public static $trackStatusList = [
        BaseConstService::TRACK_STATUS_1 => '取件中',
        BaseConstService::TRACK_STATUS_2 => '取件成功，等待派件',
        BaseConstService::TRACK_STATUS_3 => '取件成功，派件中'
    ];

    public static $stockExceptionStatusList = [
        BaseConstService::STOCK_EXCEPTION_STATUS_1 => '未审核',
        BaseConstService::STOCK_EXCEPTION_STATUS_2 => '审核成功',
        BaseConstService::STOCK_EXCEPTION_STATUS_3 => '审核失败'
    ];

    //编号类型
    public static $operationList = [
        BaseConstService::OPERATION_STORE => '新增',
        BaseConstService::OPERATION_UPDATE => '修改',
        BaseConstService::OPERATION_DESTROY => '删除',
        BaseConstService::OPERATION_STATUS_ON => '启用禁用',
        BaseConstService::OPERATION_STATUS_OFF => '启用禁用',
    ];

    public static $transportPriceTypeList = [
        BaseConstService::TRANSPORT_PRICE_TYPE_1 => '阶梯乘积值计算（固定费用+（每单位重量价格*重量价格）*（每单位里程价格*里程价格））',
        BaseConstService::TRANSPORT_PRICE_TYPE_2 => '阶梯固定值计算（固定费用+（重量价格档）*（里程价格档））',
    ];

    public static $expirationStatusList = [
        BaseConstService::EXPIRATION_STATUS_1 => '未超期',
        BaseConstService::EXPIRATION_STATUS_2 => '已超期',
        BaseConstService::EXPIRATION_STATUS_3 => '超期已处理',
    ];

    //车辆维保类型
    public static $carMaintainType = [
        BaseConstService::MAINTAIN_TYPE_1 => '保养',
        BaseConstService::MAINTAIN_TYPE_2 => '维修',
    ];

    //车辆维保收票状态
    public static $carMaintainTicket = [
        BaseConstService::IS_TICKET_1 => '已收票',
        BaseConstService::IS_TICKET_2 => '未收票',
    ];

    //车辆事故处理方式
    public static $carAccidentDealType = [
        BaseConstService::CAR_ACCIDENT_DEAL_TYPE_1 => '保险',
        BaseConstService::CAR_ACCIDENT_DEAL_TYPE_2 => '公司赔付',
    ];

    //车辆事故责任方
    public static $carAccidentDuty = [
        BaseConstService::CAR_ACCIDENT_DUTY_TYPE_1 => '主动',
        BaseConstService::CAR_ACCIDENT_DUTY_TYPE_2 => '被动',
    ];

    //车辆事故保险是否赔付
    public static $carAccidentInsPay = [
        BaseConstService::CAR_ACCIDENT_INS_PAY_TYPE_1 => '是',
        BaseConstService::CAR_ACCIDENT_INS_PAY_TYPE_2 => '否',
    ];

    //订单面单目的地模式
    public static $orderTemplateDestinationModeList = [
        BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1 => '省市区',
        BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_2 => '省市',
        BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_3 => '市区',
        BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_4 => '邮编',
    ];

    //订单模板类型
    public static $orderTemplateTypeList = [
        BaseConstService::ORDER_TEMPLATE_TYPE_1 => '模板一',
        BaseConstService::ORDER_TEMPLATE_TYPE_2 => '模板二',
    ];

    //备品单位
    public static $sparePartsUnit = [
        BaseConstService::SPARE_PARTS_UNIT_TYPE_1 => '条',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_2 => '桶',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_3 => '米',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_4 => '个',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_5 => '瓶',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_6 => '对',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_7 => '箱',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_8 => '台',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_9 => '件',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_10 => '把',
        BaseConstService::SPARE_PARTS_UNIT_TYPE_11 => '张',
    ];

    //备品领取状态
    public static $sparePartsRecordStatus = [
        BaseConstService::SPARE_PARTS_RECORD_TYPE_1 => '正常',
        BaseConstService::SPARE_PARTS_RECORD_TYPE_2 => '已作废',
    ];

    //订单运输模式
    public static $orderTransportModeList = [
        BaseConstService::ORDER_TRANSPORT_MODE_1 => '整车',
        BaseConstService::ORDER_TRANSPORT_MODE_2 => '零担'
    ];

    //订单始发地
    public static $orderOriginTypeList = [
        BaseConstService::ORDER_ORIGIN_TYPE_1 => '从网点出发，回到网点',
        BaseConstService::ORDER_ORIGIN_TYPE_2 => '装货地',
    ];

    //材料类型
    public static $materialTypeList = [
        BaseConstService::MATERIAL_TYPE_1 => '包装材料',
        BaseConstService::MATERIAL_TYPE_2 => '家电家居',
        BaseConstService::MATERIAL_TYPE_3 => '五金配件',
        BaseConstService::MATERIAL_TYPE_4 => '机械大件',
        BaseConstService::MATERIAL_TYPE_5 => '工业原料',
        BaseConstService::MATERIAL_TYPE_6 => '服装纺织',
        BaseConstService::MATERIAL_TYPE_7 => '生活商品',
        BaseConstService::MATERIAL_TYPE_8 => '电子科技',
        BaseConstService::MATERIAL_TYPE_9 => '装修建材',
        BaseConstService::MATERIAL_TYPE_10 => '其他',
    ];

    //材料包装
    public static $materialPackTypeList = [
        BaseConstService::MATERIAL_PACK_TYPE_1 => '纸箱',
        BaseConstService::MATERIAL_PACK_TYPE_2 => '铁桶',
        BaseConstService::MATERIAL_PACK_TYPE_3 => '纤袋',
        BaseConstService::MATERIAL_PACK_TYPE_4 => '泡沫箱',
        BaseConstService::MATERIAL_PACK_TYPE_5 => '托盘',
        BaseConstService::MATERIAL_PACK_TYPE_6 => 'ICB桶',
        BaseConstService::MATERIAL_PACK_TYPE_7 => '木框',
        BaseConstService::MATERIAL_PACK_TYPE_8 => '异性',
        BaseConstService::MATERIAL_PACK_TYPE_9 => '塑料桶',
        BaseConstService::MATERIAL_PACK_TYPE_10 => '铁架',
        BaseConstService::MATERIAL_PACK_TYPE_11 => '裸装',
        BaseConstService::MATERIAL_PACK_TYPE_12 => '其他',

    ];

    //包裹特性
    public static $packageFeatureList = [
        BaseConstService::PACKAGE_FEATURE_1 => '常温',
        BaseConstService::PACKAGE_FEATURE_2 => '风房',
        BaseConstService::PACKAGE_FEATURE_3 => '冷藏',
        BaseConstService::PACKAGE_FEATURE_4 => '预售',
    ];

    //控货方式
    public static $orderControlModeList = [
        BaseConstService::ORDER_CONTROL_MODE_1 => '无',
        BaseConstService::ORDER_CONTROL_MODE_2 => '等通知放货',
    ];

    //金额种类
    public static $orderAmountTypeList = [
        BaseConstService::ORDER_AMOUNT_TYPE_1 => '基础运费',
        BaseConstService::ORDER_AMOUNT_TYPE_2 => '货物价值',
        BaseConstService::ORDER_AMOUNT_TYPE_3 => '保价费',
        BaseConstService::ORDER_AMOUNT_TYPE_4 => '包装费',
        BaseConstService::ORDER_AMOUNT_TYPE_5 => '送货费',
        BaseConstService::ORDER_AMOUNT_TYPE_6 => '上楼费',
        BaseConstService::ORDER_AMOUNT_TYPE_7 => '接货费',
        BaseConstService::ORDER_AMOUNT_TYPE_8 => '装卸费',
        BaseConstService::ORDER_AMOUNT_TYPE_9 => '其他费用',
        BaseConstService::ORDER_AMOUNT_TYPE_10 => '代收货款',
        BaseConstService::ORDER_AMOUNT_TYPE_11 => '货款手续费',
    ];

    //订单费用状态
    public static $orderAmountStatusList = [
        BaseConstService::ORDER_AMOUNT_STATUS_1 => '预产生',
        BaseConstService::ORDER_AMOUNT_STATUS_2 => '已产生',
        BaseConstService::ORDER_AMOUNT_STATUS_3 => '已支付',
        BaseConstService::ORDER_AMOUNT_STATUS_4 => '已入账',
        BaseConstService::ORDER_AMOUNT_STATUS_5 => '已取消',
    ];

    //订单轨迹类型
    public static $trackingOrderTrailTypeList = [
        BaseConstService::TRACKING_ORDER_TRAIL_CREATED => '开单',
        BaseConstService::TRACKING_ORDER_TRAIL_JOIN_BATCH => '加入站点',
        BaseConstService::TRACKING_ORDER_TRAIL_JOIN_TOUR => '加入任务',
        BaseConstService::TRACKING_ORDER_TRAIL_ASSIGN_DRIVER => '分配司机',
        BaseConstService::TRACKING_ORDER_TRAIL_REVENUE_OUTLETS => '加入网点',
        BaseConstService::TRACKING_ORDER_TRAIL_LOCK => '已装货',
        BaseConstService::TRACKING_ORDER_TRAIL_DELIVERING => '在途',
        BaseConstService::TRACKING_ORDER_TRAIL_DELIVERED => '已签收',
        BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_DELIVER => '取消',
        BaseConstService::TRACKING_ORDER_TRAIL_CANCEL_ASSIGN_DRIVER => '取消司机',
        BaseConstService::TRACKING_ORDER_TRAIL_UN_LOCK => '取消装货',
        BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_BATCH => '移出站点',
        BaseConstService::TRACKING_ORDER_TRAIL_REMOVE_TOUR => '移出任务',
        BaseConstService::TRACKING_ORDER_TRAIL_DELETE => '删除',
        BaseConstService::TRACKING_ORDER_TRAIL_CUSTOMER => '自定义',
    ];

    /**
     * 包裹轨迹类型
     * @var array
     */
    public static $packageTrailTypeList = [
        BaseConstService::PACKAGE_TRAIL_CREATED => '待取件',//包裹创建
        BaseConstService::PACKAGE_TRAIL_PICKUP => '待取件',//取件中
        BaseConstService::PACKAGE_TRAIL_PICKUP_DONE => '已取件',//取件完成
        BaseConstService::PACKAGE_TRAIL_ALLOCATE => '已取件',//入库分拣
        BaseConstService::PACKAGE_TRAIL_PACK => '运输中',//装袋
        BaseConstService::PACKAGE_TRAIL_LOAD => '运输中',//装车
        BaseConstService::PACKAGE_TRAIL_OUT => '运输中',//发车
        BaseConstService::PACKAGE_TRAIL_IN => '运输中',//到车
        BaseConstService::PACKAGE_TRAIL_UNLOAD => '运输中',//卸车
        BaseConstService::PACKAGE_TRAIL_UNPACK => '运输中',//拆袋
        BaseConstService::PACKAGE_TRAIL_PIE => '派件中',//派件中
        BaseConstService::PACKAGE_TRAIL_PIE_DONE => '已签收',//派件完成
        BaseConstService::PACKAGE_TRAIL_PICKUP_CANCEL => '取件失败',//取件失败
        BaseConstService::PACKAGE_TRAIL_PIE_CANCEL => '派件失败',//派件失败
        BaseConstService::PACKAGE_TRAIL_DELETED => '已取消',//回收站
    ];

    public static $orderReceiptTypeList = [
        BaseConstService::ORDER_RECEIPT_TYPE_1 => '原单返回',
    ];

    public static $addressTypeList = [
        BaseConstService::ADDRESS_TYPE_1 => '发件人',
        BaseConstService::ADDRESS_TYPE_2 => '收件人',
    ];

    public static $mapConfigFrontTypeList = [
        BaseConstService::MAP_CONFIG_FRONT_TYPE_1 => '谷歌',
        BaseConstService::MAP_CONFIG_FRONT_TYPE_2 => '百度',
        BaseConstService::MAP_CONFIG_FRONT_TYPE_3 => '腾讯'
    ];

    public static $mapConfigBackTypeList = [
        BaseConstService::MAP_CONFIG_BACK_TYPE_1 => '谷歌',
        BaseConstService::MAP_CONFIG_BACK_TYPE_2 => '百度',
        BaseConstService::MAP_CONFIG_BACK_TYPE_3 => '腾讯'
    ];

    public static $mapConfigMobileTypeList = [
        BaseConstService::MAP_CONFIG_MOBILE_TYPE_1 => '谷歌',
        BaseConstService::MAP_CONFIG_MOBILE_TYPE_2 => '百度',
        BaseConstService::MAP_CONFIG_MOBILE_TYPE_3 => '腾讯'
    ];

    public static $orderTemplateIsDefaultList = [
        BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_1 => '默认',
        BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_2 => '非默认',
    ];

    public static $feePayerList = [
        BaseConstService::FEE_PAYER_1 => 1,
        BaseConstService::FEE_PAYER_2 => 2
    ];

    //重量单位
    publiC static $weightUnitTypeList = [
        BaseConstService::WEIGHT_UNIT_TYPE_1 => '千克',
        BaseConstService::WEIGHT_UNIT_TYPE_2 => '磅',
    ];

    //重量单位符号
    publiC static $weightUnitTypeSymbol = [
        BaseConstService::WEIGHT_UNIT_TYPE_1 => 'kg',
        BaseConstService::WEIGHT_UNIT_TYPE_2 => 'lb',
    ];

    //货币单位
    public static $currencyUnitTypeList = [
        BaseConstService::CURRENCY_UNIT_TYPE_1 => '人民币',
        BaseConstService::CURRENCY_UNIT_TYPE_2 => '美元',
        BaseConstService::CURRENCY_UNIT_TYPE_3 => '欧元',
    ];

    //货币单位符号
    public static $currencyUnitTypeSymbol = [
        BaseConstService::CURRENCY_UNIT_TYPE_1 => '¥',
        BaseConstService::CURRENCY_UNIT_TYPE_2 => '$',
        BaseConstService::CURRENCY_UNIT_TYPE_3 => '€',
    ];

    //体积单位
    public static $volumeUnitTypeList = [
        BaseConstService::VOLUME_UNIT_TYPE_1 => '立方厘米',
        BaseConstService::VOLUME_UNIT_TYPE_2 => '立方米',
    ];

    //体积单位符号
    public static $volumeUnitTypeSymbol = [
        BaseConstService::VOLUME_UNIT_TYPE_1 => 'cm³',
        BaseConstService::VOLUME_UNIT_TYPE_2 => 'm³',
    ];

    //调度规则
    public static $schedulingTypeList = [
        BaseConstService::SCHEDULING_TYPE_1 => '自动调度',
        BaseConstService::SCHEDULING_TYPE_2 => '手动调度',
    ];

    //站点取消原因
    public static $batchCancelTypeList = [
        BaseConstService:: BATCH_CANCEL_TYPE_1 => "客户不在家",
        BaseConstService:: BATCH_CANCEL_TYPE_2 => "另约时间",
        BaseConstService:: BATCH_CANCEL_TYPE_3 => "其他原因"
    ];

    public static $schedulingTypeTips = [
        BaseConstService::SCHEDULING_TYPE_1 => '下单后生成运单，自动对运单进行线路分配',
        BaseConstService::SCHEDULING_TYPE_2 => '下单后不生成运单，需手动分配线路',
    ];

    //邮件模板类型
    public static $emailTemplateTypeList = [
        BaseConstService::EMAIL_TEMPLATE_TYPE_1 => '注册',
        BaseConstService::EMAIL_TEMPLATE_TYPE_2 => '找回密码',
        BaseConstService::EMAIL_TEMPLATE_TYPE_3 => '下单'
    ];

    //邮件模板状态
    public static $emailTemplateStatusList = [
        BaseConstService::EMAIL_TEMPLATE_STATUS_1 => '开启',
        BaseConstService::EMAIL_TEMPLATE_STATUS_2 => '关闭',
    ];

    public static $warehouseTypeList = [
        BaseConstService::WAREHOUSE_TYPE_1 => '加盟',
        BaseConstService::WAREHOUSE_TYPE_2 => '自营',
    ];

    public static $warehouseAcceptanceTypeList = [
        BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1 => '取件',
        BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2 => '派件',
        BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_3 => '仓配一体',
    ];

    public static $warehouseIsCenterTypeList = [
        BaseConstService::WAREHOUSE_IS_CENTER_1 => '是',
        BaseConstService::WAREHOUSE_IS_CENTER_2 => '否',
    ];

    public static $lineTestStatusList = [
        BaseConstService::LINE_TEST_STATUS_1 => '寄件人',
        BaseConstService::LINE_TEST_STATUS_2 => '网点取件',
        BaseConstService::LINE_TEST_STATUS_3 => '分拨中心',
        BaseConstService::LINE_TEST_STATUS_4 => '网点派件',
        BaseConstService::LINE_TEST_STATUS_5 => '收件人',
        BaseConstService::LINE_TEST_STATUS_6 => '网点取件/派件',
    ];

    //员工状态
    public static $employeeForbidLoginList = [
        BaseConstService::EMPLOYEE_FORBID_LOGIN_1 => '禁用',
        BaseConstService::EMPLOYEE_FORBID_LOGIN_2 => '启用',
    ];

    /**
     * 格式化常量列表
     * @param $list
     * @return array
     */
    public static function formatList($list)
    {
        /******************************************若非中文,则需要翻译*************************************************/
        if (App::getLocale() !== 'cn') {
            foreach ($list as $key => $item) {
                $list[$key] = __($item);
            }
        }
        return array_values(collect($list)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
    }


    /**
     * 此函数用于访问静态变量的同时对其进行翻译
     * @param $name
     * @param $args
     * @return array|string|null
     */
    public static function __callStatic($name, $args)
    {
        /******************************************若为中文,则不用翻译*************************************************/
        if (App::getLocale() === 'cn') {
            return !empty($args) ? self::$$name[$args[0]] : self::$$name;
        }
        /******************************************若非中文,则需要翻译*************************************************/
        //若args存在,则获取翻译单个值
        if (!empty($args)) {
            return __(self::$$name[$args[0]]);
        }
        //若args不存在,则获取翻译整个数组
        $arr = [];
        foreach (self::$$name as $key => $item) {
            $arr[$key] = __($item);
        }
        return $arr;
    }
}
