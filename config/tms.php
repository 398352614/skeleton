<?php

return [
    'api_url' => env('API_URL'),
    'api_key' => env('API_KEY'),
    'api_secret' => env('API_SECRET'),
    'map_url' => env('MAP_URL'),
    'map_key' => env('MAP_KEY'),
    'messager_key' => env('MESSAGER_KEY'),
    'messager_secret' => env('MESSAGER_SECRET'),
    'vpn' => env('VPN'),
    'push_url' => env('PUSH_URL', 'dev-tms.nle-tech.com/socket'),
    'country_path' => storage_path(env('COUNTRY_PATH')),
    'special_merchant_id' => env('SPECIAL_MERCHANT_ID'),
    'fake_merchant_id' => env('FAKE_MERCHANT_ID'),

    //缓存前缀
    'cache_prefix' => [
        'company' => 'company:',
        'address_template' => 'address_template:',
    ],
    //缓存标签
    'cache_tags' => [
        'company' => 'company',
        'address_template' => 'address_template',
    ]
];
