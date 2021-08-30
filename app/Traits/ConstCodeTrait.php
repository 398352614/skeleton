<?php

/**
 * 常量翻译 服务
 * Created by PhpStorm
 * User: long
 * Date: 2019/12/20
 * Time: 17:02
 */

namespace App\Traits;

use App\Services\BaseCodeService;
use Illuminate\Support\Facades\App;

/**
 * Trait ConstTranslateTrait
 * @package App\Traits
 * @method static serverExceptionList($args = null)
 * @method static authExceptionList($args = null)
 * @method static apiExceptionList($args = null)
 * @method static dataExceptionList($args = null)
 * @method static businessExceptionList($args = null)
 * 常用中英对照：
 * 操作operation新增create删除delete修改update
 * 存在exist不not需要required错误wrong没有no失败failure重复duplicate不一致different禁止forbidden合适fit
 * 第三方订单api_order信息info
 * 线路更新refresh智能优化navigation线路的地图部分route
 * 开始begin结束end
 * 所有数字均用阿拉伯数字表示，并与其他单词或数字用下划线连接，字段名除外，例如express_first_no。
 * amount金额
 **/
trait ConstCodeTrait
{
    //1000-1999服务器错误
    public static $serverExceptionList = [
        BaseCodeService::SERVER_BUSY => '系统繁忙，请稍后重试',
        BaseCodeService::FUNCTION_NOT_EXIST => '方法不存在',

        BaseCodeService::UPLOAD_FAILURE  => '上传失败',
        BaseCodeService::IMAGE_UPLOAD_FAILURE => '图片上传失败，请重新操作',
        BaseCodeService::FILE_UPLOAD_FAILURE => '文件上传失败，请重新操作',
        BaseCodeService::TXT_UPLOAD_FAILURE => '文档上传失败，请重新操作',

        BaseCodeService::IMAGE_DOWNLOAD_FAILURE => '图片获取失败，请重新操作',
        BaseCodeService::EXCEL_EXPORT_FAILURE => '表格导出失败，请重新操作',

        BaseCodeService::EXCEL_FORMAT_WRONG => '表格格式不正确，请使用正确的模板导入',
        BaseCodeService::NO_DIR => '没有对应目录',

        BaseCodeService::TEST_CREATE_FAILURE => '测试数据新增失败',
        BaseCodeService::TEST_UPDATE_FAILURE => '测试数据修改失败',
        BaseCodeService::LETTER_RULE_WRONG => '字母规则不正确',

    ];

    //2000-2999账户错误。包括权限错误
    public static $authExceptionList = [
        BaseCodeService::ACCOUNT_NOT_REGISTERED => '邮箱未注册，请先注册',
        BaseCodeService::PASSWORD_WRONG => '用户名或密码错误',
        BaseCodeService::account_forbidden => '账户已被禁用，请联系管理员！',
        BaseCodeService::old_password_wrong => '原密码不正确',
        BaseCodeService::code_wrong => '验证码错误',
        BaseCodeService::account_registered => '账号已注册，请直接登录',
        BaseCodeService::register_failure => '企业注册失败',
        BaseCodeService::init_roles_failure => '初始化权限组失败',
        BaseCodeService::init_price_failure => '初始化运价失败',
        BaseCodeService::init_merchant_group_failure => '初始化用户组失败',
        BaseCodeService::init_merchant_failure => '初始化用户失败',
        BaseCodeService::init_merchant_api_failure => '初始化用户API失败',
        BaseCodeService::init_fee_failure => '初始化费用失败',
        BaseCodeService::email_registered => '邮箱已注册，请直接登录',
        BaseCodeService::account_not_exist => '用户不存在，请检查用户名',
        BaseCodeService::code_send_failure => '验证码发送失败',
        BaseCodeService::user_name_registered => '名称已注册，请直接登录',
        BaseCodeService::authentication_failure => '用户认证失败',
        BaseCodeService::account_have_not_permission => '当前用户没有该权限',
        BaseCodeService::company_config_necessary => '请先联系管理员到配置管理，填写高级配置内容',
        BaseCodeService::route_exist => '路由已存在',
        BaseCodeService::permission_not_exist => '当前权限不存在',
        BaseCodeService::role_exist => '权限组已存在',
        BaseCodeService::role_not_exist => '权限组不存在',
        BaseCodeService::company_not_exist => '不存在的公司',
        BaseCodeService::admin_cant_move => '超级管理员只能在管理员组',
        BaseCodeService::cant_delete_yourself => '无法删除自己',
        BaseCodeService::admin_role_cant_update => '管理员组权限不允许操作',
        BaseCodeService::cant_operate_admin => '存在超级管理员，不能操作',


    ];

    //3000-3999第三方错误。包括：智能优化相关，司机坐标相关，第三方充值相关
    public static $apiExceptionList = [
        BaseCodeService::tour_no_required => '未传入取件线路编号',
        BaseCodeService::TOUR_LOCKED => '当前取件线路已锁定,请稍后操作',
        BaseCodeService::SEND_FAILURE => '发送失败',
        BaseCodeService::tour_locked => '当前正在使用该线路，不能操作',
        BaseCodeService::cant_find_next_destination => '未查找到下一个目的地',
        BaseCodeService::batch_count_wrong => '线路的站点数量不正确',
        BaseCodeService::tour_end => '取件线路已完成，不能优化',
        BaseCodeService::action_not_exist => '不存在的动作',
        BaseCodeService::update_time_out => '更新时间已超时',
        BaseCodeService::tour_update_failure => '更新线路失败，请稍后重试',
        BaseCodeService::route_navigation_failure => '优化线路失败',
        BaseCodeService::navigation_failure => '优化失败',
        BaseCodeService::route_refresh_failure => '线路自动更新失败',
        BaseCodeService::refresh_failure => '线路更新失败',
        BaseCodeService::parameter_lack => '缺少参数key,sign,timestamp或data',
        BaseCodeService::address_not_accurate => '地址不够精确,请检查',
        BaseCodeService::out_user_info_get_failure => '拉取第三方用户信息失败',
        BaseCodeService::out_user_not_exist => '客户不存在，请检查客户编码是否正确',
        BaseCodeService::phone_end_verify_failure => '手机尾号验证失败，请重新输入',
        BaseCodeService::verify_operation_failure => '验证操作失败',
        BaseCodeService::recharge_invalidation => '充值信息已失效，请重新充值',
        BaseCodeService::phone_end_verify_failure_1 => '手机尾号验证未通过，请重新提交',
        BaseCodeService::out_user_info_wrong => '用户信息不正确，请重新充值',
        BaseCodeService::recharge_operation_failure => '充值失败',
        BaseCodeService::recharge_expired => '充值请求已过期，请重新充值',
        BaseCodeService::recharge_statistics_failure => '充值统计失败',
        BaseCodeService::recharge_statistics_today_failure=> '纳入当日充值统计失败',
        BaseCodeService::location_collect_failure => '采集位置失败',
        BaseCodeService::sort_wrong => '请按优化的站点顺序进行派送，或手动跳过之前的站点',
        BaseCodeService::refresh_time_failure => '更新到达时间失败，请重新操作',
        BaseCodeService::parameter_required => '查询字段至少一个不为空',
        BaseCodeService::net_error_country => '可能由于网络问题，无法获取国家信息，请稍后尝试',
        BaseCodeService::country_missing => '系统无相关国家信息',
        BaseCodeService::net_error_postcode_house_no => '可能由于网络问题，无法根据邮编和门牌号码获取城市和地址信息，请稍后再尝试',
        BaseCodeService::net_error_addresses => '可能由于网络问题，无法获取地址信息，请稍后再尝试',
        BaseCodeService::addresses_wrong => '国家，城市，街道，门牌号或邮编不正确，请仔细检查输入或联系客服',
        BaseCodeService::net_error_map => '可能由于网络问题，无法获取地图，请稍后再尝试',
        BaseCodeService::route_locked => '当前 tour 正在操作中,请稍后操作',
        BaseCodeService::refresh_line_failure => '更新线路信息失败，请稍后重试',
        BaseCodeService::post_code_house_no_wrong => '邮编或门牌号码不正确，请仔细检查输入或联系客服',

    ];


    //4000-4999数据错误。包括：数据基础增删改查，基础表单验证
    public static $dataExceptionList = [
        BaseCodeService::operation_failure => '操作失败，请重新操作',
        BaseCodeService::create_failure => '新增失败',
        BaseCodeService::update_failure => '修改失败，请重新操作',
        BaseCodeService::create_failure => '新增失败，请重新操作',
        BaseCodeService::delete_failure => '删除失败，请重新操作',
        BaseCodeService::delete_failure => '删除失败',

        BaseCodeService::order_no_required => '订单号是必须的',

        BaseCodeService::merchant_not_exist => '商户不存在，请重新选择商户',
        BaseCodeService::merchant_name_exist => '商户名称已存在',

        BaseCodeService::driver_not_exist => '司机不存在或者不属于当前公司',

        BaseCodeService::address_exist => '地址已存在，不能重复添加',
        BaseCodeService::address_exist => '地址已存在，不能重复添加',

        BaseCodeService::line_create_failure => '线路新增失败',
        BaseCodeService::line_update_failure => '线路修改失败',
        BaseCodeService::line_deletefailure => '线路删除失败',
        BaseCodeService::line_not_exist => '线路不存在',

        BaseCodeService::warehouse_not_exist => '仓库不存在！',

        BaseCodeService::country_exist => '已添加了国家，不能再次添加国家',
        BaseCodeService::country_not_exist => '国家不存在',
        BaseCodeService::country_create_failure => '国家新增失败',

        BaseCodeService::tour_no_required => '取件线路编号是必须的',
        BaseCodeService::update_failure => '修改失败',
        BaseCodeService::car_brand_not_exist => '车辆品牌不存在',
        BaseCodeService::car_model_update_failure => '车辆修改失败',
        BaseCodeService::car_delete => '车辆删除失败',
        BaseCodeService::address_template_not_exist => '地址模板不存在',
        BaseCodeService::company_name_exist => '公司名称已存在',

        BaseCodeService::driver_delete_failure => '司机删除失败',
        BaseCodeService::driver_create_failure=> '司机新增失败',

        BaseCodeService::employee_create_failure => '员工新增失败',
        BaseCodeService::employee_update_failure => '员工修改失败',
        BaseCodeService::employee_update_password_failure => '修改员工密码失败',
        BaseCodeService::employee_delete_failure => '员工删除失败',

        BaseCodeService::date_format_wrong => '日期格式不正确',
        BaseCodeService::begin_date_required => '请选择开始时间',
        BaseCodeService::end_date_required => '请选择结束时间',
        BaseCodeService::line_range_create_failure => '线路范围新增失败',
        BaseCodeService::postcode_not_null => '邮编范围不能为空',

        BaseCodeService::line_not_exist => '线路不存在',
        BaseCodeService::line_range_update_failure => '线路范围修改失败',
        BaseCodeService::line_range_delete_failure => '线路范围删除失败',

        BaseCodeService::out_order_no_exist => '材料外部标识[:out_order_no]已存在',
        BaseCodeService::out_order_no_duplicate => '材料代码-外部标识[:code]有重复！不能添加订单',
        BaseCodeService::merchant_not_exist => '商户不存在',
        BaseCodeService::merchant_api_exist => '当前商户已创建API对接信息',
        BaseCodeService::price_not_exist => '运价不存在或已被禁用',
        BaseCodeService::merchant_group_not_exist=> '商户组不存在',
        BaseCodeService::order_not_exist => '订单不存在！',
        BaseCodeService::order_create_failure => '订单新增失败',
        BaseCodeService::out_order_no_exist => '外部订单号已存在',
        BaseCodeService::order_package_create_failure => '订单包裹新增失败！',
        BaseCodeService::order_material_create_failure => '订单材料新增失败！',
        BaseCodeService::order_exist => '订单[:order_no]不存在',
        BaseCodeService::express_first_no_duplicate => '快递单号[:express_no]有重复！不能添加订单',
        BaseCodeService::express_second_no_duplicate => '快递单号2[:express_no]有重复！不能添加订单',
        BaseCodeService::express_no_duplicate => '快递单号1[:express_no]已存在在快递单号2中',
        BaseCodeService::tour_not_exist => '没找到相关进行中的线路',
        BaseCodeService::car_not_exist => '暂无车辆信息',
        BaseCodeService::tracking_order_update_failure => '运单修改失败',

        BaseCodeService::warehouse_create_failure => '仓库新增失败,请重新操作',
        BaseCodeService::warehouse_house_update_failure => '仓库修改失败，请重新操作',
        BaseCodeService::warehouse_delete_failure => '仓库删除失败，请重新操作',
        BaseCodeService::company_not_exist => '企业不存在',
        BaseCodeService::memorandum_create_failure => '备忘录新增失败',
        BaseCodeService::memorandum_update_failure => '备忘录修改失败',
        BaseCodeService::memorandum_delete_failure => '备忘录删除失败',
        BaseCodeService::order_not_exist => '订单不存在',
        BaseCodeService::package_not_in_system => '当前包裹不存在系统中',
        BaseCodeService::remark_failure => '备注失败，请重新操作',
        BaseCodeService::driver_not_exist => '司机不存在',
        BaseCodeService::material_create_failure => '材料新增失败',
        BaseCodeService::tour_not_exist => '取件线路不存在',
        BaseCodeService::tour_deleted => '线路已删除，请联系管理员',
        BaseCodeService::batch_not_exist => '站点不存在',
        BaseCodeService::fee_not_exist => '费用不存在',
        BaseCodeService::address_wrong => '地址不正确，请重新选择地址',
        BaseCodeService::second_address_wrong => '派件地址不正确，请重新选择地址',
        BaseCodeService::api_order_cant_update => '该状态的第三方订单不能修改',
        BaseCodeService::order_update_info_different => '所选多个订单电话或取件日期不一致，无法统一修改',
        BaseCodeService::wrong_address_for_date => '地址数据不正确，无法拉取可选日期',
        BaseCodeService::lot_delete_failure => '批量删除失败,订单[:order_no]删除失败,原因-[:exception_info]',
        BaseCodeService::tour_not_begin_for_tracking => '运输未开始，暂无物流信息',
        BaseCodeService::no_tracking_info => '暂无物流信息',
        BaseCodeService::tour_not_begin_for_route => '该取件线路不在取派中，无法进行追踪',

    ];


    //5000-5999业务错误。包括：特殊表单验证，状态相关，业务操作
    public static $businessExceptionList = [
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::SERVER_BUSY => '',
        BaseCodeService::driver_not_exists => '司机不存在或者不属于当前公司',
        BaseCodeService::address_template_2_cant_export => '地址模板二无法进行批量导入，请联系管理员',
        BaseCodeService::no_fit_line => '当前没有合适的线路，请先联系管理员',
        BaseCodeService::order_no_fit_line => '当前订单没有合适的线路，请先联系管理员',
        BaseCodeService::line_forbidden => '当前线路[:line]已被禁用',
        BaseCodeService::over_today_deadline => '当天下单已超过截止时间',
        BaseCodeService::execution_date_not_in_range => '预约日期已超过可预约时间范围',
        BaseCodeService::over_pickup_order_max => '当前线路已达到最大取件订单数量',
        BaseCodeService::over_pie_order_max => '当前线路已达到最大派件订单数量',
        BaseCodeService::status_wrong_for_deal_exception => '当前状态不能处理异常',
        BaseCodeService::batch_not_fit_tracking_order => '当前指定站点不符合当前运单',
        BaseCodeService::trackng_order_join_batch_failure => '运单加入站点失败!',
        BaseCodeService::batch_remvoe_failure => '站点移除运单失败，请重新操作',
        BaseCodeService::cancel_order_failure => '取消取派失败，请重新操作',
        BaseCodeService::deal_failure => '处理失败，请重新操作',
        BaseCodeService::line_type_required => '独立取派站点,需先选择线路类型',
        BaseCodeService::batch_join_tour_failure => '站点加入取件线路失败，请重新操作',
        BaseCodeService::amount_count_failure => '金额统计失败',
        BaseCodeService::tour_not_end_for_delete => '仍有未完成的任务，无法删除',
        BaseCodeService::order_exist_for_delete_country => '已存在订单，不能删除国家',
        BaseCodeService::SERVER_BUSY => '已存在收件人，不能删除国家',
        BaseCodeService::SERVER_BUSY => '已存在仓库，不能删除国家',
        BaseCodeService::SERVER_BUSY => '已存在线路，不能删除国家',
        BaseCodeService::SERVER_BUSY => '该设备已绑定司机[:driver_name]',
        BaseCodeService::SERVER_BUSY => '正在进行线路任务，请先解绑设备',
        BaseCodeService::SERVER_BUSY => '系统级费用不能删除',
        BaseCodeService::SERVER_BUSY => '商户ID为[:id]已分配',
        BaseCodeService::SERVER_BUSY => '区域[:i]和区域[:j]有重叠',
        BaseCodeService::SERVER_BUSY => '区域[:key]部分区域已存在',
        BaseCodeService::SERVER_BUSY => '当前商户组内还有成员,不能删除',
        BaseCodeService::SERVER_BUSY => '可预约天数必须大于提前下单天数',
        BaseCodeService::SERVER_BUSY => '当前订单不支持再次派送，请联系管理员',
        BaseCodeService::SERVER_BUSY => '当前包裹已生成对应运单',
        BaseCodeService::SERVER_BUSY => '导入订单数量不得超过100个',
        BaseCodeService::SERVER_BUSY => '订单中必须存在一个包裹或一种材料',
        BaseCodeService::SERVER_BUSY => '第三方订单不能修改',
        BaseCodeService::SERVER_BUSY => '订单类型不能修改',
        BaseCodeService::SERVER_BUSY => '第三方订单不允许手动删除',
        BaseCodeService::SERVER_BUSY => '未设置打印模板，请联系管理员设置打印模板',
        BaseCodeService::SERVER_BUSY => '只有已完成的订单才能无效化',
        BaseCodeService::SERVER_BUSY => '当日充值未完结，请次日审核',
        BaseCodeService::SERVER_BUSY => '实际金额不能大于预计金额',
        BaseCodeService::SERVER_BUSY => '该充值已审核,请勿重复审核',
        BaseCodeService::SERVER_BUSY => '异常已处理',
        BaseCodeService::SERVER_BUSY => '当前司机已被分配，请选择其他司机',
        BaseCodeService::SERVER_BUSY => '当前车辆已被分配，请选择其他车辆',
        BaseCodeService::SERVER_BUSY => '司机分配失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '车辆不存在或已被锁定',
        BaseCodeService::SERVER_BUSY => '司机不存在或已被锁定',
        BaseCodeService::SERVER_BUSY => '车辆取消分配失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '取件线路取消锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '站点取消锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '运单取消锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '站点加入取件线路失败，请重新操作！',
        BaseCodeService::SERVER_BUSY => '取件移除运单失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '取件移除站点失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '当前指定取件线路不符合当前站点',
        BaseCodeService::SERVER_BUSY => '只能选择本月之前的月份',
        BaseCodeService::SERVER_BUSY => '运单状态为[:status_name],不能修改派送信息',
        BaseCodeService::SERVER_BUSY => '运单状态为[:status_name],不能操作',
        BaseCodeService::SERVER_BUSY => '所有运单的当前状态不能操作，只允许待分配或已分配状态的运单操作',
        BaseCodeService::SERVER_BUSY => '运单[:order_no]的当前状态不能操作,只允许待分配或已分配状态的运单操作',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不能操作',
        BaseCodeService::SERVER_BUSY => '当前线路已更换，请刷新',
        BaseCodeService::SERVER_BUSY => '当前取件线路正在派送中，取件运单加单不能包含材料',
        BaseCodeService::SERVER_BUSY => '取件数量超过线路取件运单最大值',
        BaseCodeService::SERVER_BUSY => '运单[:order_no]的不是待分配或已分配状态，不能操作',
        BaseCodeService::SERVER_BUSY => '派件运单不允许加单',
        BaseCodeService::SERVER_BUSY => '数据量过大无法导出，运单数不得超过200',
        BaseCodeService::SERVER_BUSY => '仓库不存在',
        BaseCodeService::SERVER_BUSY => '当前运单正在[:status_name]',
        BaseCodeService::SERVER_BUSY => '当前运价没有公里计费列表',
        BaseCodeService::SERVER_BUSY => '当前公里不在该运价范围内',
        BaseCodeService::SERVER_BUSY => '当前运价没有重量计费列表',
        BaseCodeService::SERVER_BUSY => '当前重量不在该运价范围内',
        BaseCodeService::SERVER_BUSY => '当前运价没有特殊时段计费列表',
        BaseCodeService::SERVER_BUSY => '当前时间不在该运价范围内',
        BaseCodeService::SERVER_BUSY => '公里区间有重叠',
        BaseCodeService::SERVER_BUSY => '重量区间有重叠',
        BaseCodeService::SERVER_BUSY => '时间段有重叠',
        BaseCodeService::SERVER_BUSY => '存在当前仓库的线路,请先删除线路',
        BaseCodeService::SERVER_BUSY => '当前商户没有API对接权限',
        BaseCodeService::SERVER_BUSY => '当前状态是[:status_name]，不能操作',
        BaseCodeService::SERVER_BUSY => '此设备已绑定司机[:driver_name]，请先解绑',
        BaseCodeService::SERVER_BUSY => '没有合适日期',
        BaseCodeService::SERVER_BUSY => '该商户未开启充值业务',
        BaseCodeService::SERVER_BUSY => '线路未出库，无法进行现金充值',
        BaseCodeService::SERVER_BUSY => '该包裹当前状态不允许上报异常',
        BaseCodeService::SERVER_BUSY => '线路[:line]存在取派任务线路[:tour_no]，不能操作',
        BaseCodeService::SERVER_BUSY => '上报异常失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '该包裹当前状态不允许上报异常',
        BaseCodeService::SERVER_BUSY => '上报异常失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '异常已处理或被拒绝',
        BaseCodeService::SERVER_BUSY => '包裹已入库，当前线路[:line_name]，派送日期[:execution_date]',
        BaseCodeService::SERVER_BUSY => '当前包裹不能生成对应派件运单，请进行异常入库处理',
        BaseCodeService::SERVER_BUSY => '当前包裹状态为[:status_name],不能分拣入库',
        BaseCodeService::SERVER_BUSY => '当前包裹不能生成对应派件运单或已生成派件运单',
        BaseCodeService::SERVER_BUSY => '当前包裹已入库',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许装货',
        BaseCodeService::SERVER_BUSY => '同时只能进行一个任务，请先完成其他取派中的任务',
        BaseCodeService::SERVER_BUSY => '取件线路待分配车辆,请先分配车辆',
        BaseCodeService::SERVER_BUSY => '取件线路锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '站点锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '运单锁定失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许取消锁定',
        BaseCodeService::SERVER_BUSY => '取件线路不存在或当前状态不允许分配车辆',
        BaseCodeService::SERVER_BUSY => '出库失败',
        BaseCodeService::SERVER_BUSY => '状态错误',
        BaseCodeService::SERVER_BUSY => '出库里程数小于该车上次入库里程数，请重新填写',
        BaseCodeService::SERVER_BUSY => '车辆里程记录失败，请重试',
        BaseCodeService::SERVER_BUSY => '实际出库失败',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许出库',
        BaseCodeService::SERVER_BUSY => '取件线路待分配车辆,请先分配车辆',
        BaseCodeService::SERVER_BUSY => '订单已取消或已删除,不能出库,请先剔除',
        BaseCodeService::SERVER_BUSY => '材料有重复,请先合并',
        BaseCodeService::SERVER_BUSY => '材料种类不正确',
        BaseCodeService::SERVER_BUSY => '当前取件线路的材料数量不正确',
        BaseCodeService::SERVER_BUSY => '订单为[:order_no],运单为[:tracking_order_no]不可出库',
        BaseCodeService::SERVER_BUSY => '当前站点为[:status],无法进行此操作',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许上报异常',
        BaseCodeService::SERVER_BUSY => '请先确认出库',
        BaseCodeService::SERVER_BUSY => '站点当前状态不能上报异常',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许站点取消取派',
        BaseCodeService::SERVER_BUSY => '站点当前状态不能取消取派',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许站点签收',
        BaseCodeService::SERVER_BUSY => '站点当前状态不能签收',
        BaseCodeService::SERVER_BUSY => '顺带包裹费用不为0，不能选择无需支付',
        BaseCodeService::SERVER_BUSY => '费用不为0，不能选择无需支付',
        BaseCodeService::SERVER_BUSY => '此站点已被跳过，请先恢复站点',
        BaseCodeService::SERVER_BUSY => '签收失败',
        BaseCodeService::SERVER_BUSY => '总计代收货款不正确',
        BaseCodeService::SERVER_BUSY => '总计运费不正确',
        BaseCodeService::SERVER_BUSY => '材料处理失败',
        BaseCodeService::SERVER_BUSY => '商户不存在，无法顺带包裹',
        BaseCodeService::SERVER_BUSY => '商户未开启顺带包裹服务',
        BaseCodeService::SERVER_BUSY => '当前站点不属于当前取件线路',
        BaseCodeService::SERVER_BUSY => '顺带包裹格式不正确',
        BaseCodeService::SERVER_BUSY => '未从仓库取材料[:code]',
        BaseCodeService::SERVER_BUSY => '材料数量不得超过预计材料数量',
        BaseCodeService::SERVER_BUSY => '材料[:code]只剩[:count]个，请重新选择材料数量',
        BaseCodeService::SERVER_BUSY => '存在需要身份验证的包裹，请填写身份验证信息',
        BaseCodeService::SERVER_BUSY => '取件线路当前状态不允许回仓库',
        BaseCodeService::SERVER_BUSY => '当前取件线路还有未完成站点，请先处理',
        BaseCodeService::SERVER_BUSY => '出库里程数过大，请重新填写',
        BaseCodeService::SERVER_BUSY => '司机入库失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '延迟失败',
        BaseCodeService::SERVER_BUSY => '延迟处理失败',
        BaseCodeService::SERVER_BUSY => '延迟记录失败',
        BaseCodeService::SERVER_BUSY => '当前预约必须提前[:count_days]天预约',
        BaseCodeService::SERVER_BUSY => '该预约日期是放假日期，不可预约',
        BaseCodeService::SERVER_BUSY => '当前指定站点不符合当前订单',
        BaseCodeService::SERVER_BUSY => '订单加入站点失败!',
        BaseCodeService::SERVER_BUSY => '当前订单状态是[:status_name]，不能操作',
        BaseCodeService::SERVER_BUSY => '该状态无法进行此操作',
        BaseCodeService::SERVER_BUSY => '订单正在[:status_name],不能修改日期',
        BaseCodeService::SERVER_BUSY => '当前编号规则未定义',
        BaseCodeService::SERVER_BUSY => '前缀与其他用户重复，请更改前缀',
        BaseCodeService::SERVER_BUSY => '订单单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '单号生成失败，请重新操作',
        BaseCodeService::SERVER_BUSY => '站点单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '单号规则不存在，请先添加单号规则',
        BaseCodeService::SERVER_BUSY => '取件线路单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '异常站点单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '充值单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '运单单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '入库异常单号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '编号规则不存在或已被禁用，请先联系后台管理员',
        BaseCodeService::SERVER_BUSY => '该包裹非本系统包裹，无法顺带',
        BaseCodeService::package_operation_failure => '包裹处理失败，请重新操作',
        BaseCodeService::tracking_order_operation_failure => '运单处理失败，请重新操作',
        BaseCodeService::tracking_order_package_operation_failure => '运单包裹处理失败，请重新操作',
        BaseCodeService::order_operation_failure => '订单处理失败，请重新操作',
        BaseCodeService::batch_operation_failure => '站点处理失败，请重新操作',
        BaseCodeService::tour_operation_failure => '取件线路处理失败，请重新操作',
        BaseCodeService::driver_assign_failure => '司机分配失败，请重新操作',
        BaseCodeService::driver_cancel_assign_failure => '司机取消分配失败，请重新操作',
        BaseCodeService::driver_assigned => '当前车辆已被分配，请选择其他车辆',
        BaseCodeService::remove_failure => '移除失败,请重新操作',
        BaseCodeService::SERVER_BUSY => '充值信息丢失，请重新充值',
        BaseCodeService::SERVER_BUSY => '充值已完成，请勿重复充值',
        BaseCodeService::price_set_failure => '批量设置运价失败',
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
