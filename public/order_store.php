<?php
$key = 'OY17evPMwM6az5Bdobqz';
$secret = 'MmkEv1pRgVyN9aBan3Q1N5X7j4WA96oO';
//订单新增的接口
$api = "http://tms-api.test/api/merchant_api/order";
/**
 * 包裹列表
 * name 包裹名称,必填
 * express_first_no 快递单号1,必填
 * express_second_no 快递单号2,选填
 * out_order_no 外部标识/订单号,选填
 * weight 重量,必填
 * quantity 数量,必填
 * remark 备注,选填
 */
$packageList = [
    [
        'name' => '包裹1',
        'express_first_no' => '123',
        'express_second_no' => '223',
        'out_order_no' => '323',
        'weight' => '12.12',
        'expect_quantity' => '1',
        'remark' => ''
    ],
    [
        'name' => '包裹2',
        'express_first_no' => '423',
        'express_second_no' => '523',
        'out_order_no' => '323',
        'weight' => '12.12',
        'expect_quantity' => '1',
        'remark' => ''
    ],
];

/**
 * 材料列表
 * name 材料名称,必填
 * code 材料代码,必填
 * out_order_no 外部标识/订单号,选填
 * expect_quantity 数量,必填
 * remark 备注,选填
 */
$materialList = [
    [
        'name' => '材料1',
        'code' => 'code1',
        'out_order_no' => 'qqqw',
        'expect_quantity' => '3',
        'remark' => 'wqwq'
    ],
    [
        'name' => '材料2',
        'code' => 'code2',
        'out_order_no' => 'qwwsq',
        'expect_quantity' => '2',
        'remark' => 'wqwq'
    ]
];

//参数
$options = [
    'execution_date' => '2020-07-22',               //取派日期,必填
    'type' => '1',                                  //类型1-取件2-派件,必填
    'out_user_id' => '13',                          //外部客户ID,选填
    'settlement_type' => '1',                       //结算类型1-寄付2-到付,必填
    'settlement_amount' => '12.24',                 //结算金额;当结算类型为2时,必填
    'replace_amount' => '0.00',                     //代收金额,必填
    'delivery' => '1',                              //是否提货1-是2-,必填

    //发货方信息
    'sender_fullname' => 'test_sender',              //发货方姓名,必填
    'sender_phone' => '17570715315',                //发货方电话,必填
    'sender_country' => 'CN',                       //发货方国家,必填
    'sender_post_code' => '1333',                   //发货方邮编,必填
    'sender_house_number' => '808',                 //发货方门牌号,必填
    'sender_city' => 'ChangSha',                    //发货方城市,必填
    'sender_street' => 'C8',                        //发货方街道,必填
    'sender_address' => 'squire',                   //发货方详细地址,选填
    //收货方信息
    'receiver_fullname' => 'test_receiver',          //收货方姓名,必填
    'receiver_phone' => '18825558852',              //收货方电话,必填
    'receiver_country' => 'NL',                     //收货方国家,必填
    'receiver_post_code' => '5611HW',               //收货方邮编,必填
    'receiver_house_number' => '314',               //收货方门牌号,必填
    'receiver_city' => 'Eindhoven',                 //收货方城市,必填
    'receiver_street' => 'De Regent',               //收货方街道,必填
    'receiver_address' => 'SAN SA454',              //收货方详细地址,选填
    'lon' => '5.4740944',                           //收货方纬度,必填
    'lat' => '51.4384193',                          //收货方经度,必填

    'special_remark' => 'special remark',           //特殊事项备注,选填
    'remark' => 'test remark',                      //其余备注,选填
    'package_list' => json_encode($packageList),    //包裹列表,包裹列表和材料列表不能同时为空,选填
    //'material_list' => json_encode($materialList),  //材料列表,包裹列表和材料列表不能同时为空,选填
];
/************************************************1.获取参数*************************************************************/
$params = [
    'key' => $key,
    'sign' => getSign($options, $secret),
    'timestamp' => time(),
    'data' => $options
];
/*************************************************3.接口请求************************************************************/
$result = postUrl($api, $params);
print_r($result);
exit;


/**
 * 获取签名
 * @param $options
 * @param $secret
 * @return string
 */
function getSign($options, $secret)
{
    $options = array_filter($options);
    krsort($options);
    $str = join('&', dotParams($options, []));
    $sign = strtoupper(md5(urldecode($str . $secret)));
    return $sign;
}

/**
 * 平铺数组
 * @param $params
 * @param $newParams
 * @return mixed
 */
function dotParams($params, $newParams)
{
    foreach ($params as $key => $val) {
        if (is_array($val)) {
            $newParams = dotParams($val, $newParams);
        } else {
            array_push($newParams, $key . '=' . $val);
        }
    }
    return $newParams;
}


function postUrl($url, $data)
{
    $headerArray = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
        'X-Requested-With' => 'XMLHttpRequest'
    ];
    try {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
    } catch (\Exception $exception) {
        print_r($exception->getMessage());
        exit;
    }
    return json_decode($output, true);
}

?>
