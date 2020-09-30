# NLE-TMS后端开发文档

## 一、项目介绍
### 1.概述
  TMS全称Transportation Management System，即运输管理系统。TMS是恩尔伊科技公司重要开发运营项目之一，主要承接荷兰快递公司的快递业务以及欧亚商城的送货业务。  
### 2.技术介绍
该项目于2019年年末进行筹备，2020年1月进行正式开发，首要需求为满足荷兰方的运输管理需求，分担ERP项目的部分功能，其次计划将该产品推向国内，服务国内小型运输公司。本项目采取前后端分离模式，后端基于php+mysql+redis利用Laravel框架开发，前端基于css+javascript利用vue框架开发。  
php版本：7.4.6  
mysql版本：5.7.11  
redis版本：  
laravel版本：6.18.27  

### 3.主要开发人员
  产品经理：**曾纯**，**宁羚**  
  后端主程序员：**龙放耀**  
  后端程序员：**胡洋铭**，**唐睦州**，**霍张启**  
  前端主程序员：**何家群**  
  前端程序员：**苏宇**，**方丹**  
  ### 4.相关材料
  
正式服管理员端地址：[https://tms-admin.eutechne.com](https://tms-admin.eutechne.com)  
正式服商户端地址：[https://dev-tms-business.nle-tech.com](https://dev-tms-business.nle-tech.com)  
开发服管理员端地址：[https://dev-tms-admin.nle-tech.com](https://dev-tms-admin.nle-tech.com)  
开发服商户端地址：[https://dev-tms-business.nle-tech.com](https://dev-tms-business.nle-tech.com)  
  
## 二、项目部署
### 1.搭建环境
利用docker构建虚拟环境，
每次修改docker中的配置文件时，都需要重构Docker容器。
```
docker-compose build;
```
重构Docker容器后，需要启动或重启服务。
```
docker-compose up -d;
```
### 2.项目部署与维护
利用github进行代码管理，利用composer下载laravel框架。  
####2.1 安装git
访问git官网下载git。  
下载地址：[https://git-scm.com/downloads](https://git-scm.com/downloads)
####2.2从github上下载项目工程。
 github仓库地址：[https://github.com/nletech/tms-api](https://github.com/nletech/tms-api)
 正式服分支为deploy,复制github下载地址,利用git clone下载项目工程。  
 ```gitexclude
git clone https://github.com/nletech/tms-api/tree/deploy;
 ```
### 3.各项配置
改写配置文件，

## 三、使用说明
