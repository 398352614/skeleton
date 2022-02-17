<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::get('/favicon.ico', function () {
    return '';
});

//用户
Router::addGroup('/user', function () {
    Router::get('', 'App\Controller\UserController@index');
    Router::get('/{id}', 'App\Controller\UserController@show');
    Router::post('', 'App\Controller\UserController@store');
    Router::put('/{id}', 'App\Controller\UserController@edit');
    Router::delete('/{id}', 'App\Controller\UserController@destroy');
});

//认证
Router::addGroup('/auth', function () {
    //登录
    Router::post('/login', 'App\Controller\AuthController@login');
    //登出
    Router::put('/logout', 'App\Controller\AuthController@logout');
    //查看
    Router::get('/', 'App\Controller\AuthController@show');
    //注册码
    Router::get('/register', 'App\Controller\AuthController@registerCode');
    //注册
    Router::post('/register', 'App\Controller\AuthController@register');
    //重置码
    Router::get('/reset', 'App\Controller\AuthController@resetCode');
    //重置
    Router::post('/reset', 'App\Controller\AuthController@reset');
    //测试
    Router::get('/test', 'App\Controller\AuthController@test');
});
