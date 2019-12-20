/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : tms-api

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 20/12/2019 15:26:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for batch
-- ----------------------------
DROP TABLE IF EXISTS `batch`;
CREATE TABLE `batch`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取派件批次编号',
  `tour_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件线路编号',
  `line_id` int(11) NULL DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '线路名称',
  `execution_date` date NULL DEFAULT NULL COMMENT '取派日期',
  `status` int(11) NULL DEFAULT 1 COMMENT '状态：1-未取派2-已分配3-取派中4-已签收5-异常',
  `exception_type` tinyint(4) NULL DEFAULT 1 COMMENT '异常类型1-正常2-签收异常3-在途异常4-装货异常',
  `exception_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '异常备注',
  `exception_picture` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '异常图片',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机电话',
  `driver_rest_time` int(11) NULL DEFAULT 0 COMMENT '司机休息时长-秒',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '车牌号',
  `sort_id` int(11) NULL DEFAULT 0 COMMENT '排序ID',
  `expect_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '预计取件数量',
  `actual_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '实际取件数量',
  `expect_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '预计派件数量',
  `actual_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '实际派件数量',
  `receiver` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人姓名',
  `receiver_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人电话',
  `receiver_country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人国家',
  `receiver_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人邮编',
  `receiver_house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人门牌号',
  `receiver_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人城市',
  `receiver_street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人街道',
  `receiver_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人详细地址',
  `receiver_lon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人经度',
  `receiver_lat` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '收件人纬度',
  `expect_arrive_time` datetime(0) NULL DEFAULT NULL COMMENT '预计到达时间',
  `actual_arrive_time` datetime(0) NULL DEFAULT NULL COMMENT '实际到达时间',
  `expect_distance` decimal(16, 2) NULL DEFAULT NULL COMMENT '预计里程',
  `actual_distance` decimal(16, 2) NULL DEFAULT NULL COMMENT '实际里程',
  `expect_time` int(11) NULL DEFAULT NULL COMMENT '预计耗时-秒',
  `actual_time` int(11) NULL DEFAULT NULL COMMENT '实际耗时-秒',
  `order_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '贴单费用',
  `replace_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '代收货款',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `line_id`(`line_id`) USING BTREE,
  INDEX `execution_date`(`execution_date`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '批次表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car
-- ----------------------------
DROP TABLE IF EXISTS `car`;
CREATE TABLE `car`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '车牌号',
  `outgoing_time` int(11) NULL DEFAULT NULL COMMENT '出厂日期',
  `car_brand_id` int(11) NULL DEFAULT NULL COMMENT '车辆品牌ID',
  `car_model_id` int(11) NULL DEFAULT NULL COMMENT '汽车型号id',
  `frame_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '车架号',
  `engine_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发动机编号',
  `transmission` tinyint(4) NULL DEFAULT 1 COMMENT '车型（1自动档，2手动挡）',
  `fuel_type` tinyint(4) NULL DEFAULT 1 COMMENT '燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）',
  `current_miles` decimal(16, 2) NULL DEFAULT NULL COMMENT '当前里程数',
  `annual_inspection_date` date NULL DEFAULT NULL COMMENT '下次年检日期',
  `ownership_type` tinyint(4) NULL DEFAULT 1 COMMENT '类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）',
  `received_date` date NULL DEFAULT NULL COMMENT '接收车辆日期',
  `month_road_tax` double(8, 2) NULL DEFAULT 0.00 COMMENT '每月路税',
  `insurance_company` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '保险公司',
  `insurance_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '保险类型',
  `month_insurance` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '每月保险',
  `rent_start_date` date NULL DEFAULT NULL COMMENT '起租时间',
  `rent_end_date` date NULL DEFAULT NULL COMMENT '到期时间',
  `rent_month_fee` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '月租金',
  `repair` tinyint(4) NULL DEFAULT 1 COMMENT '维修自理（1 是/ 2 否）',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `relate_material` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '文件(相关材料)',
  `is_locked` tinyint(4) NULL DEFAULT 1 COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `car_no`(`car_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '车辆基础信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_fee
-- ----------------------------
DROP TABLE IF EXISTS `car_fee`;
CREATE TABLE `car_fee`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型1-油费2-违章3-维修',
  `amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '金额',
  `attached_document` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附件',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `car_id`(`car_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`  (
  `id` int(11) NOT NULL,
  `company_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司代码',
  `email` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'email',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司名称',
  `contacter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司联系人',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司电话',
  `country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '所在国家',
  `address` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司地址',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_code`(`company_code`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver
-- ----------------------------
DROP TABLE IF EXISTS `driver`;
CREATE TABLE `driver`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户邮箱',
  `encrypt` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '盐值',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '密码',
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '姓',
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名',
  `gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '性别',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '手机',
  `duty_paragraph` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '税号',
  `post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '邮编',
  `door_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '门牌号',
  `street` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '街道',
  `city` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '城市',
  `country` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '国家',
  `lisence_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '驾照编号',
  `lisence_valid_date` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '有效期',
  `lisence_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '驾照类型',
  `lisence_material` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '驾照材料',
  `government_material` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '政府信件',
  `avatar` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '头像',
  `bank_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '银行名称',
  `iban` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'IBAN',
  `bic` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'BIC',
  `is_locked` tinyint(4) NULL DEFAULT 1 COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '司机基础信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver_tour_trail
-- ----------------------------
DROP TABLE IF EXISTS `driver_tour_trail`;
CREATE TABLE `driver_tour_trail`  (
  `id` int(11) NOT NULL COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '取件线路编号',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '纬度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver_work
-- ----------------------------
DROP TABLE IF EXISTS `driver_work`;
CREATE TABLE `driver_work`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `driver_id` int(11) NOT NULL COMMENT '司机ID',
  `crop_type` int(11) NULL DEFAULT NULL COMMENT '合作方式：1雇佣，2包线',
  `workday` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '工作的时间',
  `business_range` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '业务范围',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '司机网点工作信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee`  (
  `id` int(11) NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `encrypt` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '盐值',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码',
  `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `auth_group_id` int(11) NULL DEFAULT NULL COMMENT '权限组/员工组',
  `institution_id` int(11) NULL DEFAULT NULL COMMENT '机构ID',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for line
-- ----------------------------
DROP TABLE IF EXISTS `line`;
CREATE TABLE `line`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路名称',
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '国家',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '线路备注',
  `warehouse_id` int(11) NULL DEFAULT 0 COMMENT '仓库ID',
  `order_max_count` int(8) NULL DEFAULT 0 COMMENT '最大订单量',
  `creator_id` int(11) NULL DEFAULT NULL COMMENT '创建人ID(员工ID)',
  `creator_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建人姓名(员工姓名)',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '路线表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for line_range
-- ----------------------------
DROP TABLE IF EXISTS `line_range`;
CREATE TABLE `line_range`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) NOT NULL COMMENT '线路ID',
  `post_code_start` int(11) NULL DEFAULT NULL COMMENT '起始邮编',
  `post_code_end` int(11) NULL DEFAULT NULL COMMENT '结束邮编',
  `schedule` tinyint(4) NULL DEFAULT NULL COMMENT '取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '路线区间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for memorandum
-- ----------------------------
DROP TABLE IF EXISTS `memorandum`;
CREATE TABLE `memorandum`  (
  `id` int(11) NOT NULL COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `dirver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `content` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `dirver_id`(`dirver_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `execution_date` date NULL DEFAULT NULL COMMENT '取件/派件 日期',
  `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件批次编号',
  `tour_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件线路编号',
  `out_order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '外部订单号',
  `express_first_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号1',
  `express_second_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号2',
  `source` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '来源',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型:1-取;2-派',
  `out_user_id` int(11) NULL DEFAULT 0 COMMENT '外部客户ID',
  `nature` tinyint(4) NULL DEFAULT 1 COMMENT '性质:1-包裹2-材料3-文件4-增值服务5-其他',
  `settlement_type` tinyint(4) NULL DEFAULT 1 COMMENT '结算类型1-寄付2-到付',
  `settlement_amount` decimal(16, 2) NULL DEFAULT 0.00 COMMENT '结算金额',
  `replace_amount` decimal(16, 2) NULL DEFAULT NULL COMMENT '代收货款',
  `delivery` tinyint(4) NULL DEFAULT 2 COMMENT '是否送货上门1-是2否',
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '订单状态1-未取派2-已分配3-取派中4-已签收5-异常6-收回站',
  `exception_type` tinyint(4) NULL DEFAULT 1 COMMENT '异常类型1-正常2-签收异常3-在途异常4-装货异常',
  `exception_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '异常备注',
  `exception_picture` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '异常图片',
  `sender` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人姓名',
  `sender_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人电话',
  `sender_country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人国家',
  `sender_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人邮编',
  `sender_house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人门牌号',
  `sender_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人城市',
  `sender_street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人街道',
  `sender_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人详细地址',
  `receiver` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人姓名',
  `receiver_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人电话',
  `receiver_country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人国家',
  `receiver_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人邮编',
  `receiver_house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人门牌号',
  `receiver_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人城市',
  `receiver_street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人街道',
  `receiver_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收件人详细地址',
  `special_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '特殊事项',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `unique_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '识别码',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '司机姓名',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no`) USING BTREE,
  UNIQUE INDEX `out_order_no`(`out_order_no`) USING BTREE,
  UNIQUE INDEX `express_no`(`express_first_no`, `express_second_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `execution_date`(`execution_date`) USING BTREE,
  INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_no_rule
-- ----------------------------
DROP TABLE IF EXISTS `order_no_rule`;
CREATE TABLE `order_no_rule`  (
  `id` int(11) NOT NULL,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `type` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '类型',
  `prefix` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT '前缀',
  `start_index` int(11) NULL DEFAULT NULL COMMENT '开始索引',
  `length` int(8) NULL DEFAULT NULL COMMENT '长度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id`(`company_id`, `type`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_operation
-- ----------------------------
DROP TABLE IF EXISTS `order_operation`;
CREATE TABLE `order_operation`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_trail
-- ----------------------------
DROP TABLE IF EXISTS `order_trail`;
CREATE TABLE `order_trail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tour
-- ----------------------------
DROP TABLE IF EXISTS `tour`;
CREATE TABLE `tour`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `tour_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件线路编号',
  `line_id` int(11) NULL DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '线路名',
  `execution_date` date NULL DEFAULT NULL COMMENT '取派日期',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '司机姓名',
  `driver_rest_time` int(11) NULL DEFAULT NULL COMMENT '司机休息时长',
  `driver_avt_id` int(11) NULL DEFAULT NULL COMMENT '取派件AVT设备ID',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '车牌',
  `warehouse_id` int(11) NULL DEFAULT 0 COMMENT '仓库ID',
  `warehouse_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库名称',
  `warehouse_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库电话',
  `warehouse_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库邮编',
  `warehouse_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库城市',
  `warehouse_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库详细地址',
  `warehouse_lon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库经度',
  `warehouse_lat` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '仓库纬度',
  `status` tinyint(4) NULL DEFAULT 10 COMMENT '状态：1-未取派2-取派中3-取派完成',
  `begin_signature` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库签名',
  `begin_signature_remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库备注',
  `begin_signature_first_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片1',
  `begin_signature_second_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片2',
  `begin_signature_third_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片3',
  `end_signature` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入库签名',
  `end_signature_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入库备注',
  `expect_distance` decimal(16, 2) NULL DEFAULT NULL COMMENT '预计里程',
  `actual_distance` decimal(16, 2) NULL DEFAULT NULL COMMENT '实际里程',
  `expect_time` int(11) NULL DEFAULT NULL COMMENT '预计耗时-秒',
  `actual_time` int(11) NULL DEFAULT NULL COMMENT '实际耗时-秒',
  `expect_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '预计取件数量(预计包裹入库数量)',
  `actual_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '实际取件数量(实际包裹入库数量)',
  `expect_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '预计派件数量(预计包裹出库数量)',
  `actual_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '实际派件数量(实际包裹出库数量)',
  `order_amount` decimal(16, 2) NULL DEFAULT NULL COMMENT '贴单费用',
  `replace_amount` decimal(16, 2) NULL DEFAULT NULL COMMENT '代收货款',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_at` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `line_id`(`line_id`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for warehouse
-- ----------------------------
DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '仓库名称',
  `contacter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '电话',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国家',
  `post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮编',
  `house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '门牌号',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市',
  `street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '街道',
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人详细地址',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
