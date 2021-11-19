# NLE-TMS后端技术文档
[1.项目介绍](#1)  
[2.项目部署](#2)  
[3.初始化项目](#3)   

<h2 id='1'> 一、项目介绍 </h2>
### 1. 概述
  TMS全称Transportation Management System，即运输管理系统。TMS是恩尔伊科技公司重要开发运营项目之一，主要承接荷兰快递公司的快递业务以及欧亚商城的送货业务。  
### 2. 技术介绍
该项目于2019年年末进行筹备，2020年1月进行正式开发，首要需求为满足荷兰方的运输管理需求，分担ERP项目的部分功能，其次计划将该产品推向国内，服务国内小型运输公司。本项目采取前后端分离模式，后端基于php+mysql+redis利用Laravel框架开发，前端基于css+javascript利用vue框架开发。  
php版本：7.4.6  
mysql版本：5.7.11  
redis版本：  1
laravel版本：6.18.27  

### 3. 主要开发人员
  产品经理：**曾纯**，**宁羚**  
  后端主程序员：**龙放耀**  
  后端程序员：**胡洋铭**，**唐睦州**，**霍张启**  
  前端主程序员：**何家群**  
  前端程序员：**苏宇**，**方丹**  
### 4. 相关材料
  
正式服管理员端地址：[https://tms-admin.eutechne.com](https://tms-admin.eutechne.com)  
正式服货主端地址：[https://tms-business.eutechne.com](https://tms-business.eutechne.com)  
开发服管理员端地址：[https://dev-tms-admin.nle-tech.com](https://dev-tms-admin.nle-tech.com)  
开发服货主端地址：[https://dev-tms-business.nle-tech.com](https://dev-tms-business.nle-tech.com)  
  
<h2 id='2'> 项目部署 </h2>  
### 1.搭建环境
#### 1.1 安装docker
Docker 是一个开源的应用容器引擎，可以让开发者打包他们的应用以及依赖包到一个轻量级、可移植的容器中，然后发布到任何流行的 Linux 机器上，也可以实现虚拟化。本项目的开发服，正式服均是由docker搭建环境。开发服与正式服的服务器均是CentOS系统，首先通过相关教程将Docker安装至服务器。  
安装方法见**1.2** docker-compose的安装方法。

#### 1.2 安装docker-compose工具
docker-compose 是用于定义和运行多容器 Docker 应用程序的工具。通过 docker-compose，您可以使用 YML 文件来配置应用程序需要的所有服务。然后，使用一个命令，就可以从 YML 文件配置中创建并启动所有服务。  
安装方法地址：[https://www.runoob.com/docker/docker-compose.html](https://www.runoob.com/docker/docker-compose.html)
#### 1.3 配置&启动服务
从github上获取配置文件。
```gitexclude
git clone https://github.com/balloontmz/php_docker_env;
```
打开php_docker_env文件夹下.env文件，进行相关配置。  
>NGINX_HTTPS_PORT=433  #https端口  
 NGINX_HTTP_PORT=80  #http端口  
 USER_NETWORK= #网络  （将其他各个端口用下划线连接）  
 WEB_ROOT=../www #项目目录  
 MYSQL_PORT=3306 #数据库端口  
 REDIS_PORT=6379 #redis端口  
 PHPMYADMIN_PORT= #数据库管理后台端口  

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
该项目利用github进行代码管理，放置在nle-tech的网点中，其中正式服分支为deploy，开发服分支为develop。  
#### 2.1 安装git
访问git官网下载git。  
下载地址：[https://git-scm.com/downloads](https://git-scm.com/downloads)
#### 2.2 从github上下载项目工程
 github网点地址：[https://github.com/nletech/tms-api](https://github.com/nletech/tms-api)
通过克隆，下载到服务器上。
注意：应在docker环境文件夹同层新建www文件夹，再在www文件夹内git clone克隆项目代码。  
```gitexclude
git clone https://github.com/nletech/tms-api/tree/deploy;
```
#### 2.3 更新
以开发服为例，在本地克隆后，在项目目录下，对代码进行了任何更改，可首先将代码上传到开发服网点上。
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
php artisan migrate
````
如果有更改队列任务，还需进入容器，执行以下命令重启队列。
```gitexclude
docker-compose exec php-cli bash
cd api
supervisorctl restart all
```

### 2. 初始化项目
#### 3.1 nginx配置
在服务器上/etc/nginx/conf.d/文件夹中，新建.conf配置文件，在service下加上以下配置：  
```
client_max_body_size 50M;
client_body_buffer_size 1024M;
```  
#### 3.2 框架初始化
在根目录下，根据.env.example编写.env文件。  
生成APP秘钥。  
```
php artisan key:generate
```
生成软连接
```
php artisan storage:link
```
composer 自我更新
```gitexclude
composer selfupdate
```

替换文件已解决php版本字符问题，文件在docker根目录下。
原因是7.4版本php不支持花括号{}取数组元素，只支持中括号[]取数组元素。因此需要将环境文件夹根目录下的DNS1D.php文件复制并覆盖以下文件。
```
tms-api/www/api/vendor/milon/barcode/src/Milon/Barcode/DNS1D.php
```

composer 安装依赖
```gitexclude
composer install
```
#### 3.3 数据库初始化
```
php artisan migrate
```
#### 3.4 缓存初始化
地址模板缓存  
```OrderService
php artisan cache:address-template
```
邮编缓存
```
php artisan cache:postcode
```
#### 3.5 配置supervisor
由于某些原因，supervisor并未以容器的形式进行管控，而是在php-cli容器中进行了安装，仅此需要解决supervisor配置问题，每次重启后均需重新配置。
进入docker目录，执行以下命令：
``` 
docker-compose exec php-cli bash
cd api
sudo supervisord -c /etc/supervisor/supervisord.conf
```

#### 3.6 基础权限表  
手动导入permission表  

#### 3.7 导入地址模板  
```$php
php artisan db:seed --class=AddressTemplateSeeder
```

#### 3.8 打印模板上传
打印模板并未存在git上，需要在本地上传至服务器的公共目录，然后移动至/app/tms/www/api/storage/app/public/admin/print_template。
标准模板带包裹二维码，重命名为1.png，通用模板不带包裹二维码，重命名为2.png。


## 三、使用说明
### 1. 项目连接
#### 1.1 内部连接
TMS项目后端与管理员端，货主端，司机端进行连接，其中管理员端与货主端为网页端，司机端为安卓端。  
开发服管理员端接口地址：[https://dev-tms.nle-tech.com/api/admin](https://dev-tms.nle-tech.com/api/admin)  
正式服管理员端接口地址：[https://tms-admin.eutechne.com/api/admin](https://tms-admin.eutechne.com/api/admin)  
开发服货主端接口地址：[https://dev-tms.nle-tech.com/api/merchant](https://dev-tms.nle-tech.com/api/merchant)  
正式服货主端接口地址：[https://tms-admin.eutechne.com/api/merchant](https://tms-admin.eutechne.com/api/merchant)  
开发服司机端接口地址：[https://dev-tms.nle-tech.com/api/driver](https://dev-tms.nle-tech.com/api/driver)  
正式服司机端接口地址：[https://tms-admin.eutechne.com/api/driver](https://tms-admin.eutechne.com/api/driver)  
#### 1.2 业务外部连接
TMS项目可通过API与其他系统进行交互，目前仅对接ERP和欧亚商城。
##### 1.2.1 响应
第三方系统可以通过请求以下接口进行订单新增等主动操作，TMS系统会进行响应。
开发服第三方接口地址：[https://dev-tms.nle-tech.com/api/merchant_api](https://dev-tms.nle-tech.com/api/merchant_api)  
正式服第三方接口地址：[https://tms-admin.eutechne.com/api/merchant_api](https://tms-admin.eutechne.com/api/merchant_api)  
##### 1.2.2 推送
TMS系统可以在公司配置-商家配置中的API授权菜单中，设置第三方接收URL。TMS系统会将信息推送到此地址。

#### 1.3 功能外部连接
TMS系统目前利用到的第三方接口有谷歌地图API，腾讯地图API，postcode.nl，公司内部的谷歌API服务(暂行办法)。智能优化，距离计算等功能的正常运作需要以上第三方接口生效，因此请在项目根目录下的.env文件中对各个接口进行正确配置。  
除此之外，本项目有自动翻译的功能，如果需要用到该功能，还需调用百度翻译API。
谷歌地图API地址：[https://maps.googleapis.com/maps/api](https://maps.googleapis.com/maps/api)  
谷歌地图API文档：[https://developers.google.com/maps/documentation](https://developers.google.com/maps/documentation)  
腾讯地图API地址：[https://apis.map.qq.com/ws/distance/v1/optimal_order](https://apis.map.qq.com/ws/distance/v1/optimal_order)  
腾讯地图API文档：[https://lbs.qq.com](https://lbs.qq.com)  
公司内部的谷歌API服务：[https://tms.exss.io](https://tms.exss.io)  
postcode.nl网站API地址：[https://api.postcode.nl/rest](https://api.postcode.nl/rest)  
百度翻译API地址：[http://api.fanyi.baidu.com/api/trans/vip/translate](http://api.fanyi.baidu.com/api/trans/vip/translate)
百度翻译文档：[https://api.fanyi.baidu.com/doc/21](https://api.fanyi.baidu.com/doc/21)

### 2. 业务流程
本系统为SaaS平台，服务对象为运输公司、物流公司，服务内容主要为货物运输配套功能。可对接上游电商系统，也可直接面向普通个人客户。管理员端的主要功能为财务管理，公司管理，订单管理，出车管理，车队管理，配置管理。货主端主要功能为订单管理，配置管理。司机端主要功能为，任务管理，备忘录管理，第三方服务管理，包裹复核管理。管理员端是业务处理的枢纽，权限为最高权限，货主端主要为订单入口，司机端主要负责运输业务实现。
#### 2.1 新建公司
在登录页面点击注册，通过简单的信息填写便可以新建一个公司账号，但此时账号是无法进行实际使用。此时登录后会直接跳转到配置页面，在配置完整之前，其他功能是无法使用的。
#### 2.2 配置线路
在出车管理中，对线路进行配置，线路有较多参数，通过这些参数调整，可以控制订单自动分配线路的流向。分配好的订单会以运单的形式，成为整个系统的核心。
#### 2.3 配置车队
在车队管理中，新建司机和车辆，只有分配了司机与车辆的线路任务才能进行出车作业。司机端暂时没有注册功能，在管理员端注册好司机后，司机就可以在司机端登录了。
#### 2.4 新建货主或客户
在公司管理中，新建货主或客户，只有新建了货主或者客户后，才能以其为来源进行新增订单。
#### 2.5 新增订单
订单既可以通过货主端，也可以通过API或者手动添加。对于货主或者客户来说，订单是他们对于这个系统的唯一使用凭证。通过订单类型，可分为取件订单，派件订单，取派订单，不同的订单会生成不同的运单，在系统内部实际上是通过操作运单来进行作业的。取件订单会生成一个取件运单，派件订单会生成一个派件运单，取派订单会生成一个取件订单与一个派件订单。
#### 2.6 分配线路任务
进入线路任务管理，为线路任务分配司机和车辆，然后进入智能优化界面，自动优化或者手动优化线路，这样司机端就能获得一个系统给出的最优路径。智能优化功能是基于谷歌地图，腾讯地图API做的，本系统只根据业务进行了调用封装。
#### 2.7 司机出库
在管理员端新增司机后，司机便可以通过新增使用的账户密码登录司机端，登录司机端后可以选择需要派送的线路任务，进行出库操作。出库时需要扫描包裹二维码或者手动输入包裹号，包裹号在订单新增时就会输入。
#### 2.8 站点签收
站点的定义是同一个目的地，同一个客户，同一取派日期，同一条线路任务下的运单集合，是系统内的虚拟概念，层级高低依次为线路，线路任务，站点，运单。司机只要选择好客户签收的包裹，如有支付，执行支付流程，然后让客户统一签名即可完成签收。
#### 2.9 司机入库
所有站点取送完成后，需要进行入库操作，网点对取回的包裹进行核实，然后统一签名即可完成入库。司机入库后，既完成了整个系统的主流程。


### 3. 自定义命令
在Laravel框架下，拥有便利的自定义artisan命令。在项目目录下的app/Console/Commands文目录内，存放有所有的自定义artisan命令，其中可用的有以下命令。  
#### 3.1 抛错自动翻译
```
php artisan translate
```
原理是获取/app目录下中所有的引号或者双引号内，且首字为汉字的所有字符。然后通过调用百度翻译API，将所有字段翻译成英语与荷兰语。最后写入resources/lang文目录下的语言文件。  
如需手动修改翻译，仍旧可以在语言文件中修改。因为此命令只会新增原语言文件中没有的键，不会覆盖已有的键对应的值。  
由于某些原因，荷兰语翻译中的变量会有异常，需要手动处理。 
#### 3.2 字段自动翻译
```
php artisan validate
```
字段自动翻译，原理是获取数据库中所有字段的备注，并且翻译，后续过程与抛错自动翻译一致。  
#### 3.3 数据库备份
```
php artisan db:backup
```
备份以sql语句的形式，经过压缩后存储在/storage/backup下。定时任务会在APP时区每日1点调用该命令，进行覆盖储存。  
#### 3.4 socket通讯推送
```
php artisan admin:push {id : the admin u_id} {type : the push type}
```
#### 3.5 订单签收重推
```
php artisan repush {--order_no= : order_no} {--tour_no= : tour_no}
```
#### 3.6 重新推送
```
php artisan test:guzzle {url}
```
该命令需要在app/Console/Commands/TestGuzzle.php中按格式填构建请求数据，然后填写所需第三方Url，此命令适用于所有第三方推送。  
#### 3.7 解锁线路任务的操作锁定
```
php artisan unlock:tour {tour_no}
```
如果智能调度时出现“当前 tour 正在操作中,请稍后操作”报错，可使用该命令对线路任务进行解锁。  

### 4 代码规范
#### 4.1 命名
表名采用小蛇形命名法，字母全部小写，通过下划线连接。  
字段名采用小蛇形命名法，字母全部小写，通过下划线连接。 
常量名采用大蛇形命名法，字母全部大写，通过下划线连接。 
变量名采用小驼峰命名法，除第一个单词外其他单词首字母大写。  
函数名采用小驼峰命名法，除第一个单词外其他单词首字母大写。  
类名采用大驼峰命名法，所有单词首字母大写。  
路由采用字母全部小写，通过短横线连接。
#### 4.2 目录结构
目录结构大体按照Laravel框架设置，不同点如下：  
##### 4.2.1 新增app/Services目录
该文件目录存放主要业务逻辑。app/Http/Controller只作为从route到service的连接桥梁，而app/Models中只存放与数据库交互的相关代码。Services目录下按照不同端分为几个子文件目录，每个端之间无法互相调用。虽然这样做会造成一部分代码重复冗余，但也是为了保证代码分离，如果有需要，可以快速将原项目代码拆分为各个端独立的代码。  
##### 4.2.2 新增app/Http/validate目录
该目录下存放各个接口的表单验证规则。与Services一样，validate目录也按照不同端分为几个子目录。通过validate中间件，可以将validate自动绑定到service上，自动绑定的条件是validate文件与service文件拥有共同前缀，例如，orderService与OrderValidate。亦可在Service文件中，手动调用validate类，或者调用底层validator::make()静态方法。  
##### 4.2.3 新增app/Exports目录
该目录下存放所有的导出模板。
##### 4.2.4 新增app/Imports目录
该目录下存放所有的导入模板。
##### 4.2.5 新增app/Mail目录
该目录下存放邮件发送相关代码。  
##### 4.2.6 新增app/Worker目录
该目录下存放即时通讯相关代码。
##### 4.2.7 新增config/tms.php文件
该文件储存环境配置路径，所有业务代码只能通过该文件访问.env里的环境配置。这样做是为了安全性与统一管理。
该目录下存放即时通讯相关代码。
#### 4.3 设计通例
##### 4.3.1 Service相互之间的调用
在一个service中，通过getInstance方法构造单例，从而调用其他service。controller等其他类大多数无法进行同级调用。
##### 4.3.2 基类继承
在service目录下，有一个BaseService作为基类，该类中存有较为通用的方法。其他所有service类都继承这个基类，又根据业务需求分别拥有特殊方法。并且，可以通过复写基类中的方法对其通用方法进行定制。既可以减少代码重复性，又不缺乏灵活性。除了service，其他所有的类都可以以此方法构建通用基类，提高代码复用率。如果某一类service除了BaseService外，仍然有较多相同代码，可以在BaseService与Serice之间再加一层通用类，例如由于按区域分配订单与按邮编分配订单这两个服务，拥有部分共同的处理步骤，所以在AreaService与BaseService之间，新建BaseLineService。
##### 4.3.3 上传文件目录结构
用户上传的文件在app/public/storage目录下，根据不同端分为admin，driver，merchant三个文件夹，在此之下再根据文件类型分文件夹，例如，barcode，excel，flie等。结构形如app/public/storage/admin/excel。一般情况下不要改变该结构，不要在文件类型目录下再建更多子目录，那样做的话会难以管理。

## 附录：
### 1 新增权限
#### 1.1 新增permission表数据，注意要保持树状结构，并且注意类型，1为菜单，2为按钮，并将路由别名告知前端，前端通过路由别名进行隐藏显示操作。
#### 1.3 重新缓存permission表
执行
```
php artisan cache:permission
```
#### 1.4 初始化权限

``` php
php artisan init:permission
```

