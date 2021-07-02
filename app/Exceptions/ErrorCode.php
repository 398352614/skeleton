<?php


return array(


    //1-程序错误
    //2-权限认证
    //3-第三方错误1-地图2-经纬度3-货主
    //4-表单验证
    //5-业务逻辑1状态错误2-复杂错误
    //6-数据库错误1新增失败2修改失败3删除失败4操作失败

    //200成功
    //500失败

    //1程序错误0-语法错误1-基础功能2-服务器错误
    "类不存在" => 1001,
    "方法不存在" => 1002,
    "方法未定义" => 1003,

    "字母规则不正确" => 1101,
    "没有对应目录" => 1102,

    "系统繁忙，请稍后重试" => 1101,


    //2权限认证0-底层1-注册2-登录3-登出4-权限5-权限组
    "用户认证失败" => 2001,
    "验证码发送失败" => 2002,
    "路由已存在" => 2003,
    "验证码错误" => "Verification code error",
    "原密码不正确" => "The original password is incorrect",

    "账号已注册，请直接登录" => 2101,
    "该名称已注册，请直接登录" => 2102,
    "该邮箱已注册，请直接登录" => 2103,

    "暂时无法登录，请联系管理员！" => 2201,
    "用户名或密码错误！" => "User name or password incorrect!",

    "当前货主没有API对接权限" => 2401,
    "当前用户没有该权限" => 2402,
    "权限不存在" => 2403,
    "存在超级管理员，不能操作" => 2405,
    "当前用户没有该权限，请按F5刷新页面" => 2406,
    "当前权限不存在" => 2407,

    "权限组已存在" => 2501,
    "权限组不存在" => 2502,
    "超级管理员只能在管理员组" => 2503,
    "管理员组权限不允许操作" => 2504,

    //3第三方接口0-地图1-经纬度2-国家3-货主
    "不存在的动作" => 3001,
    "可能由于网络原因，无法估算距离" => 3002,
    "优化失败" => 3003,
    "线路更新失败" => 3004,
    "线路优化失败" => 3005,

    "由于网络问题，无法根据地址信息获取真实位置，请稍后再尝试" => 3101,

    "由于网络问题，无法获取国家信息，请稍后尝试" => 3201,
    "系统无相关国家信息" => 3202,

    "拉取第三方用户信息失败" => 3301,
    "充值信息丢失，请重新充值" => 3302,
    "充值信息已失效，请重新充值" => 3303,
    "充值已完成，请勿重复充值" => 3304,
    "验证失败" => 3305,
    "用户信息不正确，请重新充值" => 3306,
    "实际金额不能大于充值金额" => 3307,
    "充值请求已过期，请重新充值" => 3308,
    "发送失败" => 3309,
    "缺少参数key,sign,timestamp或data" => 3310,

    //4表单验证0-普通验证1-数据存在2-数据不存在3-非法参数4-外键约束
    "邮编范围:post_range_1与:post_range_2存在重叠,无法添加" => 4101,
    "材料代码-外部标识[:code]有重复！不能添加订单" => 4102,
    "材料有重复，请先合并" => 4103,
    "快递单号1[:express_no]有重复！不能添加订单" => 4104,
    "快递单号2[:express_no]有重复！不能添加订单" => 4105,
    "快递单号1[:express_no]已存在在快递单号2中" => 4106,
    "公里区间有重叠" => 4107,
    "重量区间有重叠" => 4108,
    "时间段有重叠" => 4109,
    "前缀与其他用户重复，请更改前缀" => "Prefix duplicated with other user, please change it",
    "区域[:i]和区域[:j]有重叠" => "area [:i] and area [:j] duplicated",

    "邮编[:post_code_start]到[:post_code_end]已存在" => "Postcode [:cost_code_start]-[:cost_code_end] already exists",
    "名称已存在" => "Name already exists",
    "外部订单号已存在" => "Cargo number already exists",
    "已添加了国家，不能再次添加国家" => "Country already added, cannot add again",
    "区域[:key]部分区域已存在" => "area [:key] some areas already exist",
    "地址已存在，不能重复添加" => "Address already exists, don’t add it again",

    "已存在订单，不能删除国家" => "Country couldn’t be deleted for the existing order",
    "已存在收件人，不能删除国家" => "Country couldn’t be deleted for the existing receiver",
    "已存在网点，不能删除国家" => "Country couldn’t be deleted for the existing outstation",
    "已存在线路，不能删除国家" => "Country couldn’t be deleted for the existing route",

    "当前包裹已生成对应运单" => "Parcel has generated the corresponding waybill",
    "异常已处理，请勿重复处理" => "Exception processed",
    "包裹号和袋号同时存在，请进行选择" => "Parcel and package No. exist at the same time",

    //数据不存在
    "没找到相关进行中的线路" => "No relevant route in progress found",
    "未设置打印模板，请联系管理员设置打印模板" => "Print template not set, please contact an administrator",

    "数据不存在" => "Data doesn’t exist",
    "货主不存在" => "Cargo owner doesn’t exist",
    "司机不存在" => "Driver doesn’t exist or belong to the current company",
    "国家不存在" => "Country doesn’t exist",
    "网点不存在" => "outstation doesn’t exist",
    "线路任务不存在" => "Pick-up route doesn’t exist",
    "车辆不存在" => "Vehicle doesn’t exist or has been locked",
    "费用不存在" => "Fee does not exist",
    "地址模板不存在" => "Address template doesn’t exist",
    "站点不存在" => "Site doesn’t exist",
    "运价不存在" => "Freight rate doesn’t exist or is disabled",
    "货主组不存在" => "Cargo owner group doesn’t exist",
    "线路不存在" => "Route doesn’t exist",
    "订单不存在" => "Order doesn’t exist",
    "公司不存在" => "Enterprise doesn’t exist",
    "袋号不存在" => "Package No. does not exist",
    "包裹不存在" => "Parcel does not exist",
    "记录不存在" => "Record doesn’t exist",
    "包裹号或袋号不存在" => "Parcel or package No. doesn’t exist",
    "运单不存在" => "Waybill does not exist",
    "所属品牌不存在" => "Vehicle brand doesn’t exist",

    "订单[:order_no]不存在" => "Order [:order_no] doesn't exist",
    "ID为[:id]的货主不存在" => "Cargo owner [:id] doesn’t exist",

    "货主不存在，请重新选择货主" => "Cargo owner doesn’t exist, please try again",
    "客户不存在，请检查客户编码是否正确" => "Customer doesn’t exist, please check customer’s postcode",
    "货主不存在，无法顺带包裹" => "Cargo owner doesn’t exist, parcel couldn’t be picked up on the way",

    "未查找到下一个目的地" => "Next destination not found",
    "当前编号规则未定义" => "Current number rule is undefined",
    "当前包裹不存在系统中" => "Current parcel doesn’t exist in system",
    "暂无物流信息" => "No logistics info now",
    "暂无预计运价，运价以实际为准" => "No estimated freight rate is available, subject to actual freight rate",
    "暂无车辆信息" => "no vehicle info",

    "订单单号规则不存在或已被禁用，请先联系后台管理员" => "Order No. rule doesn’t exist or is disabled, please contact an administrator",
    "站点单号规则不存在或已被禁用，请先联系后台管理员" => "Site order No. rule doesn’t exist or is disabled, please contact an administrator",
    "线路任务单号规则不存在或已被禁用，请先联系后台管理员" => "Pick-up route order rule doesn’t exist or is disabled, please contact an administrator",
    "异常站点单号规则不存在或已被禁用，请先联系后台管理员" => "The order No. rule for exceptional sites doesn’t exist or is disabled, please contact an administrator",
    "充值单号规则不存在或已被禁用，请先联系后台管理员" => "Recharge order No. rule doesn’t exist or is disabled, please contact an administrator",
    "运单单号规则不存在或已被禁用，请先联系后台管理员" => "Waybill No. rule doesn’t exist or is disabled, please contact an administrator",
    "入库异常单号规则不存在或已被禁用，请先联系后台管理员" => "Exception warehouse order No. rule doesn’t exist or is disabled, please contact an administrator",
    "车次号规则不存在或已被禁用，请先联系后台管理员" => "Vehicle schedule rule doesn’t exist or is disabled, please contact an administrator",
    "车辆维护流水号规则不存在或已被禁用，请先联系后台管理员" => "Vehicle maintenance serial number rule doesn’t exist or is disabled, please contact an administrator",
    "事故处理编号规则不存在或已被禁用，请先联系后台管理员" => "Accident response number rule doesn’t exist or is disabled, please contact an administrator",
    "转运单号规则不存在或已被禁用，请先联系后台管理员" => "Transhipment order No. rule doesn’t exist or is disabled, please contact an administrator",
    "袋号规则不存在或已被禁用，请先联系后台管理员" => "Parcel No. rule doesn’t exist or is disabled, please contact an administrator",

    "当前运价没有配置公里计费规则" => "Current freight rate has no kilometer billing list",
    "当前运价没有配置重量计费规则" => "Current freight rate has no weight billing list",
    "当前运价没有配置特殊时段计费规则" => "Current freight rate has no special time slot billing list",

    //验证
    "单号规则总长度不得超过[:length]位" => "Order No. rule shall not exceed [:length]",
    "订单中必须存在一个包裹或一种材料" => "The order must contain a parcel or material",
    "邮编或门牌号码不正确，请仔细检查输入或联系客服" => "The postcode or house number is incorrect, please check the data you typed or contact customer service",
    "请选择开始时间" => "Please select starting time",
    "请选择结束时间" => "Please select ending time",
    "表格格式不正确，请使用正确的模板导入" => "Form format error, please import a correct template",
    "订单号是必须的" => "order No. is mandatory",
    "线路的站点数量不正确" => "Site quantity on the route is incorrect",
    "当结算方式为到付时,:attribute字段必填" => "In case of COD, ttribute field is mandatory",
    "费用不为0，不能选择无需支付" => "Fee is not 0, cannot select no payment required",
    "当前公里不在该运价范围内" => "Kilometer is not within the freight rate range",
    "当前重量不在该运价范围内" => "Weight is not within the freight rate range",
    "邮编范围不能为空" => "Postcode range cannot be empty",
    "材料种类不正确" => "material type incorrect",
    "线路编号是必须的" => "Route No. is mandatory",
    "顺带包裹费用不为0，不能选择无需支付" => "On-the-way parcel fee should not be 0, cannot select no payment required",
    "只能选择本月之前的月份" => "Only select a month that is earlier than this month",
    "订单类型不能修改" => "Order type couldn’t be modified",
    "派件运单不允许加单" => "Delivery waybills cannot add waybills",
    "没有合适日期" => "No suitable date",
    "所选多个订单电话或取件日期不一致，无法统一修改" => "Selected multiple order telephone or pick-up date are not identical, cannot change in batches",
    "该包裹非本系统包裹，无法顺带" => "This parcel is not in the system, on-the-way delivery is not supported",
    "有效日期不得小于取派日期" => "Valid date shall not be earlier than the pick-up/delivery date",
    "固定费用/距离费用/重量费用至少配置一项" => "Select at least one out of the fixed fee/distance fee/weight fee",
    "该备品没有库存" => "This spare has no stock",
    "备品库存不足" => "Spare out of stock",
    "一个区域至少应该有三个顶点" => "A region should have at least three vertices",
    "前缀长度不得超过总长度" => "The prefix length must not exceed the total length",
    "查询字段至少一个不为空" => "At least one of the query fields cannot be empty",

    //参数非法
    "当前货主已创建API对接信息" => "Current cargo owner has created an API",
    "材料数量不得超过预计材料数量" => "The quantity of materials shall not exceed the estimated quantity",
    "材料[:code]只剩[:count]个，请重新选择材料数量" => "Only [:count] [:code] materials left, please re-select material quantity",
    "当前线路任务的材料数量不正确" => "The material quantity of the current pickup route is incorrect",
    "地址数据不正确，无法拉取可选日期" => "The address data is incorrect, unable to select the optional date",
    "未传入线路任务编号" => "Pick-up route number not updated",
    "日期格式不正确" => "date format error",
    "总计代收货款不正确" => "total collected payment error",
    "总计运费不正确" => "total freight cost error",
    "顺带包裹格式不正确" => "on-the-way parcel format incorrect",
    "公里区间不连贯" => "Kilometer interval discontinued",
    "公里区间未涵盖所有范围" => "Kilometer interval doesn’t cover all ranges",
    "重量区间不连贯" => "Weight interval discontinued",
    "重量区间未涵盖所有范围" => "Weight interval doesn’t cover all ranges",

    //外键约束
    "线路已删除，请联系管理员" => "Route deleted, please contact an administrator",
    "请先删除该机构下的所有成员" => "Please delete all members of the organization first",
    "无法删除根组织" => "Root organization cannot be deleted",
    "组织机构最高为3级" => "Organization can reach a maximum of level 3",
    "该节点存在子机构，请先删除子机构" => "This node has a subsidiary, please delete the subsidiary first",
    "当前货主组内还有成员,不能删除" => "Current cargo owner group has members, deletion is not supported",
    "线路[:line]存在取派任务线路[:tour_no]，不能操作" => "Route [:line] has pick-up/delivery tasks [:tour_no], operation disabled",
    "请先移除该权限组员工" => "Please remove this permission group employee first",
    "请先删除下级网点的该线路" => "Please delete this route of the subordinate outstation",
    "无法删除根网点" => "Cannot delete root outstation",
    "请先删除子网点" => "Please delete sub-outstation first",
    "请先删除该网点下的所有员工" => "Please delete all employees under this outstation",
    "请先删除该网点下的所有司机" => "Please delete all drivers under this outstation",
    "请先删除该网点下的所有货主" => "Please delete all cargo owners under this outstation",
    "网点层级最高为3级" => "Maximum outstation level is 3",
    "包裹不属于该袋号" => "Parcel does not belong to this package No.",
    "包裹或袋号不属于该车次" => "Parcel or package No. is not in this vehicle schedule",
    "订单为[:order_no],运单为[racking_order_no]已取消或已删除,不能出库,请先剔除" => "Order [:order_no], waybill [racking_order_no] canceled or deleted, warehouse-out not supported, please remove it first",

    //业务规则
    "同时只能进行一个任务，请先完成其他取派中的任务" => "Only one task can be performed at the same time. Please complete other pickup/delivery tasks first",
    "地址模板二无法进行批量导入，请联系管理员" => "Address template 2 cannot be imported in batches, please contact the administrator",

    "当前没有合适的线路，请先联系管理员" => "No suitable route, please contact an administrator",
    "当天下单已超过截止时间" => "Order placed exceeds the deadline on the day",
    "预约日期已超过可预约时间范围" => "Appointment time exceeds the range",
    "当前线路已达到最大取件订单数量" => "Current route reached the maximum order quantity",
    "当前线路已达到最大派件订单数量" => "Current route reached the maximum order quantity",
    "该预约日期是放假日期，不可预约" => "This appointment date is on a holiday, cannot make appointment",

    "当前指定站点不符合当前订单" => "The current specified site does not match the current order",
    "当前指定线路任务不符合当前站点" => "Current designated pick-up route doesn’t comply with current site",
    "当前指定站点不符合当前运单" => "Designed site doesn’t comply with the current waybill",
    "当前线路的其他货主还未到达取件最小订单量，该货主无法添加订单" => "Other cargo owners on the current route failed to reach the minimum pick-up order quantity, so this cargo owner couldn’t add an order",
    "当前线路的其他货主还未到达派件最小订单量，该货主无法添加订单" => "Other cargo owners on the current route failed to reach the minimum delivery order quantity, so this cargo owner couldn’t add an order",
    "订单为[:order_no]，运单为[:tracking_order_no]已取消或已删除，不能出库，请先剔除" => 5006,

    "第三方订单不能修改" => "Third-party order couldn’t be modified",

    "可预约天数必须大于提前下单天数" => "Booking days must be greater than placing order in advance days",
    "当前预约必须提前[:count_days]天预约" => "Current appointment shall be made [:count_days] days in advance",

    "线路任务待分配车辆，请先分配车辆" => "Pick-up route awaiting vehicle assignment, please assign one",

    "未从网点取材料[:code]" => "No material picked up from outstation [:code]",
    "存在需要身份验证的包裹，请填写身份验证信息" => "Parcel with ID verification required, please verify ID first",
    "系统级费用不能删除" => "Cannot delete system fee",
    "第三方订单不允许手动删除" => "Cannot manually delete third-party orders",
    "请按优化的站点顺序进行派送，或手动跳过之前的站点" => "Please deliver by optimized site sequence, or manually skip previous sites",
    "独立取派站点，需先选择线路类型" => "Independent pick-up/delivery site, must select route type",
    "当日充值未完结，请次日审核" => "Recharge on the day is not completed, please review it on the next day",
    "当前线路任务正在派送中，取件运单加单不能包含材料" => "Current pick-up route is under delivery, add pick-up waybill cannot contain materials",
    "数据量过大无法导出，运单数不得超过200" => "Overloaded data couldn’t be exported, waybills cannot exceed 200",

    "当前包裹不能生成对应派件运单或已生成派件运单" => "Current parcel couldn’t generate the corresponding delivery waybill or has generated a delivery waybill",
    "当前包裹不能生成对应派件运单，请进行异常入库处理" => "Current parcel couldn’t generate a delivery waybill. Please process the exception warehouse-in",

    "各货主组取件最小订单量之和不得超过线路取件最大订单量" => "The sum of the minimum pick-up order quantity for various cargo owner groups shall not exceed the maximum pick-up order quantity on this route",
    "各货主组派件最小订单量之和不得超过线路派件最大订单量" => "The sum of the minimum delivery order quantity for various cargo owner groups shall not exceed the maximum delivery order quantity on this route",
    "有未取材料，请刷新页面" => "Materials not picked up, please refresh the page",
    "您选择的邮编范围跨越多个国家，暂不支持多国家线路" => "The postcode range you selected covers different countries. Multi-country routes are not supported",
    "货主所属网点不承接取件订单" => "Cargo owner’s outstation doesn’t accept pick-up orders",
    "货主所属网点不承接派件订单" => "Cargo owner’s outstation doesn’t accept delivery orders",
    "货主所属网点不承接取派订单" => "Cargo owner’s outstation doesn’t accept pick-up/delivery orders",
    "该发件人地址所属区域，网点不承接取件订单" => "Sender’s area and outstation do not accept pick-up orders",
    "该收件人地址所属区域，网点不承接派件订单" => "Receiver’s area and outstation do not accept delivery orders",
    "包裹与袋号的下一站不一致" => "Parcel and package No. have a different next-stop",
    "袋号与下一站不一致" => "Package No. and next-stop is different",
    "该包裹不应在此网点入库" => "This parcel is not warehouse-in at this outstation",


    //状态错误
    "状态错误" => "Status error",
    "车辆已被锁定" => "Vehicle doesn’t exist or has been locked",
    "司机已被锁定" => "This driver doesn’t exist or is locked",
    "当前线路任务已锁定，请稍后操作" => "Current pick-up route has been locked, please try again later",

    "运价已被禁用" => "Freight rate doesn’t exist or is disabled",
    "当前线路[:line]已被禁用" => "Current route [:line] disabled",

    "线路任务当前状态不允许分配车辆" => "Pick-up route doesn’t exist or vehicle assignment is not allowed in the current status",
    "线路任务当前状态不允许装货" => "Loading is not allowed for the pick-up route in the current status",
    "线路任务当前状态不允许取消锁定" => "Lock cancellation is not allowed for pick-up route in the current status",
    "线路任务当前状态不允许出库" => "Warehouse-out is not allowed for the pick-up route in the current status",
    "线路任务当前状态不允许上报异常" => "Exception report is not allowed for the pick-up route in the current status",
    "线路任务当前状态不允许站点取消取派" => "Site is not allowed to cancel the pick-up for pick-up route in the current status",
    "线路任务当前状态不允许站点签收" => "Acceptance signature by the site is not allowed for the pick-up route in the current status",
    "线路任务当前状态不允许回网点" => "Return to outstation is not allowed for the pick-up route in the current status",
    "当前线路任务还有未完成站点，请先处理" => "The current pick-up route has unfinished sites, please complete first",
    "当前线路任务正在派送中，取件订单加单不能包含材料" => "Current pick-up route is under delivery, and the order addition of the pick-up order cannot contain materials",

    "站点当前状态不能取消取派" => "Pick-up cancellation is not allowed for the site in the current status",
    "站点当前状态不能签收" => "Acceptance signature not allowed for the site in the current status",
    "站点当前状态不能上报异常" => "Site doesn’t support exception report in its current status",
    "当前站点不属于当前线路任务" => "Current site is not on current pick-up route",
    "当前站点为[:status],无法进行此操作" => "No operation for current site in [:status] status",


    "当前状态不能处理异常" => "Exception cannot be processed in the current status",

    "订单已取消或已删除，不能出库，请先剔除" => "Order canceled or deleted, warehouse-out not supported, please remove it first",
    "订单为[:order_no],运单为[:tracking_order_no]不可出库" => "Order [:order_no], waybill is [:tracking_order_no], warehouse-out not supported",
    "订单正在[:status_name],不能修改日期" => "Order is [:status_name], cannot modify the date",

    "货主ID为[:id]已分配" => "Cargo owner ID [:id] assigned",
    "当前司机已被分配，请选择其他司机" => "This driver has already been assigned, please select another one",
    "当前车辆已被分配，请选择其他车辆" => "Current vehicle has already been assigned, please select another one",

    "货主未开启顺带包裹服务" => "Cargo owner disabled on-the-way parcel service",
    "该货主未开启充值业务" => "This cargo owner disabled recharge",
    "当前订单不支持再次派送，请联系管理员" => "Current order doesn’t support delivery, please contact an administrator",
    "当前订单不支持再次派送，请刷新后再操作" => "Current order doesn’t support delivery, please refresh and try it again",
    "订单处于中转过程，无法再次生成运单" => "Order in transition, cannot generate waybill again",

    "当前正在使用该线路，不能删除" => "This route is in use and cannot be operated",
    "该状态无法进行此操作" => "Cannot do this operation in this status",
    "请先确认出库" => "Please confirm warehouse-out first",
    "线路任务当前状态不能操作" => "Pick-up route cannot operate in the current status",
    "仍有未完成的任务，无法删除" => "Still has an unfinished task, failed to delete",
    "此站点已被跳过，请先恢复站点" => "This site was skipped, please restore the site first",
    "当前订单状态是[:status_name]，不能操作" => "Current order status is [:status_name], cannot change",

    "该充值已审核,请勿重复审核" => "Recharge reviewed, please don’t review again",
    "线路任务已完成，不能优化" => "Completed pick-up route couldn’t be optimized",
    "该设备已绑定司机[:driver_name]" => "This device is bound with driver [:driver_name]",
    "正在进行线路任务，请先解绑设备" => "Route is in use, please unbind the device first",
    "线路未出库，无法进行现金充值" => "Route is not warehouse-out, cash recharge disabled",
    "只有已完成的订单才能无效化" => "Only completed orders can be invalid",

    "运单状态为[:status_name],不能修改派送信息" => "Waybill status is [:status_name], delivery info couldn’t be modified",
    "运单状态为[:status_name],不能操作" => "Waybill status is [:status_name], operation disabled",
    "运单[:order_no]的当前状态不能操作,只允许待分配或已分配状态的运单操作" => "Waybill [:order_no] couldn’t operate in the current status, except for waybills to be assigned or already assigned",
    "运单[:order_no]的不是待分配或已分配状态，不能操作" => "Waybill [:order_no] not waiting to be assigned or already assigned, cannot do this",
    "运单的当前状态不能操作，只允许待分配或已分配状态的运单操作" => "All waybills couldn’t operate in the current status, except for waybills to be assigned or already assigned",
    "运单[:tracking_order_no]不可出库" => "Waybill [:tracking_order_no] warehouse-out not supported",
    "当前运单正在[:status_name]" => "Current waybill is [:status_name]",

    "该线路任务不在取派中，无法进行追踪" => "This pick-up route is not being delivered and cannot be tracked",
    "运输未开始，暂无物流信息" => "Transportation has not started, no logistics info",
    "当前状态不能处理异常或异常已处理" => "Current status couldn’t process exception, or exception processed",
    "订单[:order_no]未生成运单，无法打印面单" => "Order [:order_no] doesn’t generate a waybill, the parcel sheet couldn’t be printed",
    "该记录已经作废" => "This record has been voided",
    "网点未配置仓配一体，无法选择该网点" => "Outstation doesn’t set W&D integration and is not available for selection",
    "只有未发车的袋号才能删除" => "Only the package No. of available vehicles can be deleted",

    "当前包裹状态为[:status_name],不能分拣入库" => "Current parcel status is [:status_name], pick and place in warehouse not supported",
    "该包裹当前状态不允许上报异常" => "Exception report not supported for parcel in current status",
    "包裹已入库，当前线路[:route_name]，派送日期[:execution_date]" => "Parcel put in warehouse, current route [:route_name], delivery date [:execution_date]",
    "包裹阶段错误" => "Parcel stage error",
    "包裹状态错误" => "Parcel status error",
    "包裹已装袋，请勿重复扫描" => "Parcel is packed, don’t scan it again",
    "包裹已装车，不允许装袋" => "Parcel is loaded, packing is not supported",
    "只有未发车的车次才能删除" => "Only the schedule of available vehicles can be deleted",

    //新增失败


    "最小订单量新增失败" => "Failed to add minimum order amount",
    "订单费用新增失败" => "Failed to add order fee!",
    "转运单新增失败" => "Failed to generate transhipment waybill",
    "订单新增失败" => "Failed to add order",
    "国家新增失败" => "Failed to add country",
    "员工新增失败" => "Failed to create employee",
    "订单包裹新增失败！" => "Failed to add order parcel!",
    "订单材料新增失败！" => "Failed to add order material!",
    "备忘录新增失败" => "Failed to add memo",
    "线路新增失败" => "Failed to add route",
    "材料新增失败" => "Failed to add material",
    "司机新增失败" => "Failed to add driver",
    "线路范围新增失败" => "Failed to add route range",


    "新增失败，请重新操作" => "Failed to add, please try again",

    "初始化运价失败" => "Failed to initialize freight rate",
    "初始化货主组失败" => "Failed to initialize cargo owner",
    "初始化货主失败" => "Failed to initialize cargo owner",
    "初始化货主API失败" => "Failed to initialize cargo owner API",
    "初始化费用失败" => "Failed to initialize fee",
    "初始化权限组失败" => "Failed to initialize permission group",

    //删除失败
    "无法删除自己" => "Can’t delete yourself",

    "车辆删除失败" => "Failed to delete vehicle",
    "员工删除失败" => "Failed to delete employee",
    "备忘录删除失败" => "Failed to delete memo",
    "线路删除失败" => "Failed to delete route",
    "司机删除失败" => "Failed to delete driver",
    "线路范围删除失败" => "Failed to delete route range",

    "网点删除失败，请重新操作" => "Failed to delete outstation, please try again",
    "删除失败,订单[:order_no]删除失败,原因[:exception_info]" => "Failed to delete, failed to delete order [:order_no], reason [:exception_info]",
    "批量删除失败,订单[:order_no]删除失败,原因-[:exception_info]" => "Failed to delete in batches, order [:order_no] deletion failed, reason - [:exception_info]",

    "删除失败，请重新操作" => "Failed to delete, please try again",
    "删除失败" => "deletion failed",
    "移除失败，请重新操作" => "Failed to remove, please try again",


    //修改失败
    "修改失败" => "modification failed",
    "修改车辆失败" => "Failed to modify vehicle",
    "修改失败，请重新操作" => "Failed to modify, please try again",
    "修改员工失败" => "Failed to modify employee",
    "线路修改失败" => "Failed to modify route",
    "网点修改失败，请重新操作" => "Failed to modify outstation, please try again",
    "运单修改失败" => "Failed to modify waybill",
    "员工密码修改失败" => "Failed to modify employee password",

    "公司注册失败" => "Enterprise registration failed",
    "单号生成失败，请重新操作" => "Failed to generate order No., please try again",
    "异常上报失败，请重新操作" => "Exception report failed, please try again",
    "异常处理失败，请重新操作" => "Processing failed, please try again",
    "充值失败" => "Failed to recharge",
    "采集位置失败" => "Failed to get location",

    "文档上传失败，请重新操作" => "Failed to upload file, please try again",
    "文件上传失败，请重新操作" => "Failed to upload file, please try again",
    "表格导出失败，请重新操作" => "Failed to export form, please try again",
    "图片获取失败，请重新操作" => "Failed to get picture, please try again",
    "图片上传失败，请重新操作" => "Failed to upload picture, please try again",

    "司机分配失败，请重新操作" => "Failed to assign driver, please try again",
    "司机取消分配失败，请重新操作" => "Failed to cancel driver assignment, please try again",

    "备注失败，请重新操作" => "Notes failed, please try again",

    "车辆分配失败，请重新操作" => "Failed to assign vehicle, please try again",

    "更新到达时间失败，请重新操作" => "Failed to update arrival time, please try again",

    "站点加入线路任务失败，请重新操作" => "The site failed to add pick-up route, please try again!",

    "订单加入站点失败" => "Order failed to add to site",
    "取件移除站点失败，请重新操作" => "Failed to remove pick-up site, please try again",
    "备忘录修改失败" => "Failed to modify memo",
    "更新线路信息失败，请稍后重试" => "Failed to update route info, please try again",
    "更新线路失败，请稍后重试" => "Failed to update route, please try again",
    "金额统计失败" => "Failed to count amount",
    "更新线路失败" => "Failed to update route",
    "订单加入站点失败!" => "Order failed to add site!",
    "材料处理失败" => "failed to process material",
    "批量设置运价失败" => "Failed to set freight rate in batches",
    "线路范围修改失败" => "Failed to modify route range",
    "纳入当日充值统计失败" => "Failed to include recharging statistics on the day",
    "充值统计失败" => "Recharge statistics failed",
    "车辆里程记录失败，请重试" => "Failed to record vehicle mileage, please try again",
    "操作失败，请重新操作" => "Failed, please try again",
    "延迟失败" => "Delay failed",
    "延迟处理失败" => "Delay processing failed",
    "延迟记录失败" => "Delay record failed",

    "运单加入站点失败!" => "Failed to add waybill to site!",
    "站点移除运单失败，请重新操作" => "Site failed to remove waybill, please try again",
    "取件移除运单失败，请重新操作" => "Failed to remove pick-up waybill, please try again",

    "包裹处理失败，请重新操作" => "Failed to process parcel, please try again",
    "运单处理失败，请重新操作" => "Failed to process waybill, please try again",
    "运单包裹处理失败，请重新操作" => "Failed to process waybill parcel, please try again",
    "订单处理失败，请重新操作" => "Failed to process order, please try again",
    "站点处理失败，请重新操作" => "Failed to process site, please try again",
    "线路任务处理失败，请重新操作" => "Failed to process pick-up route, please try again",

    "站点顺序调整失败，请重新操作" => "Failed to adjust site sequence, please try again",
    "重复扫描" => "duplicate scan",
    "出车失败" => "Vehicle departure failed 1",
    "到车失败" => "vehicle arrival failed",

    "站点锁定失败，请重新操作" => "Failed to lock site, please try again",
    "线路任务锁定失败，请重新操作" => "Failed to lock pick-up route, please try again",
    "线路任务取消锁定失败，请重新操作" => "Failed to lock pick-up route cancellation, please try again",

    "出库失败" => "Warehouse-out failed",
    "取消取派失败，请重新操作" => "Failed to cancel pick-up, please try again",
    "签收失败" => "Acceptance signature failed",
    "司机入库失败，请重新操作" => "Driver warehouse-in failed, please try again",
    "车辆取消分配失败，请重新操作" => "Failed to cancel vehicle assignment, please try again",
    "实际出库失败" => "Actual warehouse-out failed",
    "当前状态是[:status_name]，不能操作" => "Current status is [:status_name], cannot change",
    "运单取消锁定失败，请重新操作" => "Failed to cancel waybill lock, please try again",
    "运单锁定失败，请重新操作" => "Failed to lock waybill, please try again",
    "站点取消锁定失败，请重新操作" => "Failed to lock site cancellation, please try again",

    //操作
);
