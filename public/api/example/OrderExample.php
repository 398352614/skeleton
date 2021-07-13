<?php
$key = '';
$secret = '';
//订单新增的接口
$api = "api.rrtms.com/api/merchant_api/order";

//参数
$options = [
    //请求数据
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
