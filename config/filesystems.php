<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        //管理员端-图片
        'admin_image_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/images'),
            'url' => env('APP_URL').'/storage/admin/images',
            'visibility' => 'public',
        ],

        //管理员端-文件
        'admin_file_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/file'),
            'url' => env('APP_URL').'/storage/admin/file',
            'visibility' => 'public',
        ],

        //管理员端-表格
        'admin_excel_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/excel'),
            'url' => env('APP_URL').'/storage/admin/excel',
            'visibility' => 'public',
        ],

        //管理员端-文档
        'admin_txt_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/txt'),
            'url' => env('APP_URL').'/storage/admin/txt',
            'visibility' => 'public',
        ],

        //管理员端-条码
        'admin_barcode_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/barcode'),
            'url' => env('APP_URL').'/storage/admin/barcode',
            'visibility' => 'public',
        ],

        //管理员端-打印模板
        'admin_print_template_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/admin/print_template'),
            'url' => env('APP_URL').'/storage/admin/print_template',
            'visibility' => 'public',
        ],

        //管理员端-打印模板
        'admin_file_storage' => [
            'driver' => 'local',
            'root' => storage_path('app/backup'),
            'url' => env('APP_URL').'/storage/backup',
            'visibility' => 'public',
        ],

        //司机端-图片
        'driver_image_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/driver/images'),
            'url' => env('APP_URL').'/storage/driver/images',
            'visibility' => 'public',
        ],

        //司机端-文件
        'driver_file_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/driver/file'),
            'url' => env('APP_URL').'/storage/driver/file',
            'visibility' => 'public',
        ],

        //货主端-文件
        'merchant_file_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/merchant/file'),
            'url' => env('APP_URL').'/storage/merchant/file',
            'visibility' => 'public',
        ],


        //货主端-文件
        'merchant_image_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/merchant/images'),
            'url' => env('APP_URL').'/storage/merchant/images',
            'visibility' => 'public',
        ],

        //pdf文件
        'public_pdf'=>[
            'driver' => 'local',
            'root'   => storage_path('app/public/pdf'),
            'url'   => env('APP_URL').'/storage/pdf',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

    ],

];
