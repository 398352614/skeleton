/*
 Navicat Premium Data Transfer

 Source Server         : dev
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Schema         : tms-api

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 13/03/2020 11:33:28
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
  `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '站点编号',
  `tour_no` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件线路编号',
  `line_id` int(11) NULL DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '线路名称',
  `execution_date` date NULL DEFAULT NULL COMMENT '取派日期',
  `status` int(11) NULL DEFAULT 1 COMMENT '状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派',
  `exception_label` tinyint(4) NULL DEFAULT 1 COMMENT '标签1-正常2-异常',
  `cancel_type` tinyint(4) NULL DEFAULT 1 COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '取消取派-图片',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机电话',
  `driver_rest_time` int(11) NULL DEFAULT 0 COMMENT '司机休息时长-秒',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '车牌号',
  `sort_id` int(11) NULL DEFAULT 1000 COMMENT '排序ID',
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
  `expect_distance` int(11) NULL DEFAULT NULL COMMENT '预计里程',
  `actual_distance` int(11) NULL DEFAULT NULL COMMENT '实际里程',
  `expect_time` int(11) NULL DEFAULT NULL COMMENT '预计耗时-秒',
  `actual_time` int(11) NULL DEFAULT NULL COMMENT '实际耗时-秒',
  `sticker_amount` decimal(8, 2) NULL COMMENT '贴单费用',
  `replace_amount` decimal(16, 2) NULL COMMENT '代收货款',
  `settlement_amount` decimal(16, 2) NULL COMMENT '结算金额-运费',
  `signature` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '客户签名',
  `pay_type` tinyint(4) NULL DEFAULT 1 COMMENT '支付方式1-现金支付2-银行支付',
  `pay_picture` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付图片',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `line_id`(`line_id`) USING BTREE,
  INDEX `execution_date`(`execution_date`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '批次表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for batch_exception
-- ----------------------------
DROP TABLE IF EXISTS `batch_exception`;
CREATE TABLE `batch_exception`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `batch_exception_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '异常编号',
  `batch_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '站点编号',
  `receiver` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收货方姓名',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '状态1-未处理2-已处理',
  `source` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '来源',
  `stage` tinyint(4) NULL DEFAULT 1 COMMENT '异常阶段1-在途异常2-装货异常',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '异常类型（在途异常：1道路2车辆3其他，装货异常1少货2货损3其他）',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '异常内容',
  `picture` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '异常图片',
  `deal_remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '处理内容',
  `deal_id` int(11) NULL DEFAULT NULL COMMENT '处理人ID(员工ID)',
  `deal_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '处理人姓名',
  `deal_time` datetime(0) NULL DEFAULT NULL COMMENT '处理时间',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID(创建人ID)',
  `driver_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '司机姓名(创建人姓名)',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  UNIQUE INDEX `batch_exception_no`(`batch_exception_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car
-- ----------------------------
DROP TABLE IF EXISTS `car`;
CREATE TABLE `car`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '车牌号',
  `outgoing_time` date NULL DEFAULT NULL COMMENT '出厂日期',
  `car_brand_id` int(11) NULL DEFAULT NULL COMMENT '车辆品牌ID',
  `car_model_id` int(11) NULL DEFAULT NULL COMMENT '车辆型号ID',
  `frame_number` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '车架号',
  `engine_number` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发动机编号',
  `transmission` tinyint(4) NULL DEFAULT 1 COMMENT '车型（1自动档，2手动挡）',
  `fuel_type` tinyint(4) NULL DEFAULT 1 COMMENT '燃料类型（1 柴油/ 2 汽油/ 3 混合动力/ 4电动）',
  `current_miles` decimal(16, 2) NULL DEFAULT NULL COMMENT '当前里程数',
  `annual_inspection_date` date NULL DEFAULT NULL COMMENT '下次年检日期',
  `ownership_type` tinyint(4) NULL DEFAULT 1 COMMENT '类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）',
  `received_date` date NULL DEFAULT NULL COMMENT '接收车辆日期',
  `month_road_tax` decimal(16, 2) NULL COMMENT '每月路税',
  `insurance_company` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '保险公司',
  `insurance_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '保险类型',
  `month_insurance` decimal(16, 2) NULL COMMENT '每月保险',
  `rent_start_date` date NULL DEFAULT NULL COMMENT '起租时间',
  `rent_end_date` date NULL DEFAULT NULL COMMENT '到期时间',
  `rent_month_fee` decimal(16, 2) NULL COMMENT '月租金',
  `repair` tinyint(4) NULL DEFAULT 1 COMMENT '维修自理（1 是/ 2 否）',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注',
  `relate_material` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '文件(相关材料)',
  `relate_material_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '相关材料名',
  `is_locked` tinyint(4) NULL DEFAULT 1 COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `car_no`(`car_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '车辆基础信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_brand
-- ----------------------------
DROP TABLE IF EXISTS `car_brand`;
CREATE TABLE `car_brand`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cn_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名',
  `en_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '品牌英文名',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `company_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `cn_name`(`cn_name`, `company_id`) USING BTREE,
  UNIQUE INDEX `en_name`(`en_name`, `company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_fee
-- ----------------------------
DROP TABLE IF EXISTS `car_fee`;
CREATE TABLE `car_fee`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型1-油费2-违章3-维修',
  `amount` decimal(16, 2) NULL COMMENT '金额',
  `attached_document` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '附件',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `car_id`(`car_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for car_model
-- ----------------------------
DROP TABLE IF EXISTS `car_model`;
CREATE TABLE `car_model`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) NOT NULL COMMENT '汽车型号对应的品牌 id',
  `cn_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名',
  `en_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '品牌英文名',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `company_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `brand_id`(`brand_id`, `cn_name`, `company_id`) USING BTREE,
  UNIQUE INDEX `brand_id_2`(`brand_id`, `en_name`, `company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司代码',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'email',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司名称',
  `contacts` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司联系人',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司电话',
  `country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '所在国家',
  `address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '公司地址',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_code`(`company_code`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for company_config
-- ----------------------------
DROP TABLE IF EXISTS `company_config`;
CREATE TABLE `company_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `line_rule` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '线路分配规则',
  `weight_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '重量单位',
  `currency_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '货币单位',
  `volume_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '体积单位',
  `map` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '地图引擎',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for country
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `short` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '简称',
  `en_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '英文名称',
  `cn_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '中文名称',
  `tel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '区号',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_cn_name`(`company_id`, `cn_name`) USING BTREE,
  UNIQUE INDEX `company_en_name`(`company_id`, `en_name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `company_id_2`(`company_id`, `short`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver
-- ----------------------------
DROP TABLE IF EXISTS `driver`;
CREATE TABLE `driver`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户邮箱',
  `messager` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '通讯标志',
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
  `lisence_valid_date` date NULL DEFAULT NULL COMMENT '有效期',
  `lisence_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '驾照类型',
  `lisence_material` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '驾照材料',
  `lisence_material_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '驾照材料名',
  `government_material` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '政府信件',
  `government_material_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '政府材料名',
  `avatar` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '头像',
  `bank_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '银行名称',
  `iban` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'IBAN',
  `bic` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'BIC',
  `is_locked` tinyint(4) NULL DEFAULT 1 COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `crop_type` smallint(6) NULL DEFAULT 1 COMMENT '合作类型1-雇佣2-包线',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '司机基础信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver_tour_trail
-- ----------------------------
DROP TABLE IF EXISTS `driver_tour_trail`;
CREATE TABLE `driver_tour_trail`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for driver_work
-- ----------------------------
DROP TABLE IF EXISTS `driver_work`;
CREATE TABLE `driver_work`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `driver_id` int(11) NOT NULL COMMENT '司机ID',
  `crop_type` int(11) NULL DEFAULT NULL COMMENT '合作方式：1雇佣，2包线',
  `workday` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '工作的时间',
  `business_range` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '业务范围',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `driver_id`(`driver_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '司机网点工作信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `encrypt` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '盐值',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '密码',
  `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `auth_group_id` int(11) NULL DEFAULT NULL COMMENT '权限组/员工组',
  `institution_id` int(11) NULL DEFAULT NULL COMMENT '机构ID',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `forbid_login` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '禁止登录标志',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for institutions
-- ----------------------------
DROP TABLE IF EXISTS `institutions`;
CREATE TABLE `institutions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '机构组织名',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '机构组织电话',
  `contacts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '机构组织负责人',
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '机构组织国家城市',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '机构组织负责人详细地址',
  `company_id` bigint(20) NOT NULL,
  `parent` bigint(20) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `institutions_company_id_index`(`company_id`) USING BTREE,
  INDEX `institutions_parent_index`(`parent`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for institutions_closure
-- ----------------------------
DROP TABLE IF EXISTS `institutions_closure`;
CREATE TABLE `institutions_closure`  (
  `ancestor` int(10) UNSIGNED NOT NULL,
  `descendant` int(10) UNSIGNED NOT NULL,
  `distance` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`ancestor`, `descendant`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for kilometres_charging
-- ----------------------------
DROP TABLE IF EXISTS `kilometres_charging`;
CREATE TABLE `kilometres_charging`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) NULL DEFAULT NULL COMMENT '运价ID',
  `start` int(11) NULL DEFAULT NULL COMMENT '起始公里',
  `end` int(11) NULL DEFAULT NULL COMMENT '截止公里',
  `price` decimal(8, 2) NULL COMMENT '加价',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `transport_price_id`(`transport_price_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
  `warehouse_id` int(11) NULL DEFAULT 0 COMMENT '网点ID',
  `order_max_count` int(8) NULL DEFAULT 0 COMMENT '最大订单量',
  `creator_id` int(11) NULL DEFAULT NULL COMMENT '创建人ID(员工ID)',
  `creator_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建人姓名(员工姓名)',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `name`(`name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  UNIQUE INDEX `company_id_name`(`company_id`, `name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '路线表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for line_log
-- ----------------------------
DROP TABLE IF EXISTS `line_log`;
CREATE TABLE `line_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公司ID',
  `line_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '线路名称',
  `user` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作用户',
  `operation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作记录',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `line_name`(`line_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '国家',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `line_id`(`line_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '路线区间表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for material
-- ----------------------------
DROP TABLE IF EXISTS `material`;
CREATE TABLE `material`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '取件线路编号',
  `batch_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '站点编号',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '订单编号',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '材料名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '材料代码',
  `out_order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '货号/标识',
  `expect_quantity` smallint(6) NULL DEFAULT 0 COMMENT '预计数量',
  `actual_quantity` smallint(6) NULL DEFAULT 0 COMMENT '实际数量',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no_code`(`order_no`, `code`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no_2`(`order_no`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `batch_no`(`batch_no`) USING BTREE,
  UNIQUE INDEX `order_no_name`(`order_no`, `name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for memorandum
-- ----------------------------
DROP TABLE IF EXISTS `memorandum`;
CREATE TABLE `memorandum`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for merchant
-- ----------------------------
DROP TABLE IF EXISTS `merchant`;
CREATE TABLE `merchant`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型1-个人2-货主',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '名称',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '邮箱',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '密码',
  `settlement_type` tinyint(4) NULL DEFAULT 1 COMMENT '结算方式1-票结2-日结3-月结',
  `merchant_group_id` int(11) NULL DEFAULT NULL COMMENT '货主组ID',
  `contacter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '电话',
  `address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '联系地址',
  `avatar` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '头像',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '状态1-启用2-禁用',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id_name`(`company_id`, `name`) USING BTREE,
  UNIQUE INDEX `company_id`(`company_id`, `email`) USING BTREE,
  INDEX `company_id_2`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for merchant_api
-- ----------------------------
DROP TABLE IF EXISTS `merchant_api`;
CREATE TABLE `merchant_api`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) NULL DEFAULT NULL COMMENT '货主ID',
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT 'key',
  `secret` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT 'secret',
  `url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '推送url',
  `white_ip_list` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '白名单IP列表',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '推送1-是2-否',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_merchant`(`company_id`, `merchant_id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE,
  UNIQUE INDEX `secret`(`secret`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for merchant_group
-- ----------------------------
DROP TABLE IF EXISTS `merchant_group`;
CREATE TABLE `merchant_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '名称',
  `transport_price_id` int(11) NULL DEFAULT NULL COMMENT '运价ID',
  `count` int(11) NULL DEFAULT 0 COMMENT '成员数量',
  `is_default` tinyint(4) NULL DEFAULT 2 COMMENT '是否是默认组1-是2-否',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id_name`(`company_id`, `name`) USING BTREE,
  UNIQUE INDEX `name`(`name`, `transport_price_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) NULL DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `execution_date` date NULL DEFAULT NULL COMMENT '取件/派件 日期',
  `batch_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '站点编号',
  `tour_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '取件线路编号',
  `out_order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '货号',
  `express_first_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号1',
  `express_second_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '快递单号2',
  `source` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '来源',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型:1-取;2-派',
  `out_user_id` int(11) NULL DEFAULT 0 COMMENT '客户单号',
  `nature` tinyint(4) NULL DEFAULT 1 COMMENT '性质:1-包裹2-材料3-文件4-增值服务5-其他',
  `settlement_type` tinyint(4) NULL DEFAULT 1 COMMENT '结算类型1-寄付2-到付',
  `settlement_amount` decimal(16, 2) NULL COMMENT '结算金额',
  `replace_amount` decimal(16, 2) NULL COMMENT '代收货款',
  `delivery` tinyint(4) NULL DEFAULT 2 COMMENT '是否送货上门1-是2否',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `exception_label` tinyint(4) NULL DEFAULT 1 COMMENT '标签1-正常2-异常',
  `cancel_type` smallint(6) NULL DEFAULT NULL COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '取消取派-图片',
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
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '纬度',
  `special_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '特殊事项',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `unique_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '识别码',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '司机姓名',
  `driver_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '司机电话',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '车牌号',
  `sticker_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(8, 2) NULL COMMENT '贴单费用',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `execution_date`(`execution_date`) USING BTREE,
  INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '订单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_import_log
-- ----------------------------
DROP TABLE IF EXISTS `order_import_log`;
CREATE TABLE `order_import_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '下载链接',
  `status` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '状态',
  `log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '日志',
  `success_order` int(11) NULL DEFAULT 0 COMMENT '导入订单成功数量',
  `fail_order` int(11) NULL DEFAULT 0 COMMENT '导入订单失败数量',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_items
-- ----------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '订单号',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '货物名称',
  `quantity` mediumint(20) NULL DEFAULT 0 COMMENT '数量',
  `weight` decimal(8, 2) NULL COMMENT '重量',
  `volume` decimal(8, 2) NULL COMMENT '体积',
  `price` decimal(8, 2) NULL COMMENT '单价',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no_name`(`order_no`, `name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_no_rule
-- ----------------------------
DROP TABLE IF EXISTS `order_no_rule`;
CREATE TABLE `order_no_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '类型',
  `prefix` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '前缀',
  `start_index` int(11) NULL DEFAULT NULL COMMENT '开始索引',
  `end_alpha` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'A' COMMENT '尾号字母',
  `length` int(8) NULL DEFAULT NULL COMMENT '长度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_type`(`company_id`, `type`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_operation
-- ----------------------------
DROP TABLE IF EXISTS `order_operation`;
CREATE TABLE `order_operation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for order_trail
-- ----------------------------
DROP TABLE IF EXISTS `order_trail`;
CREATE TABLE `order_trail`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '订单号',
  `content` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '内容',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for package
-- ----------------------------
DROP TABLE IF EXISTS `package`;
CREATE TABLE `package`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '取件线路编号',
  `batch_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '站点编号',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '订单编号',
  `type` tinyint(4) NULL DEFAULT 1 COMMENT '类型1-取2-派',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '包裹名称',
  `express_first_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '快递单号1',
  `express_second_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '快递单号2',
  `out_order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '货号/标识',
  `weight` decimal(8, 2) NULL COMMENT '重量',
  `expect_quantity` smallint(6) NULL DEFAULT 1 COMMENT '预计数量',
  `actual_quantity` smallint(6) NULL DEFAULT 0 COMMENT '实际数量',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `sticker_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(8, 2) NULL COMMENT '贴单费用',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no_name`(`order_no`, `name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `batch_no`(`batch_no`) USING BTREE,
  INDEX `order_no`(`order_no`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  UNIQUE INDEX `express_no`(`express_first_no`, `express_second_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for receiver_address
-- ----------------------------
DROP TABLE IF EXISTS `receiver_address`;
CREATE TABLE `receiver_address`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) NULL DEFAULT NULL COMMENT '货主ID',
  `receiver` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人姓名',
  `receiver_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人电话',
  `receiver_country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人国家',
  `receiver_post_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人邮编',
  `receiver_house_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人门牌号',
  `receiver_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人城市',
  `receiver_street` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人街道',
  `receiver_address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '收件人详细地址',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '纬度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `merchant_id`(`merchant_id`) USING BTREE,
  UNIQUE INDEX `all_address`(`merchant_id`, `receiver`, `receiver_phone`, `receiver_country`, `receiver_post_code`, `receiver_house_number`, `receiver_city`, `receiver_street`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for route_tracking
-- ----------------------------
DROP TABLE IF EXISTS `route_tracking`;
CREATE TABLE `route_tracking`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '纬度',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '在途编号',
  `driver_id` int(11) NULL DEFAULT NULL COMMENT '司机ID',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sender_address
-- ----------------------------
DROP TABLE IF EXISTS `sender_address`;
CREATE TABLE `sender_address`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) NULL DEFAULT NULL COMMENT '货主ID',
  `sender` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人姓名',
  `sender_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人电话',
  `sender_country` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人国家',
  `sender_post_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人邮编',
  `sender_house_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人门牌号',
  `sender_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人城市',
  `sender_street` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人街道',
  `sender_address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '发件人详细地址',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '纬度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `merchant_id`(`merchant_id`) USING BTREE,
  UNIQUE INDEX `all_address`(`merchant_id`, `sender`, `sender_phone`, `sender_country`, `sender_post_code`, `sender_house_number`, `sender_city`, `sender_street`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for source
-- ----------------------------
DROP TABLE IF EXISTS `source`;
CREATE TABLE `source`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `source_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '来源名称',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for special_time_charging
-- ----------------------------
DROP TABLE IF EXISTS `special_time_charging`;
CREATE TABLE `special_time_charging`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) NULL DEFAULT NULL COMMENT '运价ID',
  `start` time(0) NULL DEFAULT NULL COMMENT '起始时间',
  `end` time(0) NULL DEFAULT NULL COMMENT '截止时间',
  `price` decimal(8, 2) NULL COMMENT '加价',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `transport_price_id`(`transport_price_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for telescope_entries
-- ----------------------------
DROP TABLE IF EXISTS `telescope_entries`;
CREATE TABLE `telescope_entries`  (
  `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`sequence`) USING BTREE,
  UNIQUE INDEX `telescope_entries_uuid_unique`(`uuid`) USING BTREE,
  INDEX `telescope_entries_batch_id_index`(`batch_id`) USING BTREE,
  INDEX `telescope_entries_type_should_display_on_index_index`(`type`, `should_display_on_index`) USING BTREE,
  INDEX `telescope_entries_family_hash_index`(`family_hash`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 401928 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for telescope_entries_tags
-- ----------------------------
DROP TABLE IF EXISTS `telescope_entries_tags`;
CREATE TABLE `telescope_entries_tags`  (
  `entry_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  INDEX `telescope_entries_tags_entry_uuid_tag_index`(`entry_uuid`, `tag`) USING BTREE,
  INDEX `telescope_entries_tags_tag_index`(`tag`) USING BTREE,
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for telescope_monitoring
-- ----------------------------
DROP TABLE IF EXISTS `telescope_monitoring`;
CREATE TABLE `telescope_monitoring`  (
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
  `driver_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '司机电话',
  `driver_rest_time` int(11) NULL DEFAULT NULL COMMENT '司机休息时长',
  `driver_avt_id` int(11) NULL DEFAULT NULL COMMENT '取派件AVT设备ID',
  `car_id` int(11) NULL DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '车牌号',
  `warehouse_id` int(11) NULL DEFAULT 0 COMMENT '网点ID',
  `warehouse_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点名称',
  `warehouse_phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点电话',
  `warehouse_post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点邮编',
  `warehouse_city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点城市',
  `warehouse_street` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '网点街道',
  `warehouse_house_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '网点门牌号',
  `warehouse_address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点详细地址',
  `warehouse_lon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点经度',
  `warehouse_lat` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '网点纬度',
  `status` smallint(6) NULL DEFAULT 1 COMMENT '状态1-待分配2-已分配-3-待出库4-取派中5-取派完成',
  `begin_time` datetime(0) NULL DEFAULT NULL COMMENT '出库时间',
  `begin_signature` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库签名',
  `begin_signature_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库备注',
  `begin_signature_first_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片1',
  `begin_signature_second_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片2',
  `begin_signature_third_pic` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出库图片3',
  `end_time` datetime(0) NULL DEFAULT NULL COMMENT '入库时间',
  `end_signature` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入库签名',
  `end_signature_remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '入库备注',
  `expect_distance` int(11) NULL DEFAULT NULL COMMENT '预计里程',
  `actual_distance` int(11) NULL DEFAULT NULL COMMENT '实际里程',
  `expect_time` int(11) NULL DEFAULT NULL COMMENT '预计耗时-秒',
  `actual_time` int(11) NULL DEFAULT NULL COMMENT '实际耗时-秒',
  `expect_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '预计取件数量(预计包裹入库数量)',
  `actual_pickup_quantity` int(8) NULL DEFAULT 0 COMMENT '实际取件数量(实际包裹入库数量)',
  `expect_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '预计派件数量(预计包裹出库数量)',
  `actual_pie_quantity` int(8) NULL DEFAULT 0 COMMENT '实际派件数量(实际包裹出库数量)',
  `sticker_amount` decimal(8, 2) NULL COMMENT '贴单费用',
  `replace_amount` decimal(16, 2) NULL COMMENT '代收货款',
  `settlement_amount` decimal(16, 2) NULL COMMENT '结算金额-运费',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `lave_distance` bigint(20) NULL DEFAULT 0 COMMENT '剩余里程数',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tour_no`(`tour_no`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `line_id`(`line_id`) USING BTREE,
  INDEX `driver_id`(`driver_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `execution_date`(`execution_date`) USING BTREE,
  INDEX `car_id`(`car_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 197 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tour_driver_event
-- ----------------------------
DROP TABLE IF EXISTS `tour_driver_event`;
CREATE TABLE `tour_driver_event`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '经度',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '纬度',
  `type` int(11) NULL DEFAULT 0 COMMENT '事件类型 -- 预留',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '事件详情',
  `icon_id` int(11) NULL DEFAULT 0 COMMENT '图标 id 预留',
  `icon_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '图标url地址',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '在途编号',
  `route_tracking_id` int(11) NULL DEFAULT 0 COMMENT '对应的路线追踪中的点,预留',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `tour_no`(`tour_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tour_log
-- ----------------------------
DROP TABLE IF EXISTS `tour_log`;
CREATE TABLE `tour_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '在途的唯一标识',
  `action` tinyint(4) NOT NULL COMMENT '对在途进行的操作',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '日志的状态, 1 为进行中 2 为已完成 3 为异常',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tour_material
-- ----------------------------
DROP TABLE IF EXISTS `tour_material`;
CREATE TABLE `tour_material`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '取件线路编号',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '材料名称',
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '材料代码',
  `expect_quantity` smallint(6) NULL DEFAULT 0 COMMENT '预计数量',
  `actual_quantity` smallint(6) NULL DEFAULT 0 COMMENT '实际数量',
  `finish_quantity` smallint(6) NULL DEFAULT 0 COMMENT '完成数量',
  `surplus_quantity` smallint(6) NULL DEFAULT 0 COMMENT '剩余数量',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tour_no_code`(`tour_no`, `code`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for transport_price
-- ----------------------------
DROP TABLE IF EXISTS `transport_price`;
CREATE TABLE `transport_price`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '名称',
  `starting_price` decimal(8, 2) NULL COMMENT '起步价',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '特别说明',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '状态1-启用2-禁用',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id_name`(`company_id`, `name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for version
-- ----------------------------
DROP TABLE IF EXISTS `version`;
CREATE TABLE `version`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT 0 COMMENT '公司ID',
  `uploader_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '上传者邮箱',
  `name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'TMS' COMMENT '名称',
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '版本号',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '状态（1-强制更新，2可选更新）',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下载链接',
  `change_log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '更新日志',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for warehouse
-- ----------------------------
DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网点名称',
  `contacter` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '电话',
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国家',
  `post_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '邮编',
  `house_number` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '门牌号',
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '城市',
  `street` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '街道',
  `address` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '发件人详细地址',
  `lat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '纬度',
  `lon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '经度',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `company_id_2`(`company_id`, `name`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for weight_charging
-- ----------------------------
DROP TABLE IF EXISTS `weight_charging`;
CREATE TABLE `weight_charging`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NULL DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) NULL DEFAULT NULL COMMENT '运价ID',
  `start` int(11) NULL DEFAULT NULL COMMENT '起始重量',
  `end` int(11) NULL DEFAULT NULL COMMENT '截止重量',
  `price` decimal(8, 2) NULL COMMENT '加价',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `transport_price_id`(`transport_price_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
