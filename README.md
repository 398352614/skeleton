# NLE-TMS后端开发文档

## 一、项目介绍
### 1. 概述
  TMS全称Transportation Management System，即运输管理系统。TMS是恩尔伊科技公司重要开发运营项目之一，主要承接荷兰快递公司的快递业务以及欧亚商城的送货业务。  
### 2. 技术介绍
该项目于2019年年末进行筹备，2020年1月进行正式开发，首要需求为满足荷兰方的运输管理需求，分担ERP项目的部分功能，其次计划将该产品推向国内，服务国内小型运输公司。本项目采取前后端分离模式，后端基于php+mysql+redis利用Laravel框架开发，前端基于css+javascript利用vue框架开发。  
php版本：7.4.6  
mysql版本：5.7.11  
redis版本：  
laravel版本：6.18.27  

### 3. 主要开发人员
  产品经理：**曾纯**，**宁羚**  
  后端主程序员：**龙放耀**  
  后端程序员：**胡洋铭**，**唐睦州**，**霍张启**  
  前端主程序员：**何家群**  
  前端程序员：**苏宇**，**方丹**  
### 4. 相关材料
  
正式服管理员端地址：[https://tms-admin.eutechne.com](https://tms-admin.eutechne.com)  
正式服商户端地址：[https://tms-business.eutechne.com](https://tms-business.eutechne.com)  
开发服管理员端地址：[https://dev-tms-admin.nle-tech.com](https://dev-tms-admin.nle-tech.com)  
开发服商户端地址：[https://dev-tms-business.nle-tech.com](https://dev-tms-business.nle-tech.com)  
  
## 二、项目部署
### 1.搭建环境
#### 1.1 安装docker
Docker 是一个开源的应用容器引擎，可以让开发者打包他们的应用以及依赖包到一个轻量级、可移植的容器中，然后发布到任何流行的 Linux 机器上，也可以实现虚拟化。本项目的开发服，正式服均是由docker搭建环境。开发服与正式服的服务器均是CentOS系统，首先通过相关教程将Docker安装至服务器。  
安装方法见**1.2** docker-compose的安装方法。

#### 1.2 安装docker-compose
docker-compose 是用于定义和运行多容器 Docker 应用程序的工具。通过 docker-compose，您可以使用 YML 文件来配置应用程序需要的所有服务。然后，使用一个命令，就可以从 YML 文件配置中创建并启动所有服务。  
安装方法地址：[https://www.runoob.com/docker/docker-compose.html](https://www.runoob.com/docker/docker-compose.html)
#### 1.3 配置&启动服务
从github上获取配置文件。
```gitexclude
git clone https://github.com/balloontmz/php_docker_env;
```
构建容器。
```
docker-compose build;
```
启动服务。
```
docker-compose up -d;
```
查看运行情况。
```
docker-compose ps；
```
如果运行正常，以下状态均应为up。
>tms_dev_env_crontab_1  
tms_dev_env_mysql_1  
tms_dev_env_nginx_1  
tms_dev_env_php-cli_1  
tms_dev_env_php-fpm_1  
tms_dev_env_phpmyadmin_1  
tms_dev_env_redis_1  

按顺序分别为定时任务，数据库，服务器，php命令行，php扩展，数据库网页版，缓存。其中还有supervisor及wkhtmltopdf安装在php-cli的虚拟容器中，因为某些原因并未单独拿出来作为容器管理。

### 2. 项目部署与维护
该项目利用github进行代码管理，放置在nle-tech的仓库中，其中正式服分支为deploy，开发服分支为develop。  
#### 2.1 安装git
访问git官网下载git。  
下载地址：[https://git-scm.com/downloads](https://git-scm.com/downloads)
#### 2.2 从github上下载项目工程
 github仓库地址：[https://github.com/nletech/tms-api](https://github.com/nletech/tms-api)
通过克隆，下载到服务器上。
```gitexclude
git clone https://github.com/nletech/tms-api/tree/deploy;
```
#### 2.3 更新
以开发服为例，在本地克隆后，在项目目录下，对代码进行了任何更改，可首先将代码上传到开发服仓库上。
```
git push 
```
然后在开发服进行拉取
```gitexclude
git pull
```
如果有通过数据迁移文件对数据库修改，还需进入容器，执行以下命令进行数据迁移。
````gitexclude
docker-compose exec php-cli bash
cd api
php aritsan migrate
````
如果有更改队列任务，还需进入容器，执行以下命令重启队列。
```gitexclude
docker-compose exec php-cli bash
cd api
supervisorctl restart all
```
### 3. 各项配置
由于某些原因，supervisor并未以容器的形式进行管控，而是在php-cli容器中进行了安装，仅此需要解决supervisor配置问题，每次重启后均需重新配置。
进入docker目录，执行以下命令：
``` 
docker-compose exec php-cli bash
cd api
sudo supervisord -c /etc/supervisor/supervisord.conf
```
## 三、使用说明
### 1. 项目结构
#### 1.1 内部连接
TMS项目后端与管理员端，商户端，司机端进行连接，其中管理员端与商户端为网页端，司机端为安卓端。  
开发服管理员端接口地址：[https://dev-tms.nle-tech.com/api/admin](https://dev-tms.nle-tech.com/api/admin)  
正式服管理员端接口地址：[https://tms-admin.eutechne.com/api/admin](https://tms-admin.eutechne.com/api/admin)  
开发服商户端接口地址：[https://dev-tms.nle-tech.com/api/merchant](https://dev-tms.nle-tech.com/api/merchant)  
正式服商户端接口地址：[https://tms-admin.eutechne.com/api/merchant](https://tms-admin.eutechne.com/api/merchant)  
开发服司机端接口地址：[https://dev-tms.nle-tech.com/api/driver](https://dev-tms.nle-tech.com/api/driver)  
正式服司机端接口地址：[https://tms-admin.eutechne.com/api/driver](https://tms-admin.eutechne.com/api/driver)  
#### 1.2 外部连接
TMS项目对外提供第三方接口，供对接系统使用，目前仅对接ERP和欧亚商城。
开发服第三方接口地址：[https://dev-tms.nle-tech.com/api/driver](https://dev-tms.nle-tech.com/api/driver)  
正式服第三方接口地址：[https://tms-admin.eutechne.com/api/driver](https://tms-admin.eutechne.com/api/driver)  
### 2. 业务流程
### 3. 特殊说明
