<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>E-Sale Tech</title>
        <style>
            .font-size-t1 {
                font-size: 24px;
                font-weight: 500;
            }
            .font-size-t2 {
                font-size: 14px;
            }
            .bottom-line {
                border-bottom: 1px solid #000;
            }
            .right-line {
                border-right: 1px solid #000;
            }
            .facial-list {
                width: 592px;
                height: 840px;
                box-sizing: border-box;
                border: 1px solid #000;
            }
            .facial-list .company-name {
                padding: 15px;
            }
            .facial-list .bar-code {
                padding: 5px;
                text-align: center;
            }
            .facial-list .address {
                width: 100%;
                display: table;
            }
            .facial-list .address .address-obj {
                width: 15%;
                display: table-cell;
            }
            .facial-list .address .address-info {
                display: table-cell;
                width: 85%;
                padding-left: 5px;
            }
            .facial-list .address .address-info > div {
                padding: 5px;
            }
            .facial-list .destination {
                width: 100%;
                display: table;
            }
            .facial-list .destination .destination-address {
                width: 70%;
                display: table-cell;
            }
            .facial-list .destination .destination-info {
                width: 30%;
                padding-left: 5px;
                display: table-cell;
            }
            .facial-list .destination .destination-info > div {
                padding: 5px;
            }
            .facial-list .item-information {
                width: 100%;
                display: table;
                padding: 5px;
            }
            .facial-list .item-information .item-information-item {
                width: 70%;
                display: table-cell;
            }
            .facial-list .item-information .item-information-num {
                padding-left: 15px;
                display: table-cell;
            }
            .facial-list .package {
                width: 100%;
                padding: 5px;
            }
            .facial-list .package div {
                width: 100%;
                display: table;
            }
            .facial-list .package div > span {
                width: 33%;
                display: table-cell;
            }
            .facial-list .package div > span:last-child {
                vertical-align: middle;
            }
            .facial-list .material-science {
                width: 100%;
                padding: 5px;
            }
            .facial-list .material-science div {
                width: 100%;
                display: table;
            }
            .facial-list .material-science div > span {
                width: 33%;
                display: table-cell;
            }
            .facial-list .material-science div > span:last-child {
                vertical-align: middle;
            }
        </style>

    </head>
    <body>
        <div class="facial-list">
            <div class="company-name bottom-line">公司名称</div>
            <div class="bar-code bottom-line">
                <img src="http://gss0.baidu.com/9fo3dSag_xI4khGko9WTAnF6hhy/zhidao/pic/item/f636afc379310a5591a83d9bb54543a9832610d4.jpg" />
                <!-- <div>TMS2839284902</div> -->
            </div>
            <div class="address bottom-line">
                <div class="address-obj right-line">发货方</div>
                <div class="address-info">
                    <div>姓名</div>
                    <div>电话</div>
                    <div>地址</div>
                </div>
            </div>
            <div class="address bottom-line">
                <div class="address-obj right-line">收货人</div>
                <div class="address-info">
                    <div>姓名</div>
                    <div>电话</div>
                    <div>地址</div>
                </div>
            </div>
            <div class="destination bottom-line">
                <div class="destination-address right-line font-size-t1">目的地：湖南长沙</div>
                <div class="destination-info">
                    <div>快件类型: 取件/派件</div>
                    <div>代收货款: xxx元</div>
                    <div>支付方式: 寄付/到付</div>
                    <div>运费金额: 0/xx元</div>
                </div>
            </div>
            <div class="item-information bottom-line">
                <div class="item-information-item">物品信息</div>
                <div class="item-information-num">总数量</div>
            </div>
            <div class="package bottom-line">
                <div>
                    <span>包裹</span>
                    <span>数量: 1</span>
                    <span>
                        <span>编号</span>
                        <img src="http://gss0.baidu.com/9fo3dSag_xI4khGko9WTAnF6hhy/zhidao/pic/item/f636afc379310a5591a83d9bb54543a9832610d4.jpg" width="100px" />
                    </span>
                </div>
                <div>
                    <span></span>
                    <span>数量: 1</span>
                    <span>
                        <span>编号</span>
                        <img src="http://gss0.baidu.com/9fo3dSag_xI4khGko9WTAnF6hhy/zhidao/pic/item/f636afc379310a5591a83d9bb54543a9832610d4.jpg" width="100px" />
                    </span>
                </div>
            </div>
            <div class="material-science">
                <div>
                    <span>材料</span>
                    <span>数量: 2</span>
                    <span>代码: UIO</span>
                </div>
                <div>
                    <span></span>
                    <span>数量: 2</span>
                    <span>代码: UIO</span>
                </div>
            </div>
        </div>
    </body>
</html>
