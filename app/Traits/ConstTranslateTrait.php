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
 * @method static noTypeList($args = null)
 * @method static lineRuleList($args = null)
 * @method static printTemplateList($args = null)
 * @method static weekList($args = null)
 * @method static orderSourceList($arg = null)
 * @method static orderTypeList($args = null)
 * @method static orderSettlementTypeList($args = null)
 * @method static orderStatusList($args = null)
 * @method static packageStatusList($args = null)
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
 * @method static merchantPackageStatusList($args = null)
 * @method static rechargeStatusList($args = null)
 * @method static verifyStatusList($args = null)
 */
trait ConstTranslateTrait
{
    //编号类型
    public static $noTypeList = [
        BaseConstService::ORDER_NO_TYPE => '订单编号规则',
        BaseConstService::BATCH_NO_TYPE => '站点编号规则',
        BaseConstService::BATCH_EXCEPTION_NO_TYPE => '站点异常编号规则',
        BaseConstService::TOUR_NO_TYPE => '取件线路编号规则',
        BaseConstService::RECHARGE_NO_TYPE => '充值单号规则',
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

    //订单类型1-取2-派
    public static $orderTypeList = [
        BaseConstService::ORDER_TYPE_1 => '取件',
        BaseConstService::ORDER_TYPE_2 => '派件',
    ];

    //订单来源1-手动添加2-批量导入3-第三方
    public static $orderSourceList = [
        BaseConstService::ORDER_SOURCE_1 => '手动添加',
        BaseConstService::ORDER_SOURCE_2 => '批量导入',
        BaseConstService::ORDER_SOURCE_3 => '第三方'
    ];

    //订单结算方式1-寄付2-到付
    public static $orderSettlementTypeList = [
        BaseConstService::ORDER_SETTLEMENT_TYPE_1 => '寄付',
        BaseConstService::ORDER_SETTLEMENT_TYPE_2 => '到付',
    ];

    //订单状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    public static $orderStatusList = [
        BaseConstService::ORDER_STATUS_1 => '待分配',
        BaseConstService::ORDER_STATUS_2 => '已分配',
        BaseConstService::ORDER_STATUS_3 => '待出库',
        BaseConstService::ORDER_STATUS_4 => '取派中',
        BaseConstService::ORDER_STATUS_5 => '已完成',
        BaseConstService::ORDER_STATUS_6 => '取消取派',
        BaseConstService::ORDER_STATUS_7 => '回收站',
    ];

    //包裹状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    public static $packageStatusList = [
        BaseConstService::ORDER_STATUS_1 => '待分配',
        BaseConstService::ORDER_STATUS_2 => '已分配',
        BaseConstService::ORDER_STATUS_3 => '待出库',
        BaseConstService::ORDER_STATUS_4 => '取派中',
        BaseConstService::ORDER_STATUS_5 => '已完成',
        BaseConstService::ORDER_STATUS_6 => '取消取派',
        BaseConstService::ORDER_STATUS_7 => '回收站',
    ];

    //商户端包裹状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-回收站
    public static $merchantPackageStatusList = [
        BaseConstService::MERCHANT_PACKAGE_STATUS_1 => '未取派',
        BaseConstService::MERCHANT_PACKAGE_STATUS_2 => '取派中',
        BaseConstService::MERCHANT_PACKAGE_STATUS_3 => '已完成',
        BaseConstService::MERCHANT_PACKAGE_STATUS_4 => '取消取派',
        BaseConstService::MERCHANT_PACKAGE_STATUS_5 => '回收站',
    ];

    //订单异常标签1-正常2-异常
    public static $orderExceptionLabelList = [
        BaseConstService::ORDER_EXCEPTION_LABEL_1 => '正常',
        BaseConstService::ORDER_EXCEPTION_LABEL_2 => '异常'
    ];

    //订单性质1-包裹2-材料
    public static $orderNatureList = [
        BaseConstService::ORDER_NATURE_1 => '包裹',
        BaseConstService::ORDER_NATURE_2 => '材料',
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

    //商户端在途类型1-未取派-2-取派中3-取派完成
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

    //租赁类型1-租赁（到期转私）2-私有3-租赁（到期转待定）
    public static $carOwnerShipTypeList = [
        BaseConstService::CAR_OWNER_SHIP_TYPE_1 => '租赁（到期转私）',
        BaseConstService::CAR_OWNER_SHIP_TYPE_2 => '私有',
        BaseConstService::CAR_OWNER_SHIP_TYPE_3 => '租赁（到期转待定）',
    ];

    //维修自理1-是2-否
    public static $carRepairList = [
        BaseConstService::CAR_REPAIR_1 => '是',
        BaseConstService::CAR_REPAIR_2 => '否',
    ];

    //司机合作类型
    public static $driverTypeList = [
        BaseConstService::DRIVER_HIRE => '雇佣',
        BaseConstService::DRIVER_CONTRACTOR => '包线',
    ];

    //司机锁定状态
    public static $driverStatusList = [
        BaseConstService::DRIVER_TO_NORMAL => '正常',
        BaseConstService::DRIVER_TO_LOCK => '锁定',
    ];

    //管理员端 图片目录
    public static $adminImageDirList = [
        BaseConstService::ADMIN_IMAGE_DRIVER_DIR => '司机图片目录',
        BaseConstService::ADMIN_IMAGE_TOUR_DIR => '取件线路图片目录',
        BaseConstService::ADMIN_IMAGE_CANCEL_DIR => '取消取派图片目录',
        BaseConstService::ADMIN_IMAGE_MERCHANT_DIR => '商户图片目录',
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
        BaseConstService::ADMIN_FILE_TEMPLATE_DIR => '表格模板目录',
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

    //商户类型
    public static $merchantTypeList = [
        BaseConstService::MERCHANT_TYPE_1 => '个人',
        BaseConstService::MERCHANT_TYPE_2 => '商户',
    ];

    //商户支付方式
    public static $merchantSettlementTypeList = [
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_1 => '票结',
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_2 => '日结',
        BaseConstService::MERCHANT_SETTLEMENT_TYPE_3 => '月结',

    ];

    //商户状态
    public static $merchantStatusList = [
        BaseConstService::MERCHANT_STATUS_1 => '启用',
        BaseConstService::MERCHANT_STATUS_2 => '禁用',
    ];

    public static $driverEventList = [
        BaseConstService::DRIVER_EVENT_OUT_WAREHOUSE => '司机从仓库出发',
        BaseConstService::DRIVER_EVENT_BATCH_ARRIVED => '司机到达客户家',
        BaseConstService::DRIVER_EVENT_BATCH_DEPART => '司机从客户家离开',
        BaseConstService::DRIVER_EVENT_BACK_WAREHOUSE => '司机返回仓库',
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

    public static $verifyStatusList = [
        BaseConstService::VERIFY_STATUS_1 => '未审核',
        BaseConstService::VERIFY_STATUS_2 => '已审核',
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
