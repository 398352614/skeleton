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
                width: 592px;
                height: 840px;
                box-sizing: border-box;
                border: 1px solid #000;
                overflow: hidden;
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
            <div class="company-name bottom-line">{{$data['company_name']}}</div>
            <div class="bar-code bottom-line">
                <img src="{{ $data['barcode'] }}" />
                <div>{{$data['order_no']}}</div>
            </div>
            <div class="address bottom-line">
                <div class="address-obj right-line">{{__('发货方')}}</div>
                <div class="address-info">
                    <div>{{__('姓名').'：'.$data['sender_fullname']}}</div>
                    <div>{{__('电话').'：'.$data['sender_phone']}}</div>
                    <div>{{__('地址').'：'.$data['sender_address']}}</div>
                </div>
            </div>
            <div class="address bottom-line">
                <div class="address-obj right-line">{{__('收货方')}}</div>
                <div class="address-info">
                    <div>{{__('姓名').'：'.$data['receiver_fullname']}}</div>
                    <div>{{__('电话').'：'.$data['receiver_phone']}}</div>
                    <div>{{__('地址').'：'.$data['receiver_address']}}</div>
                </div>
            </div>
            <div class="destination bottom-line">
                <div class="destination-address right-line font-size-t1">{{__('目的地').'：'.$data['receiver_address_short']}}</div>
                <div class="destination-info">
                    <div>{{__('快件类型').'：'.$data['type_name']}}</div>
                    <div>{{__('代收货款').'：'.$data['replace_amount']}}元</div>
                    <div>{{__('支付方式').'：'.$data['settlement_type_name']}}</div>
                    <div>{{__('运费金额').'：'.$data['settlement_amount'].__('元')}}</div>
                </div>
            </div>
            <div class="item-information bottom-line">
                <div class="item-information-item">{{__('物品信息')}}</div>
                <div class="item-information-num">{{__('总数量').'：'.$data['count']}}</div>
            </div>
            @if(!empty($data['package_list']))
                <div class="package bottom-line">
                    @foreach($data['package_list'] as $key=>$package)
                        <div>
                            @if($key==0)
                                <span>{{__('包裹')}}</span>
                            @else
                                <span></span>
                            @endif
                            <span>{{__('数量：').$package['expect_quantity']}}</span>
                            <span>
                                <span>{{__('编号')}}：</span>
                                <img src="{{$package['express_first_no']}}" width="100px" />
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
            @if(!empty($data['material_list']))
                <div class="material-science">
                    @foreach($data['material_list'] as $key=>$material)
                        <div>
                            @if($key==0)
                                <span>{{__('材料')}}：</span>
                            @else
                                <span></span>
                            @endif
                            <span>{{__('数量').'：'.$material['expect_quantity']}}</span>
                            <span>{{__('代码').'：'.$material['code']}}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </body>
</html>
