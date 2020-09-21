<!doctype html>
<html>

<head>
    <!-- 声明当前页面的编码集：charset=gbk,gb2312(中文编码) , utf-8(国际编码) -->
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
    <!-- 声明当前页面的三元素 -->
    <title>Next..</title>
    <meta name='keywords' content='个人网站,域名'>
    <meta name='description' content=''>

    <!-- js/css -->
    <style>
        html, body {
            width: 100%;
            height: 100%;
            /* padding: 0px; */
            margin: 0px;
        }

        .container {
            width: 100%;
            height: 100vh;
            padding: 0px;
            margin: 0px;
            text-align: center;
        }



        .tit {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }

        /* 表格 */
        .tb {
            width: 80%;
            margin: 0 auto 20px;
            display: table;
            border: 1px #000 solid;
        }

        .tb-r {
            display: table-row;
        }

        .tb-r > div {
            display: table-cell;
            border: 1px #000 solid;
            padding: 20px;

        }

        /* 照片 */
        .img-list {
            width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .img-list > div {
            display: inline-block;
            width: 321px;
            height: 321px;
            margin-bottom: 20px;
            border: 1px #000 solid;
        }

        img {
            width: 128px;
            height: 128px;
            margin: 96px auto;
        }

    </style>
</head>

<body>
<div class="container">
    <div class="content">
        <div class="tit">{{__("车辆信息管理")}}</div>

        <!-- 表格 -->
        <div class="tb">
            <div class="tb-r">
                <div>{{__("导出日期")}}</div>
                <div>{{__("车牌号")}}</div>
                <div>{{__("司机签名")}}</div>
            </div>
            <div class="tb-r">
                <div></div>
                <div>{{$data['car_no']}}</div>
                <div></div>
            </div>
        </div>

        <!-- 照片 -->
        <div class="img-list">
            @foreach($data['url_list'] as $key=>$v)
                <div>
                    <img src="{{$v}}" width="100px"/>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
