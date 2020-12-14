<?php
/**
 * 填充包裹信息
 * User: long
 * Date: 2020/11/30
 * Time: 15:14
 */
$key = 'DqagEnpeXe1dDykAlGoM';
$secret = '3gDw4rjXxBANwkmk7XaZNkKEyQmvR15P';
$api = "https://dev02-tms.nle-tech.com/api/admin_api/fill-package";

//参数
$options = [
    'package_list' => '[
            {
                "express_first_no":"112121",
                "weight":90
            },
            {
                "express_first_no":"112122",
                "weight":1
            }
        ]'
];

/************************************************1.获取参数*************************************************************/
$params = [
    'key' => $key,
    'sign' => getSign($options, $secret),
    'timestamp' => time(),
    'data' => $options
];

/*************************************************2.接口请求************************************************************/
/**
 * 返回值
 * code 200和非200 200表示请求成功,非200表示请求失败
 * data 响应数据
 * msg 响应信息
 */
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
