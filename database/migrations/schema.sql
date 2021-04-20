-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.0.10:3306
-- Generation Time: 2021-01-13 08:02:33
-- 服务器版本： 5.7.11-log
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nle-tms`
--

-- --------------------------------------------------------

--
-- 表的结构 `additional_package`
--

CREATE TABLE `additional_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `tour_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '取件线路编号',
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `package_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹编号',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `execution_date` date DEFAULT NULL COMMENT '顺带日期',
  `sticker_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '贴单号',
  `sticker_amount` decimal(16,2) DEFAULT '0.00' COMMENT '贴单费',
  `delivery_amount` decimal(16,2) DEFAULT '0.00' COMMENT '提货费',
  `place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人姓名',
  `place_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人电话',
  `place_country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人国家',
  `place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人邮编',
  `place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人门牌号',
  `place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人城市',
  `place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人街道',
  `place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人地址',
  `place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人经度',
  `place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人纬度',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-已推送2-未推送',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `address`
--

CREATE TABLE `address` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人姓名',
  `place_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人电话',
  `place_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人国家',
  `place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人邮编',
  `place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人门牌号',
  `place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人城市',
  `place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人街道',
  `place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人详细地址',
  `place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `address_template`
--

CREATE TABLE `address_template` (
  `id` int(10) UNSIGNED NOT NULL,
  `template` json DEFAULT NULL COMMENT '模板',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `api_times`
--

CREATE TABLE `api_times` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `date` date DEFAULT NULL COMMENT '日期',
  `directions_times` int(11) DEFAULT '0' COMMENT '智能优化数',
  `actual_directions_times` int(11) DEFAULT '0' COMMENT '智能优化成功数',
  `api_directions_times` int(11) DEFAULT '0' COMMENT '智能优化请求第三方数',
  `distance_times` int(11) DEFAULT '0' COMMENT '计算距离数',
  `actual_distance_times` int(11) DEFAULT '0' COMMENT '计算距离成功数',
  `api_distance_times` int(11) DEFAULT '0' COMMENT '计算距离请求第三方数',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `batch`
--

CREATE TABLE `batch` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT '0' COMMENT '货主ID',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tour_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `status` int(11) DEFAULT '1' COMMENT '状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派',
  `exception_label` tinyint(4) DEFAULT '1' COMMENT '标签1-正常2-异常',
  `cancel_type` tinyint(4) DEFAULT '1' COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-图片',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机电话',
  `driver_rest_time` int(11) DEFAULT '0' COMMENT '司机休息时长-秒',
  `car_id` int(11) DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '车牌号',
  `sort_id` int(11) DEFAULT '1000' COMMENT '排序ID',
  `is_skipped` tinyint(4) DEFAULT '2' COMMENT '是否跳过1-跳过2-不跳过',
  `expect_pickup_quantity` int(8) DEFAULT '0' COMMENT '预计取件数量',
  `actual_pickup_quantity` int(8) DEFAULT '0' COMMENT '实际取件数量',
  `expect_pie_quantity` int(8) DEFAULT '0' COMMENT '预计派件数量',
  `actual_pie_quantity` int(8) DEFAULT '0' COMMENT '实际派件数量',
  `place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人姓名',
  `place_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人电话',
  `place_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人国家',
  `place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人邮编',
  `place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人门牌号',
  `place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人城市',
  `place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人街道',
  `place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人详细地址',
  `place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人经度',
  `place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人纬度',
  `expect_arrive_time` datetime DEFAULT NULL COMMENT '预计到达时间',
  `actual_arrive_time` datetime DEFAULT NULL COMMENT '实际到达时间',
  `sign_time` datetime DEFAULT NULL COMMENT '签收时间',
  `out_expect_arrive_time` datetime DEFAULT NULL COMMENT '出库预计时间',
  `expect_distance` int(11) DEFAULT '0' COMMENT '预计里程',
  `actual_distance` int(11) DEFAULT '0' COMMENT '实际里程',
  `out_expect_distance` int(11) DEFAULT '0' COMMENT '出库预计里程',
  `expect_time` int(11) DEFAULT '0' COMMENT '预计耗时(秒)',
  `actual_time` int(11) DEFAULT '0' COMMENT '实际耗时耗时(秒)',
  `out_expect_time` int(11) DEFAULT '0' COMMENT '出库预计耗时',
  `sticker_amount` decimal(8,2) DEFAULT '0.00' COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT '0.00' COMMENT '提货费用',
  `replace_amount` decimal(16,2) DEFAULT '0.00' COMMENT '代收货款',
  `actual_replace_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '实际代收货款',
  `settlement_amount` decimal(16,2) DEFAULT '0.00' COMMENT '结算金额-运费',
  `actual_settlement_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '实际结算金额',
  `signature` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '客户签名',
  `pay_type` tinyint(4) DEFAULT '1' COMMENT '支付方式1-现金支付2-银行支付',
  `pay_picture` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付图片',
  `auth_fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '身份人姓名',
  `auth_birth_date` date DEFAULT NULL COMMENT '身份人出身年月',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='批次表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `batch_exception`
--

CREATE TABLE `batch_exception` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `batch_exception_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '异常编号',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收货方姓名',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-未处理2-已处理',
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '来源',
  `stage` tinyint(4) DEFAULT '1' COMMENT '异常阶段1-在途异常2-装货异常',
  `type` tinyint(4) DEFAULT '1' COMMENT '异常类型（在途异常：1道路2车辆3其他，装货异常1少货2货损3其他）',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '异常内容',
  `picture` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '异常图片',
  `deal_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '处理内容',
  `deal_id` int(11) DEFAULT NULL COMMENT '处理人ID(员工ID)',
  `deal_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '处理人姓名',
  `deal_time` datetime DEFAULT NULL COMMENT '处理时间',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID(创建人ID)',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名(创建人姓名)',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `car`
--

CREATE TABLE `car` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '车牌号',
  `distance` int(11) DEFAULT '0' COMMENT '里程',
  `outgoing_time` date DEFAULT NULL COMMENT '出厂日期',
  `car_brand_id` int(11) DEFAULT NULL COMMENT '车辆品牌ID',
  `car_model_id` int(11) DEFAULT NULL COMMENT '车辆型号ID',
  `ownership_type` tinyint(4) DEFAULT '1' COMMENT '类型( 1 租赁到期转私/ 2 私有/ 3 租赁到期转待定）',
  `insurance_company` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '保险公司',
  `insurance_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '保险类型',
  `month_insurance` decimal(16,2) DEFAULT '0.00' COMMENT '每月保险',
  `rent_start_date` date DEFAULT NULL COMMENT '起租时间',
  `rent_end_date` date DEFAULT NULL COMMENT '到期时间',
  `rent_month_fee` decimal(16,2) DEFAULT '0.00' COMMENT '月租金',
  `repair` tinyint(4) DEFAULT '1' COMMENT '维修自理（1 是/ 2 否）',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `relate_material` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '文件(相关材料)',
  `relate_material_list` json DEFAULT NULL COMMENT '文件列表',
  `relate_material_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '相关材料名',
  `is_locked` tinyint(4) DEFAULT '1' COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='车辆基础信息表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `car_brand`
--

CREATE TABLE `car_brand` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cn_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名',
  `en_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '品牌英文名',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `company_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `car_fee`
--

CREATE TABLE `car_fee` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `car_id` int(11) DEFAULT NULL COMMENT '车辆ID',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型1-油费2-违章3-维修',
  `amount` decimal(16,2) DEFAULT NULL COMMENT '金额',
  `attached_document` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '附件',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `car_model`
--

CREATE TABLE `car_model` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) NOT NULL COMMENT '汽车型号对应的品牌 id',
  `cn_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '品牌名',
  `en_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '品牌英文名',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `company_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `company`
--

CREATE TABLE `company` (
  `id` int(11) UNSIGNED NOT NULL,
  `company_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '公司代码',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'email',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '公司名称',
  `contacts` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '公司联系人',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '公司电话',
  `country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '所在国家',
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '公司地址',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `company_api`
--

CREATE TABLE `company_api` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `key` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'key',
  `secret` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'secret',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `company_config`
--

CREATE TABLE `company_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_rule` int(6) DEFAULT '1' COMMENT '线路规则1-邮编2-区域',
  `show_type` smallint(6) NOT NULL DEFAULT '1' COMMENT '展示方式1-全部展示2-按线路规则展示',
  `address_template_id` smallint(6) DEFAULT NULL COMMENT '地址模板ID',
  `stock_exception_verify` tinyint(4) DEFAULT '2' COMMENT '是否开启入库异常审核1-开启2-关闭',
  `weight_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '重量单位',
  `currency_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货币单位',
  `volume_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '体积单位',
  `map` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地图引擎',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `country`
--

CREATE TABLE `country` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `short` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '简称',
  `en_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '英文名称',
  `cn_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '中文名称',
  `tel` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区号',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `device`
--

CREATE TABLE `device` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '设备型号',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-在线2-离线',
  `mode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'GPS' COMMENT '模式',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `driver`
--

CREATE TABLE `driver` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户邮箱',
  `messager` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '通讯标志',
  `encrypt` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '盐值',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '姓名',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机',
  `duty_paragraph` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '税号',
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '税号',
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `lisence_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '驾照编号',
  `lisence_valid_date` date DEFAULT NULL COMMENT '有效期',
  `lisence_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '驾照类型',
  `lisence_material` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '驾照材料',
  `lisence_material_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '驾照材料名',
  `government_material` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '政府信件',
  `government_material_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '政府材料名',
  `avatar` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '银行名称',
  `iban` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IBAN',
  `bic` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'BIC',
  `is_locked` tinyint(4) DEFAULT '1' COMMENT '是否锁定1-正常2-锁定',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `crop_type` smallint(6) DEFAULT '1' COMMENT '合作类型1-雇佣2-包线'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='司机基础信息表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `driver_tour_trail`
--

CREATE TABLE `driver_tour_trail` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `driver_work`
--

CREATE TABLE `driver_work` (
  `id` int(11) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `driver_id` int(11) NOT NULL COMMENT '司机ID',
  `crop_type` int(11) DEFAULT NULL COMMENT '合作方式：1雇佣，2包线',
  `workday` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作的时间',
  `business_range` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '业务范围',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='司机网点工作信息表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `employee`
--

CREATE TABLE `employee` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `encrypt` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '盐值',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '姓名',
  `auth_group_id` int(11) DEFAULT NULL COMMENT '权限组/员工组',
  `institution_id` int(11) DEFAULT NULL COMMENT '机构ID',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `forbid_login` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '禁止登录标志',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `fee`
--

CREATE TABLE `fee` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '费用名称',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '费用编码',
  `amount` decimal(8,2) DEFAULT '0.00' COMMENT '费用',
  `level` tinyint(4) DEFAULT '1' COMMENT '级别1-系统级2-自定义',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `holiday`
--

CREATE TABLE `holiday` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '名称',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `holiday_date`
--

CREATE TABLE `holiday_date` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `holiday_id` int(11) DEFAULT NULL COMMENT '放假ID',
  `date` date DEFAULT NULL COMMENT '日期',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `institutions`
--

CREATE TABLE `institutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '机构组织名',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '机构组织电话',
  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '机构组织负责人',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '机构组织国家城市',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '机构组织负责人详细地址',
  `company_id` bigint(20) NOT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `institutions_closure`
--

CREATE TABLE `institutions_closure` (
  `ancestor` int(10) UNSIGNED NOT NULL,
  `descendant` int(10) UNSIGNED NOT NULL,
  `distance` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `kilometres_charging`
--

CREATE TABLE `kilometres_charging` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) DEFAULT NULL COMMENT '运价ID',
  `start` int(11) DEFAULT NULL COMMENT '起始公里',
  `end` int(11) DEFAULT NULL COMMENT '截止公里',
  `price` decimal(8,2) DEFAULT NULL COMMENT '加价',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `line`
--

CREATE TABLE `line` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `rule` smallint(6) DEFAULT '1' COMMENT '1-邮编2-区域',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路备注',
  `warehouse_id` int(11) DEFAULT '0' COMMENT '网点ID',
  `pickup_max_count` smallint(6) DEFAULT '1' COMMENT '取件最大订单量',
  `pie_max_count` smallint(6) DEFAULT '1' COMMENT '派件最大订单量',
  `is_increment` tinyint(4) DEFAULT '1' COMMENT '是否新增取件线路1-是2-否',
  `can_skip_batch` tinyint(4) DEFAULT '2' COMMENT '站点能否跳过1-不能2-可以',
  `order_deadline` time DEFAULT '23:59:59' COMMENT '当天下单截止时间',
  `appointment_days` smallint(6) DEFAULT '30' COMMENT '可预约天数',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `creator_id` int(11) DEFAULT NULL COMMENT '创建人ID(员工ID)',
  `creator_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '创建人姓名(员工姓名)',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='路线表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `line_area`
--

CREATE TABLE `line_area` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `coordinate_list` json DEFAULT NULL COMMENT '坐标点',
  `schedule` tinyint(4) DEFAULT NULL COMMENT '取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)',
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `is_split` tinyint(4) DEFAULT '2' COMMENT '是否拆分1-是2-否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `line_log`
--

CREATE TABLE `line_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '公司ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '线路名称',
  `user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作用户',
  `operation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作记录',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `line_range`
--

CREATE TABLE `line_range` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) NOT NULL COMMENT '线路ID',
  `post_code_start` int(11) DEFAULT NULL COMMENT '起始邮编',
  `post_code_end` int(11) DEFAULT NULL COMMENT '结束邮编',
  `schedule` tinyint(4) DEFAULT NULL COMMENT '取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)',
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='路线区间表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `material`
--

CREATE TABLE `material` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单编号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料名称',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料代码',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号/标识',
  `expect_quantity` smallint(6) DEFAULT '1' COMMENT '预计数量',
  `actual_quantity` smallint(6) DEFAULT '0' COMMENT '实际数量',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `memorandum`
--

CREATE TABLE `memorandum` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `content` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '内容',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `merchant`
--

CREATE TABLE `merchant` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户编码',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型1-个人2-货主',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '名称',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '邮箱',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `settlement_type` tinyint(4) DEFAULT '1' COMMENT '结算方式1-票结2-日结3-月结',
  `merchant_group_id` int(11) DEFAULT NULL COMMENT '货主组ID',
  `contacter` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电话',
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系地址',
  `avatar` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `merchant_api`
--

CREATE TABLE `merchant_api` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `key` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'key',
  `secret` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'secret',
  `url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '推送url',
  `white_ip_list` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '白名单IP列表',
  `status` tinyint(4) DEFAULT '1' COMMENT '推送1-是2-否',
  `recharge_status` tinyint(4) DEFAULT '2' COMMENT '充值通道1-开启2关闭',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `merchant_group`
--

CREATE TABLE `merchant_group` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '名称',
  `transport_price_id` int(11) DEFAULT NULL COMMENT '运价ID',
  `count` int(11) DEFAULT '0' COMMENT '成员数量',
  `is_default` tinyint(4) DEFAULT '2' COMMENT '是否是默认组1-是2-否',
  `additional_status` smallint(6) DEFAULT '2' COMMENT '顺带包裹状态1-启用2-禁用',
  `advance_days` smallint(6) DEFAULT '0' COMMENT '须提前下单天数',
  `appointment_days` smallint(6) DEFAULT NULL COMMENT '可预约天数',
  `delay_time` smallint(6) DEFAULT '0' COMMENT '截止时间延后时间(分钟)',
  `pickup_count` smallint(6) DEFAULT '1' COMMENT '取件次数0-手动',
  `pie_count` smallint(6) DEFAULT '1' COMMENT '派件次数0-手动',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `merchant_group_fee_config`
--

CREATE TABLE `merchant_group_fee_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_group_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `fee_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '费用编码',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `merchant_group_line_range`
--

CREATE TABLE `merchant_group_line_range` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_group_id` int(11) DEFAULT '0' COMMENT '货主ID',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `post_code_start` int(11) DEFAULT NULL COMMENT '开始邮编',
  `post_code_end` int(11) DEFAULT NULL COMMENT '结束邮编',
  `schedule` tinyint(4) DEFAULT NULL COMMENT '取件日期(0-星期日1-星期一2-星期二3-星期三4-星期四5-星期五6-星期六)',
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国家',
  `is_alone` tinyint(4) DEFAULT '2' COMMENT '是否独立取派1-是2-否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `merchant_holiday`
--

CREATE TABLE `merchant_holiday` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `holiday_id` int(11) DEFAULT NULL COMMENT '放假ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `old_order`
--

CREATE TABLE `old_order` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `execution_date` date DEFAULT NULL COMMENT '取件/派件 日期',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号',
  `express_first_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号1',
  `express_second_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号2',
  `mask_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '掩码',
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '来源',
  `list_mode` smallint(6) DEFAULT '1' COMMENT '清单模式1-简易模式2-列表模式',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型:1-取;2-派',
  `out_user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '客户单号',
  `nature` tinyint(4) DEFAULT '1' COMMENT '性质:1-包裹2-材料3-文件4-增值服务5-其他',
  `settlement_type` tinyint(4) DEFAULT '1' COMMENT '结算类型1-寄付2-到付',
  `settlement_amount` decimal(16,2) DEFAULT NULL COMMENT '结算金额',
  `replace_amount` decimal(16,2) DEFAULT NULL COMMENT '代收货款',
  `delivery` tinyint(4) DEFAULT '2' COMMENT '是否送货上门1-是2否',
  `status` smallint(6) DEFAULT '1' COMMENT '状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `exception_label` tinyint(4) DEFAULT '1' COMMENT '标签1-正常2-异常',
  `cancel_type` smallint(6) DEFAULT NULL COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-图片',
  `sender_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人姓名',
  `sender_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人电话',
  `sender_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人国家',
  `sender_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人邮编',
  `sender_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人门牌号',
  `sender_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人城市',
  `sender_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人街道',
  `sender_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人详细地址',
  `receiver_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人姓名',
  `receiver_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人电话',
  `receiver_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人国家',
  `receiver_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人邮编',
  `receiver_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人门牌号',
  `receiver_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人城市',
  `receiver_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人街道',
  `receiver_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人详细地址',
  `lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `special_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特殊事项',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `unique_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '识别码',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机电话',
  `car_id` int(11) DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '车牌号',
  `sticker_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(16,2) DEFAULT NULL COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT NULL COMMENT '提货费用',
  `out_status` tinyint(4) DEFAULT '1' COMMENT '是否可以出库1-是2-否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `old_package`
--

CREATE TABLE `old_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单编号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型1-取2-派',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹名称',
  `express_first_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号1',
  `express_second_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号2',
  `feature_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特性',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号/标识',
  `weight` decimal(8,2) DEFAULT NULL COMMENT '重量',
  `expect_quantity` smallint(6) DEFAULT '1' COMMENT '预计数量',
  `actual_quantity` smallint(6) DEFAULT '0' COMMENT '实际数量',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `sticker_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(8,2) DEFAULT NULL COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT NULL COMMENT '提货费用',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `is_auth` tinyint(4) DEFAULT '2' COMMENT '是否需要身份验证1-是2-否',
  `auth_fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '身份人姓名',
  `auth_birth_date` date DEFAULT NULL COMMENT '身份人出身年月',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE `order` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `execution_date` date DEFAULT NULL COMMENT '取件/派件 日期',
  `second_execution_date` date DEFAULT NULL COMMENT '取派订单类型中的派件日期',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号',
  `out_group_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部订单组号',
  `mask_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '掩码',
  `source` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '来源',
  `list_mode` smallint(6) DEFAULT '1' COMMENT '清单模式1-简易模式2-列表模式',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型:1-取;2-派',
  `out_user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '客户单号',
  `nature` tinyint(4) DEFAULT '1' COMMENT '性质:1-包裹2-材料3-文件4-增值服务5-其他',
  `settlement_type` tinyint(4) DEFAULT '1' COMMENT '结算类型1-寄付2-到付',
  `settlement_amount` decimal(16,2) DEFAULT '0.00' COMMENT '结算金额',
  `replace_amount` decimal(16,2) DEFAULT '0.00' COMMENT '代收货款',
  `delivery` tinyint(4) DEFAULT '2' COMMENT '是否送货上门1-是2否',
  `status` smallint(6) DEFAULT '1' COMMENT '状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `exception_label` tinyint(4) DEFAULT '1' COMMENT '标签1-正常2-异常',
  `cancel_type` smallint(6) DEFAULT NULL COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-图片',
  `second_place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人姓名',
  `second_place_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人电话',
  `second_place_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人国家',
  `second_place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人邮编',
  `second_place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人门牌号',
  `second_place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人城市',
  `second_place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人街道',
  `second_place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '发件人详细地址',
  `second_place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址二-经度',
  `second_place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址二-纬度',
  `place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人姓名',
  `place_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人电话',
  `place_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人国家',
  `place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人邮编',
  `place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人门牌号',
  `place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人城市',
  `place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人街道',
  `place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收件人详细地址',
  `place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `special_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特殊事项',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `unique_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '识别码',
  `sticker_amount` decimal(16,2) DEFAULT '0.00' COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT '0.00' COMMENT '提货费用',
  `out_status` tinyint(4) DEFAULT '1' COMMENT '是否可以出库1-是2-否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order_import_log`
--

CREATE TABLE `order_import_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `url` text COLLATE utf8mb4_unicode_ci COMMENT '下载链接',
  `status` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '状态',
  `log` longtext COLLATE utf8mb4_unicode_ci COMMENT '日志',
  `success_order` int(11) DEFAULT '0' COMMENT '导入订单成功数量',
  `fail_order` int(11) DEFAULT '0' COMMENT '导入订单失败数量',
  `total_order` int(11) DEFAULT '0' COMMENT '总订单数',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货物名称',
  `quantity` mediumint(20) DEFAULT '0' COMMENT '数量',
  `weight` decimal(8,2) DEFAULT NULL COMMENT '重量',
  `volume` decimal(8,2) DEFAULT NULL COMMENT '体积',
  `price` decimal(8,2) DEFAULT '0.00' COMMENT '单价',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order_no_rule`
--

CREATE TABLE `order_no_rule` (
  `id` int(11) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型',
  `prefix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '前缀',
  `start_index` int(11) DEFAULT '1' COMMENT '开始索引',
  `int_length` smallint(6) DEFAULT '0' COMMENT '长度',
  `start_string_index` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '开始字符',
  `string_length` smallint(6) DEFAULT '0' COMMENT '字符数量',
  `max_no` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '最大单号',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order_operation`
--

CREATE TABLE `order_operation` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `order_trail`
--

CREATE TABLE `order_trail` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `content` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '内容',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `package`
--

CREATE TABLE `package` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单编号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `second_execution_date` date DEFAULT NULL COMMENT '取派订单类型中的派件日期',
  `type` tinyint(4) DEFAULT '1' COMMENT '类型1-取2-派',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹名称',
  `express_first_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号1',
  `express_second_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号2',
  `feature_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特性',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号/标识',
  `weight` decimal(8,2) DEFAULT NULL COMMENT '重量',
  `expect_quantity` smallint(6) DEFAULT '1' COMMENT '预计数量',
  `actual_quantity` smallint(6) DEFAULT '0' COMMENT '实际数量',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `sticker_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(8,2) DEFAULT '0.00' COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT '0.00' COMMENT '提货费用',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `is_auth` tinyint(4) DEFAULT '2' COMMENT '是否需要身份验证1-是2-否',
  `auth_fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '身份人姓名',
  `auth_birth_date` date DEFAULT NULL COMMENT '身份人出身年月',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `package_no_rule`
--

CREATE TABLE `package_no_rule` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `prefix` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '前缀',
  `length` tinyint(4) DEFAULT '10' COMMENT '长度限制',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `print_template`
--

CREATE TABLE `print_template` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `type` int(11) DEFAULT '1' COMMENT '模板类型1-标准-通用模板2',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `recharge`
--

CREATE TABLE `recharge` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `recharge_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值单号',
  `tour_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名',
  `recharge_statistics_id` int(11) DEFAULT NULL COMMENT '充值统计ID',
  `transaction_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部充值单号',
  `out_user_id` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部用户ID',
  `out_user_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部用户名',
  `out_user_phone` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '外部用户电话',
  `recharge_date` date DEFAULT NULL COMMENT '充值日期',
  `recharge_time` datetime DEFAULT NULL COMMENT '充值时间',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `recharge_amount` decimal(16,2) DEFAULT '0.00' COMMENT '充值金额',
  `recharge_first_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值图片1',
  `recharge_second_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值图片2',
  `recharge_third_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值图片3',
  `signature` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值签名',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `driver_verify_status` tinyint(4) DEFAULT '1' COMMENT '验证状态1-未验证2-已验证',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-充值中2-充值失败3-充值成功',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `recharge_statistics`
--

CREATE TABLE `recharge_statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `tour_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `line_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名',
  `recharge_date` date DEFAULT NULL COMMENT '充值日期',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `total_recharge_amount` decimal(16,2) DEFAULT '0.00' COMMENT '充值金额',
  `recharge_count` int(11) DEFAULT '0' COMMENT '充值单数',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-待审核2-已审核',
  `verify_date` date DEFAULT NULL COMMENT '审核日期',
  `verify_time` datetime DEFAULT NULL COMMENT '审核时间',
  `verify_recharge_amount` decimal(16,2) DEFAULT '0.00' COMMENT '实际金额',
  `verify_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '审核备注',
  `verify_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '审核人',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `route_tracking`
--

CREATE TABLE `route_tracking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '在途编号',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `tour_driver_event_id` int(11) DEFAULT NULL COMMENT '派送事件ID',
  `time` int(11) DEFAULT '0' COMMENT '时间',
  `stop_time` int(11) NOT NULL DEFAULT '0' COMMENT '停留时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `source`
--

CREATE TABLE `source` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `source_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '来源名称',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `special_time_charging`
--

CREATE TABLE `special_time_charging` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) DEFAULT NULL COMMENT '运价ID',
  `start` time DEFAULT NULL COMMENT '起始时间',
  `end` time DEFAULT NULL COMMENT '截止时间',
  `price` decimal(8,2) DEFAULT '0.00' COMMENT '加价',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `stock`
--

CREATE TABLE `stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `tracking_order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `express_first_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹单号',
  `execution_date` date DEFAULT NULL COMMENT '预计出库日期',
  `weight` decimal(8,2) DEFAULT '0.00' COMMENT '重量',
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作人',
  `operator_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `stock_exception`
--

CREATE TABLE `stock_exception` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `stock_exception_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '入库异常编号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `express_first_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号1',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '异常内容',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-未处理2-已处理',
  `deal_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '处理内容',
  `deal_time` datetime DEFAULT NULL COMMENT '处理时间',
  `operator` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作人',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `stock_in_log`
--

CREATE TABLE `stock_in_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `tracking_order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `express_first_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹单号',
  `weight` decimal(8,2) DEFAULT '0.00' COMMENT '重量',
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作人',
  `operator_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `stock_out_log`
--

CREATE TABLE `stock_out_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `tracking_order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `express_first_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹单号',
  `weight` decimal(8,2) DEFAULT '0.00' COMMENT '重量',
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作人',
  `operator_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `test`
--

CREATE TABLE `test` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `third_party_log`
--

CREATE TABLE `third_party_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `content` varchar(10000) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '内容',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tour`
--

CREATE TABLE `tour` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT '0' COMMENT '货主ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机电话',
  `driver_rest_time` int(11) DEFAULT '0' COMMENT '司机休息时长',
  `driver_avt_id` int(11) DEFAULT NULL COMMENT '取派件AVT设备ID',
  `car_id` int(11) DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '车牌号',
  `warehouse_id` int(11) DEFAULT '0' COMMENT '网点ID',
  `warehouse_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点名称',
  `warehouse_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点电话',
  `warehouse_country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点国家',
  `warehouse_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点邮编',
  `warehouse_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点城市',
  `warehouse_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点街道',
  `warehouse_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点门牌号',
  `warehouse_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点详细地址',
  `warehouse_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点经度',
  `warehouse_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点纬度',
  `warehouse_expect_time` int(11) DEFAULT '0' COMMENT '抵达网点预计耗时',
  `warehouse_expect_distance` int(11) DEFAULT '0' COMMENT '抵达网点预计历程',
  `warehouse_expect_arrive_time` datetime DEFAULT NULL COMMENT '抵达网点预计时间',
  `status` smallint(6) DEFAULT '1' COMMENT '状态1-待分配2-已分配-3-待出库4-取派中5-取派完成',
  `begin_time` datetime DEFAULT NULL COMMENT '出库时间',
  `begin_distance` int(11) DEFAULT '0' COMMENT '起始公里',
  `begin_signature` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出库签名',
  `begin_signature_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出库备注',
  `begin_signature_first_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出库图片1',
  `begin_signature_second_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出库图片2',
  `begin_signature_third_pic` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出库图片3',
  `end_time` datetime DEFAULT NULL COMMENT '入库时间',
  `end_distance` int(11) DEFAULT '0' COMMENT '结束公里',
  `end_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '入库签名',
  `end_signature_remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '入库备注',
  `expect_distance` int(11) DEFAULT '0' COMMENT '预计里程',
  `actual_distance` int(11) DEFAULT '0' COMMENT '实际里程',
  `expect_time` int(11) DEFAULT '0' COMMENT '预计耗时(秒)',
  `actual_time` int(11) DEFAULT '0' COMMENT '实际耗时耗时(秒)',
  `expect_pickup_quantity` int(8) DEFAULT '0' COMMENT '预计取件数量(预计包裹入库数量)',
  `actual_pickup_quantity` int(8) DEFAULT '0' COMMENT '实际取件数量(实际包裹入库数量)',
  `expect_pie_quantity` int(8) DEFAULT '0' COMMENT '预计派件数量(预计包裹出库数量)',
  `actual_pie_quantity` int(8) DEFAULT '0' COMMENT '实际派件数量(实际包裹出库数量)',
  `sticker_amount` decimal(8,2) DEFAULT '0.00' COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT '0.00' COMMENT '提货费用',
  `replace_amount` decimal(16,2) DEFAULT '0.00' COMMENT '代收货款',
  `actual_replace_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '实际代收货款',
  `settlement_amount` decimal(16,2) DEFAULT '0.00' COMMENT '结算金额-运费',
  `actual_settlement_amount` decimal(16,2) NOT NULL DEFAULT '0.00' COMMENT '实际结算金额',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `actual_out_status` int(11) DEFAULT '2' COMMENT '是否已确认出库',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间',
  `lave_distance` bigint(20) DEFAULT '0' COMMENT '剩余里程数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tour_delay`
--

CREATE TABLE `tour_delay` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机名称',
  `delay_time` int(11) DEFAULT '0' COMMENT '延迟时间',
  `delay_type` tinyint(4) DEFAULT '4' COMMENT '延迟类型1-用餐休息2-交通堵塞3-更换行车路线4-其他',
  `delay_remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '延迟备注',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tour_driver_event`
--

CREATE TABLE `tour_driver_event` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `type` int(11) DEFAULT '0' COMMENT '事件类型 -- 预留',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '事件详情',
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址',
  `icon_id` int(11) DEFAULT '0' COMMENT '图标 id 预留',
  `icon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标url地址',
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '在途编号',
  `route_tracking_id` int(11) DEFAULT '0' COMMENT '对应的路线追踪中的点,预留',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tour_log`
--

CREATE TABLE `tour_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '在途的唯一标识',
  `action` tinyint(4) NOT NULL COMMENT '对在途进行的操作',
  `status` tinyint(4) DEFAULT '1' COMMENT '日志的状态, 1 为进行中 2 为已完成 3 为异常',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tour_material`
--

CREATE TABLE `tour_material` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料名称',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料代码',
  `expect_quantity` smallint(6) DEFAULT '0' COMMENT '预计数量',
  `actual_quantity` smallint(6) DEFAULT '0' COMMENT '实际数量',
  `finish_quantity` smallint(6) DEFAULT '0' COMMENT '完成数量',
  `surplus_quantity` smallint(6) DEFAULT '0' COMMENT '剩余数量',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tracking_order`
--

CREATE TABLE `tracking_order` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `out_user_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '客户单号',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '货号',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `line_id` int(11) DEFAULT NULL COMMENT '线路ID',
  `line_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '线路名称',
  `type` tinyint(4) DEFAULT '1' COMMENT '运单类型1-取2-派',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `warehouse_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-姓名',
  `warehouse_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-手机号码',
  `warehouse_country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-国家',
  `warehouse_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-邮编',
  `warehouse_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-门牌号',
  `warehouse_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-城市',
  `warehouse_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-街道',
  `warehouse_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-地址',
  `warehouse_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-经度',
  `warehouse_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '网点-纬度',
  `place_fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-姓名',
  `place_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-手机号码',
  `place_country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-国家',
  `place_post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-邮编',
  `place_house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-门牌号',
  `place_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-城市',
  `place_street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-街道',
  `place_address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-地址',
  `place_lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-经度',
  `place_lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '地址-纬度',
  `driver_id` int(11) DEFAULT NULL COMMENT '司机ID',
  `driver_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机姓名',
  `driver_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '司机电话',
  `car_id` int(11) DEFAULT NULL COMMENT '车辆ID',
  `car_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '车牌号',
  `status` smallint(6) DEFAULT '1' COMMENT '运单状态:1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `out_status` smallint(6) DEFAULT '1' COMMENT '是否可出库:1-是2-否',
  `exception_label` tinyint(4) DEFAULT '1' COMMENT '标签1-正常2-异常',
  `cancel_type` smallint(6) DEFAULT NULL COMMENT '取消取派-类型1-派送失败(客户不在家)2-另约时间3-其他',
  `cancel_remark` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-具体内容',
  `cancel_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取消取派-图片',
  `mask_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '掩码',
  `special_remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特殊事项',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tracking_order_material`
--

CREATE TABLE `tracking_order_material` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单号',
  `type` tinyint(4) DEFAULT '1' COMMENT '运单类型1-取件2-派件',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料名称',
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '材料标识',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部标识',
  `expect_quantity` int(11) DEFAULT '1' COMMENT '预计数量',
  `actual_quantity` int(11) DEFAULT '0' COMMENT '实际数量',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tracking_order_package`
--

CREATE TABLE `tracking_order_package` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `tour_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '取件线路编号',
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '站点编号',
  `tracking_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '运单编号',
  `order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `execution_date` date DEFAULT NULL COMMENT '取派日期',
  `type` tinyint(4) DEFAULT '1' COMMENT '运单类型1-取件2-派件',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包裹名称',
  `express_first_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号1',
  `express_second_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '快递单号2',
  `feature_logo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特性',
  `out_order_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '外部标识',
  `weight` decimal(8,2) DEFAULT '0.00' COMMENT '重量',
  `expect_quantity` int(11) DEFAULT '1' COMMENT '预计数量',
  `actual_quantity` int(11) DEFAULT '0' COMMENT '实际数量',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-待分配2-已分配3-待出库4-取派中5-已签收6-取消取派7-收回站',
  `sticker_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '贴单号',
  `sticker_amount` decimal(8,2) DEFAULT '0.00' COMMENT '贴单费用',
  `delivery_amount` decimal(8,2) DEFAULT '0.00' COMMENT '提货费用',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `is_auth` tinyint(4) DEFAULT '2' COMMENT '是否需要身份验证1-是2-否',
  `auth_fullname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '身份人姓名',
  `auth_birth_date` date DEFAULT NULL COMMENT '身份人出身年月',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `tracking_order_trail`
--

CREATE TABLE `tracking_order_trail` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `merchant_id` int(11) DEFAULT NULL COMMENT '货主ID',
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `tracking_order_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单号',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '内容',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `transport_price`
--

CREATE TABLE `transport_price` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '名称',
  `starting_price` decimal(8,2) DEFAULT '0.00' COMMENT '起步价',
  `remark` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '特别说明',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态1-启用2-禁用',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `version`
--

CREATE TABLE `version` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT '0' COMMENT '公司ID',
  `uploader_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '上传者邮箱',
  `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT 'TMS' COMMENT '名称',
  `version` int(11) DEFAULT '1' COMMENT '版本号',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态（1-强制更新，2可选更新）',
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下载链接',
  `change_log` longtext COLLATE utf8mb4_unicode_ci COMMENT '更新日志',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '网点名称',
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电话',
  `country` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '国家',
  `post_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `house_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '门牌号',
  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '城市',
  `street` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '街道',
  `address` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '发件人详细地址',
  `lon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
  `lat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `weight_charging`
--

CREATE TABLE `weight_charging` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `transport_price_id` int(11) DEFAULT NULL COMMENT '运价ID',
  `start` int(11) DEFAULT NULL COMMENT '起始重量',
  `end` int(11) DEFAULT NULL COMMENT '截止重量',
  `price` decimal(8,2) DEFAULT '0.00' COMMENT '加价',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `worker`
--

CREATE TABLE `worker` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(11) DEFAULT NULL COMMENT '公司ID',
  `to_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户ID',
  `data` json DEFAULT NULL COMMENT '数据',
  `company_auth` smallint(6) DEFAULT '1' COMMENT '是否需要验证公司权限1-是2-否',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_package`
--
ALTER TABLE `additional_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `package_no` (`package_no`),
  ADD KEY `execution_date` (`execution_date`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `all_address` (`merchant_id`,`place_country`,`place_fullname`,`place_phone`,`place_post_code`,`place_house_number`,`place_city`,`place_street`,`place_address`),
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`) USING BTREE;

--
-- Indexes for table `address_template`
--
ALTER TABLE `address_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_times`
--
ALTER TABLE `api_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `batch_no` (`batch_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `line_id` (`line_id`) USING BTREE,
  ADD KEY `execution_date` (`execution_date`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `place_post_code` (`place_post_code`),
  ADD KEY `line_name` (`line_name`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `driver_name` (`driver_name`);

--
-- Indexes for table `batch_exception`
--
ALTER TABLE `batch_exception`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `batch_exception_no` (`batch_exception_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `batch_no` (`batch_no`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `car_no` (`car_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `is_locked` (`is_locked`);

--
-- Indexes for table `car_brand`
--
ALTER TABLE `car_brand`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `cn_name` (`cn_name`,`company_id`) USING BTREE,
  ADD UNIQUE KEY `en_name` (`en_name`,`company_id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `car_fee`
--
ALTER TABLE `car_fee`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `car_id` (`car_id`) USING BTREE;

--
-- Indexes for table `car_model`
--
ALTER TABLE `car_model`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `brand_id` (`brand_id`,`cn_name`,`company_id`) USING BTREE,
  ADD UNIQUE KEY `brand_id_2` (`brand_id`,`en_name`,`company_id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_code` (`company_code`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `company_api`
--
ALTER TABLE `company_api`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_id` (`company_id`);

--
-- Indexes for table `company_config`
--
ALTER TABLE `company_config`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_cn_name` (`company_id`,`cn_name`) USING BTREE,
  ADD UNIQUE KEY `company_en_name` (`company_id`,`en_name`) USING BTREE,
  ADD UNIQUE KEY `company_id_2` (`company_id`,`short`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number_company` (`number`,`company_id`) USING BTREE,
  ADD KEY `company_id` (`company_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `phone` (`phone`) USING BTREE,
  ADD UNIQUE KEY `company_id_fullname` (`company_id`,`fullname`),
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `is_locked` (`is_locked`);

--
-- Indexes for table `driver_tour_trail`
--
ALTER TABLE `driver_tour_trail`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `driver_id` (`driver_id`) USING BTREE;

--
-- Indexes for table `driver_work`
--
ALTER TABLE `driver_work`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `driver_id` (`driver_id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `auth_group_id` (`auth_group_id`),
  ADD KEY `fullname` (`fullname`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee`
--
ALTER TABLE `fee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`company_id`,`name`),
  ADD UNIQUE KEY `code` (`company_id`,`code`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`company_id`,`name`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `holiday_date`
--
ALTER TABLE `holiday_date`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `holiday_id_name` (`holiday_id`,`date`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `institutions_company_id_index` (`company_id`) USING BTREE,
  ADD KEY `institutions_parent_index` (`parent`) USING BTREE;

--
-- Indexes for table `institutions_closure`
--
ALTER TABLE `institutions_closure`
  ADD PRIMARY KEY (`ancestor`,`descendant`) USING BTREE;

--
-- Indexes for table `kilometres_charging`
--
ALTER TABLE `kilometres_charging`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `transport_price_id` (`transport_price_id`) USING BTREE;

--
-- Indexes for table `line`
--
ALTER TABLE `line`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id_name` (`company_id`,`name`) USING BTREE,
  ADD KEY `name` (`name`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `rule` (`rule`) USING BTREE,
  ADD KEY `status` (`status`);

--
-- Indexes for table `line_area`
--
ALTER TABLE `line_area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `schedule` (`schedule`),
  ADD KEY `country` (`country`);

--
-- Indexes for table `line_log`
--
ALTER TABLE `line_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `line_name` (`line_name`) USING BTREE;

--
-- Indexes for table `line_range`
--
ALTER TABLE `line_range`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `line_id` (`line_id`) USING BTREE;

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_no_code_out` (`order_no`,`code`,`out_order_no`),
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `order_no_2` (`order_no`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`);

--
-- Indexes for table `memorandum`
--
ALTER TABLE `memorandum`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `driver_id` (`driver_id`) USING BTREE;

--
-- Indexes for table `merchant`
--
ALTER TABLE `merchant`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id_name` (`company_id`,`name`) USING BTREE,
  ADD UNIQUE KEY `company_id` (`company_id`,`email`) USING BTREE,
  ADD KEY `company_id_2` (`company_id`) USING BTREE,
  ADD KEY `status` (`status`),
  ADD KEY `merchant_group_id` (`merchant_group_id`);

--
-- Indexes for table `merchant_api`
--
ALTER TABLE `merchant_api`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_merchant` (`company_id`,`merchant_id`) USING BTREE,
  ADD UNIQUE KEY `key` (`key`) USING BTREE,
  ADD UNIQUE KEY `secret` (`secret`) USING BTREE,
  ADD KEY `status` (`status`);

--
-- Indexes for table `merchant_group`
--
ALTER TABLE `merchant_group`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id_name` (`company_id`,`name`) USING BTREE,
  ADD UNIQUE KEY `name` (`name`,`transport_price_id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `merchant_group_fee_config`
--
ALTER TABLE `merchant_group_fee_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `merchant_id_fee_code` (`merchant_group_id`,`fee_code`) USING BTREE,
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `merchant_group_line_range`
--
ALTER TABLE `merchant_group_line_range`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_group_id`) USING BTREE,
  ADD KEY `line_id` (`line_id`) USING BTREE,
  ADD KEY `post_code_start` (`post_code_start`) USING BTREE,
  ADD KEY `post_code_end` (`post_code_end`) USING BTREE,
  ADD KEY `schedule` (`schedule`) USING BTREE,
  ADD KEY `country` (`country`) USING BTREE;

--
-- Indexes for table `merchant_holiday`
--
ALTER TABLE `merchant_holiday`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `merchant_id_holiday` (`merchant_id`,`holiday_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `old_order`
--
ALTER TABLE `old_order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `execution_date` (`execution_date`) USING BTREE,
  ADD KEY `batch_no` (`batch_no`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`) USING BTREE;

--
-- Indexes for table `old_package`
--
ALTER TABLE `old_package`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `batch_no` (`batch_no`) USING BTREE,
  ADD KEY `order_no` (`order_no`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`) USING BTREE;

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `execution_date` (`execution_date`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `out_order_no` (`out_order_no`),
  ADD KEY `out_group_order_no` (`out_group_order_no`),
  ADD KEY `tracking_order_no` (`tracking_order_no`),
  ADD KEY `type` (`type`),
  ADD KEY `out_user_id` (`out_user_id`);

--
-- Indexes for table `order_import_log`
--
ALTER TABLE `order_import_log`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order_no_name` (`order_no`,`name`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `order_no` (`order_no`) USING BTREE;

--
-- Indexes for table `order_no_rule`
--
ALTER TABLE `order_no_rule`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_type` (`company_id`,`type`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `status` (`status`);

--
-- Indexes for table `order_operation`
--
ALTER TABLE `order_operation`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `order_no` (`order_no`) USING BTREE;

--
-- Indexes for table `order_trail`
--
ALTER TABLE `order_trail`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `order_no` (`order_no`) USING BTREE;

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `order_no` (`order_no`) USING BTREE,
  ADD KEY `type` (`type`) USING BTREE,
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `express_first_no` (`express_first_no`),
  ADD KEY `express_second_no` (`express_second_no`) USING BTREE;

--
-- Indexes for table `package_no_rule`
--
ALTER TABLE `package_no_rule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_company` (`name`,`company_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `print_template`
--
ALTER TABLE `print_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recharge`
--
ALTER TABLE `recharge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `recharge_no` (`recharge_no`),
  ADD KEY `status` (`status`),
  ADD KEY `out_user_id` (`out_user_id`),
  ADD KEY `driver_name` (`driver_name`),
  ADD KEY `recharge_statistics_id` (`recharge_statistics_id`);

--
-- Indexes for table `recharge_statistics`
--
ALTER TABLE `recharge_statistics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `union` (`company_id`,`merchant_id`,`recharge_date`,`driver_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `recharge_date` (`recharge_date`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `status` (`status`),
  ADD KEY `driver_name` (`driver_name`);

--
-- Indexes for table `route_tracking`
--
ALTER TABLE `route_tracking`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `driver_id` (`driver_id`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE;

--
-- Indexes for table `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `special_time_charging`
--
ALTER TABLE `special_time_charging`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `transport_price_id` (`transport_price_id`) USING BTREE;

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `express_first_no` (`express_first_no`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `line_id` (`line_id`);

--
-- Indexes for table `stock_exception`
--
ALTER TABLE `stock_exception`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_exception_no` (`stock_exception_no`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `express_first_no` (`express_first_no`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `status` (`status`),
  ADD KEY `order_no` (`order_no`);

--
-- Indexes for table `stock_in_log`
--
ALTER TABLE `stock_in_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `express_first_no` (`express_first_no`),
  ADD KEY `execution_date` (`execution_date`),
  ADD KEY `tracking_order_no` (`tracking_order_no`);

--
-- Indexes for table `stock_out_log`
--
ALTER TABLE `stock_out_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `express_first_no` (`express_first_no`),
  ADD KEY `execution_date` (`execution_date`),
  ADD KEY `tracking_order_no` (`tracking_order_no`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `third_party_log`
--
ALTER TABLE `third_party_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `order_no` (`order_no`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `line_id` (`line_id`) USING BTREE,
  ADD KEY `driver_id` (`driver_id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `execution_date` (`execution_date`) USING BTREE,
  ADD KEY `car_id` (`car_id`) USING BTREE,
  ADD KEY `line_name` (`line_name`);

--
-- Indexes for table `tour_delay`
--
ALTER TABLE `tour_delay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `delay_type` (`delay_type`),
  ADD KEY `execuiton_date` (`execution_date`),
  ADD KEY `line_name` (`line_name`);

--
-- Indexes for table `tour_driver_event`
--
ALTER TABLE `tour_driver_event`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `tour_no` (`tour_no`) USING BTREE,
  ADD KEY `batch_no` (`batch_no`);

--
-- Indexes for table `tour_log`
--
ALTER TABLE `tour_log`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `tour_material`
--
ALTER TABLE `tour_material`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `tour_no_code` (`tour_no`,`code`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `tracking_order`
--
ALTER TABLE `tracking_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_order_no` (`tracking_order_no`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `execution_date` (`execution_date`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `batch_no` (`batch_no`),
  ADD KEY `tour_no` (`tour_no`),
  ADD KEY `status` (`status`),
  ADD KEY `type` (`type`),
  ADD KEY `exception_label` (`exception_label`),
  ADD KEY `out_user_id` (`out_user_id`);

--
-- Indexes for table `tracking_order_material`
--
ALTER TABLE `tracking_order_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `execution_date` (`execution_date`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `batch_no` (`batch_no`),
  ADD KEY `tour_no` (`tour_no`),
  ADD KEY `type` (`type`),
  ADD KEY `code` (`code`),
  ADD KEY `tracking_order_no` (`tracking_order_no`) USING BTREE;

--
-- Indexes for table `tracking_order_package`
--
ALTER TABLE `tracking_order_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `execution_date` (`execution_date`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `batch_no` (`batch_no`),
  ADD KEY `tour_no` (`tour_no`),
  ADD KEY `status` (`status`),
  ADD KEY `type` (`type`),
  ADD KEY `express_first_no` (`express_first_no`),
  ADD KEY `tracking_order_no` (`tracking_order_no`) USING BTREE;

--
-- Indexes for table `tracking_order_trail`
--
ALTER TABLE `tracking_order_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `order_no` (`order_no`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `tracking_order_no` (`tracking_order_no`);

--
-- Indexes for table `transport_price`
--
ALTER TABLE `transport_price`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id_name` (`company_id`,`name`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `status` (`status`);

--
-- Indexes for table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `company_id_2` (`company_id`,`name`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE;

--
-- Indexes for table `weight_charging`
--
ALTER TABLE `weight_charging`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `company_id` (`company_id`) USING BTREE,
  ADD KEY `transport_price_id` (`transport_price_id`) USING BTREE;

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_id` (`to_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `additional_package`
--
ALTER TABLE `additional_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11450;

--
-- 使用表AUTO_INCREMENT `address`
--
ALTER TABLE `address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16249;

--
-- 使用表AUTO_INCREMENT `address_template`
--
ALTER TABLE `address_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `api_times`
--
ALTER TABLE `api_times`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- 使用表AUTO_INCREMENT `batch`
--
ALTER TABLE `batch`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=62577;

--
-- 使用表AUTO_INCREMENT `batch_exception`
--
ALTER TABLE `batch_exception`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=38;

--
-- 使用表AUTO_INCREMENT `car_brand`
--
ALTER TABLE `car_brand`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `car_fee`
--
ALTER TABLE `car_fee`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `car_model`
--
ALTER TABLE `car_model`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `company_api`
--
ALTER TABLE `company_api`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `company_config`
--
ALTER TABLE `company_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `country`
--
ALTER TABLE `country`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `device`
--
ALTER TABLE `device`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- 使用表AUTO_INCREMENT `driver`
--
ALTER TABLE `driver`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=64;

--
-- 使用表AUTO_INCREMENT `driver_tour_trail`
--
ALTER TABLE `driver_tour_trail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `driver_work`
--
ALTER TABLE `driver_work`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=780;

--
-- 使用表AUTO_INCREMENT `fee`
--
ALTER TABLE `fee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `holiday`
--
ALTER TABLE `holiday`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `holiday_date`
--
ALTER TABLE `holiday_date`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 使用表AUTO_INCREMENT `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `kilometres_charging`
--
ALTER TABLE `kilometres_charging`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `line`
--
ALTER TABLE `line`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=1096;

--
-- 使用表AUTO_INCREMENT `line_area`
--
ALTER TABLE `line_area`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `line_log`
--
ALTER TABLE `line_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `line_range`
--
ALTER TABLE `line_range`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4576;

--
-- 使用表AUTO_INCREMENT `material`
--
ALTER TABLE `material`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10077;

--
-- 使用表AUTO_INCREMENT `memorandum`
--
ALTER TABLE `memorandum`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `merchant`
--
ALTER TABLE `merchant`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `merchant_api`
--
ALTER TABLE `merchant_api`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `merchant_group`
--
ALTER TABLE `merchant_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `merchant_group_fee_config`
--
ALTER TABLE `merchant_group_fee_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `merchant_group_line_range`
--
ALTER TABLE `merchant_group_line_range`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5425;

--
-- 使用表AUTO_INCREMENT `merchant_holiday`
--
ALTER TABLE `merchant_holiday`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `old_order`
--
ALTER TABLE `old_order`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=51423;

--
-- 使用表AUTO_INCREMENT `old_package`
--
ALTER TABLE `old_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170345;

--
-- 使用表AUTO_INCREMENT `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=63767;

--
-- 使用表AUTO_INCREMENT `order_import_log`
--
ALTER TABLE `order_import_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `order_no_rule`
--
ALTER TABLE `order_no_rule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- 使用表AUTO_INCREMENT `order_operation`
--
ALTER TABLE `order_operation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `order_trail`
--
ALTER TABLE `order_trail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=431367;

--
-- 使用表AUTO_INCREMENT `package`
--
ALTER TABLE `package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215421;

--
-- 使用表AUTO_INCREMENT `package_no_rule`
--
ALTER TABLE `package_no_rule`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `print_template`
--
ALTER TABLE `print_template`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `recharge`
--
ALTER TABLE `recharge`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1053;

--
-- 使用表AUTO_INCREMENT `recharge_statistics`
--
ALTER TABLE `recharge_statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=610;

--
-- 使用表AUTO_INCREMENT `route_tracking`
--
ALTER TABLE `route_tracking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346949;

--
-- 使用表AUTO_INCREMENT `source`
--
ALTER TABLE `source`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `special_time_charging`
--
ALTER TABLE `special_time_charging`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=520;

--
-- 使用表AUTO_INCREMENT `stock_exception`
--
ALTER TABLE `stock_exception`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `stock_in_log`
--
ALTER TABLE `stock_in_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=519;

--
-- 使用表AUTO_INCREMENT `stock_out_log`
--
ALTER TABLE `stock_out_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=481;

--
-- 使用表AUTO_INCREMENT `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `third_party_log`
--
ALTER TABLE `third_party_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210289;

--
-- 使用表AUTO_INCREMENT `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2920;

--
-- 使用表AUTO_INCREMENT `tour_delay`
--
ALTER TABLE `tour_delay`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `tour_driver_event`
--
ALTER TABLE `tour_driver_event`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124047;

--
-- 使用表AUTO_INCREMENT `tour_log`
--
ALTER TABLE `tour_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17718;

--
-- 使用表AUTO_INCREMENT `tour_material`
--
ALTER TABLE `tour_material`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5677;

--
-- 使用表AUTO_INCREMENT `tracking_order`
--
ALTER TABLE `tracking_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60408;

--
-- 使用表AUTO_INCREMENT `tracking_order_material`
--
ALTER TABLE `tracking_order_material`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8654;

--
-- 使用表AUTO_INCREMENT `tracking_order_package`
--
ALTER TABLE `tracking_order_package`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200256;

--
-- 使用表AUTO_INCREMENT `tracking_order_trail`
--
ALTER TABLE `tracking_order_trail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94168;

--
-- 使用表AUTO_INCREMENT `transport_price`
--
ALTER TABLE `transport_price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- 使用表AUTO_INCREMENT `version`
--
ALTER TABLE `version`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=70;

--
-- 使用表AUTO_INCREMENT `weight_charging`
--
ALTER TABLE `weight_charging`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- 使用表AUTO_INCREMENT `worker`
--
ALTER TABLE `worker`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
