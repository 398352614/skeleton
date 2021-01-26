<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2021/1/16
 * Time: 14:02
 */
return [
    'production' => env('JPUSH_PRODUCTION', false), // 是否是正式环境
    'key' => env('JPUSH_APP_KEY', ''),                          // key
    'secret' => env('JPUSH_MASTER_SECRET', ''),       // master secret
    'log' => env('JPUSH_LOG_PATH', storage_path('logs/jpush.log')), // 日志文件路径
];