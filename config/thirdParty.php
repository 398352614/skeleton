<?php
/**
 * 第三方配置
 * User: long
 * Date: 2019/12/27
 * Time: 10:34
 */
return [
    //位置api
    'location_api' => env('LOCATION_API', 'https://api.postcode.nl/rest'),
    'location_api_key' => env('LOCATION_API_KEY'),
    'location_api_secret' => env('LOCATION_API_SECRET'),
];