<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Order</title>

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
            width: 740px;
            height: 1050px;
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
            display: flex;
        }

        .facial-list .address .address-obj {
            width: 15%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .facial-list .address .address-info {
            padding-left: 5px;
        }

        .facial-list .address .address-info > div {
            padding: 5px;
        }

        .facial-list .destination {
            display: flex;
        }

        .facial-list .destination .destination-address {
            width: 70%;
            display: flex;
            align-items: center;
            padding-left: 15px;
        }

        .facial-list .destination .destination-info {
            padding-left: 5px;
        }

        .facial-list .destination .destination-info > div {
            padding: 5px;
        }

        .facial-list .item-information {
            display: flex;
            padding: 5px;
        }

        .facial-list .item-information .item-information-item {
            width: 70%;
        }

        .facial-list .item-information .item-information-num {
            padding-left: 15px;
        }

        .facial-list .package {
            padding: 5px;
        }

        .facial-list .package div {
            display: flex;
            align-items: center;
        }

        .facial-list .package div > span {
            width: 33%;
        }

        .facial-list .package div > span:last-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .facial-list .material-science {
            padding: 5px;
        }

        .facial-list .material-science div {
            display: flex;
            align-items: center;
        }

        .facial-list .material-science div > span {
            width: 33%;
        }

        .facial-list .material-science div > span:last-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body {
            padding: 5px;
        }
    </style>

</head>
<body>
<div class="facial-list">
    <div class="company-name bottom-line">{{$data['company_name']}}</div>
    <div class="bar-code bottom-line">
        <img src="data:image/png; base64, {{ $data['barcode'] }}"/>
        <div>{{$data['order_no']}}</div>
    </div>
    <div class="address bottom-line">
        <div class="address-obj right-line">发货方</div>
        <div class="address-info">
            <div>姓名：{{$data['sender_fullname']}}</div>
            <div>电话：{{$data['sender_phone']}}</div>
            <div>地址：{{$data['sender_address']}}</div>
        </div>
    </div>
    <div class="address bottom-line">
        <div class="address-obj right-line">收货人</div>
        <div class="address-info">
            <div>姓名：{{$data['receiver_fullname']}}</div>
            <div>电话：{{$data['receiver_phone']}}</div>
            <div>地址：{{$data['receiver_address']}}</div>
        </div>
    </div>
    <div class="destination bottom-line">
        <div class="destination-address right-line font-size-t1">目的地：{{$data['receiver_address']}}</div>
        <div class="destination-info">
            <div>快件类型：{{$data['type_name']}}</div>
            <div>代收货款：{{$data['replace_amount']}}元</div>
            <div>支付方式：{{$data['settlement_type_name']}}</div>
            <div>运费金额：{{$data['settlement_amount']}}元</div>
        </div>
    </div>
    <div class="item-information bottom-line">
        <div class="item-information-item">物品信息</div>
        <div class="item-information-num">总数量：{{$data['count']}}</div>
    </div>
    <div class="package bottom-line">
        <div><span>包裹</span></div>
        @foreach($data['package_list'] as $key=>$package)
            <div>
                <span>数量：{{$package['expect_quantity']}}</span>
                <span>
                        <span>编号：{{$package['express_first_no']}}</span>

                </span>
            </div>
        @endforeach
    </div>
    <div class="material-science">
        <div><span>材料</span></div>
        @foreach($data['material_list'] as $key=>$material)
            <div>
                <span>数量：{{$material['expect_quantity']}}</span>
                <span>代码：{{$material['code']}}</span>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
