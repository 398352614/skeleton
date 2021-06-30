
ALTER TABLE batch MODIFY `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '站点编号';
ALTER TABLE batch MODIFY `tour_no` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路任务编号';
ALTER TABLE batch MODIFY `line_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路名称';
ALTER TABLE batch MODIFY `sticker_amount` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '贴单费用';
ALTER TABLE batch MODIFY `replace_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '代收货款';
ALTER TABLE batch MODIFY `settlement_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '结算金额-运费';

ALTER TABLE car MODIFY `month_insurance` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '每月保险';
ALTER TABLE car MODIFY `rent_month_fee` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '月租金';

ALTER TABLE driver_work MODIFY `workday` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '工作的时间';
ALTER TABLE driver_work MODIFY `business_range` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '业务范围';

ALTER TABLE employee MODIFY `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '姓名';

ALTER TABLE institutions MODIFY `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '机构组织电话';
ALTER TABLE institutions MODIFY `contacts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '机构组织负责人';
ALTER TABLE institutions MODIFY `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '机构组织国家城市';
ALTER TABLE institutions MODIFY `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '机构组织负责人详细地址';

ALTER TABLE line MODIFY `creator_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '创建人姓名(员工姓名)';

ALTER TABLE memorandum MODIFY `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '内容';

ALTER TABLE `order` MODIFY `order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '订单号';
ALTER TABLE `order` MODIFY `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '站点编号';
ALTER TABLE `order` MODIFY `tour_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路任务编号';
ALTER TABLE `order` MODIFY `out_order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '货号';
ALTER TABLE `order` MODIFY `express_first_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '快递单号1';
ALTER TABLE `order` MODIFY `express_second_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '快递单号2';
ALTER TABLE `order` MODIFY `source` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '来源';
ALTER TABLE `order` MODIFY `sender_fullname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人姓名';
ALTER TABLE `order` MODIFY `sender_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人电话';
ALTER TABLE `order` MODIFY `sender_country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人国家';
ALTER TABLE `order` MODIFY `sender_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人邮编';
ALTER TABLE `order` MODIFY `sender_house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人门牌号';
ALTER TABLE `order` MODIFY `sender_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人城市';
ALTER TABLE `order` MODIFY `sender_street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人街道';
ALTER TABLE `order` MODIFY `sender_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发件人详细地址';
ALTER TABLE `order` MODIFY `receiver_fullname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人姓名';
ALTER TABLE `order` MODIFY `receiver_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人电话';
ALTER TABLE `order` MODIFY `receiver_country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人国家';
ALTER TABLE `order` MODIFY `receiver_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人邮编';
ALTER TABLE `order` MODIFY `receiver_house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人门牌号';
ALTER TABLE `order` MODIFY `receiver_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人城市';
ALTER TABLE `order` MODIFY `receiver_street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人街道';
ALTER TABLE `order` MODIFY `receiver_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人详细地址';
ALTER TABLE `order` MODIFY `special_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '特殊事项';
ALTER TABLE `order` MODIFY `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注';
ALTER TABLE `order` MODIFY `unique_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '识别码';
ALTER TABLE `order` MODIFY `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机姓名';
ALTER TABLE `order` MODIFY `settlement_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '结算金额';
ALTER TABLE `order` MODIFY `replace_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '代收货款';
ALTER TABLE `order` MODIFY `sticker_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '贴单费用';

ALTER TABLE `order_items` MODIFY `price` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '单价';

ALTER TABLE order_no_rule MODIFY `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类型';
ALTER TABLE order_no_rule MODIFY `prefix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '前缀';

ALTER TABLE order_operation MODIFY `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '订单号';

ALTER TABLE `package` MODIFY `sticker_amount` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '贴单费用';

ALTER TABLE `special_time_charging` MODIFY `price` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '加价';

ALTER TABLE order_trail MODIFY `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '内容';

ALTER TABLE tour MODIFY `tour_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路任务编号';
ALTER TABLE tour MODIFY `line_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路名';
ALTER TABLE tour MODIFY `begin_signature` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '出库签名';
ALTER TABLE tour MODIFY `begin_signature_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '出库备注';
ALTER TABLE tour MODIFY `begin_signature_first_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '出库图片1';
ALTER TABLE tour MODIFY `begin_signature_second_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '出库图片2';
ALTER TABLE tour MODIFY `begin_signature_third_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '出库图片3';
ALTER TABLE tour MODIFY `end_signature` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '入库签名';
ALTER TABLE tour MODIFY `end_signature_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '入库备注';
ALTER TABLE tour MODIFY `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注';
ALTER TABLE tour MODIFY `sticker_amount` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '贴单费用';
ALTER TABLE tour MODIFY `replace_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '代收货款';
ALTER TABLE tour MODIFY `settlement_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '结算金额-运费';

ALTER TABLE transport_price MODIFY `starting_price` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '起步价';

ALTER TABLE weight_charging MODIFY `price` decimal(8, 2) NULL DEFAULT 0.00 COMMENT '加价';
