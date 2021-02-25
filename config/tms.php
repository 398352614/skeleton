<?php

return [
    'map_url' => env('MAP_URL'),
    'map_key' => env('MAP_KEY'),
    'messager_key' => env('MESSAGER_KEY'),
    'messager_secret' => env('MESSAGER_SECRET'),
    'vpn' => env('VPN'),
    'push_url' => env('PUSH_URL', 'dev-tms.nle-tech.com/socket'),
    'country_path' => storage_path(env('COUNTRY_PATH')),
    'postcode_path' => storage_path(env('POSTCODE_PATH')),
    'app_env' => env('APP_ENV'),

    //百度
    'baidu_id' => env('BAIDU_ID'),
    'baidu_key' => env('BAIDU_KEY'),
    'baidu_url' => env('BAIDU_URL'),

    //谷歌地图API
    'api_url' => env('API_URL'),
    'api_key' => env('API_KEY'),
    'api_secret' => env('API_SECRET'),
    'routexl_api_key' => env('ROUTEXL_API_KEY'),
    'routexl_api_secret' => env('ROUTEXL_API_SECRET'),

    //腾讯地图API
    'tencent_api_url' => env('TENCENT_API_URL'),
    'tencent_api_key' => env('TENCENT_API_KEY'),
    'tencent_distance_matrix_api_url' => env('TENCENT_DISTANCE_MATRIX_API_URL'),

    //缓存前缀
    'cache_prefix' => [
        'company' => 'company:',
        'address_template' => 'address_template:',
    ],
    //缓存标签
    'cache_tags' => [
        'company' => 'company',
        'address_template' => 'address_template',
        'permission' => env('PERMISSION_CACHE', 'permission:')
    ],
    'wechat_push' => env('WECHAT_PUSH'),
    'env' => env('APP_ENV'),
    'excel' => env('EXCEL'),
    'admin_id' => env('ADMIN_ID'),
    'erp_merchant_id' => env('ERP_MERCHANT_ID'),
    'eushop_merchant_id' => env('EUSHOP_MERCHANT_ID'),
    'admin_email' => env('ADMIN_EMAIL'),
    'true_app_env' => env('TRUE_APP_ENV'),
    'tcp_merchant_id'=>env('TCP_MERCHANT_ID'),

];
