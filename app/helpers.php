<?php

if (!function_exists('isJson')) {
    /**
     * 判断字符串是否是json
     * @param $str
     * @return bool
     */
    function isJson($str)
    {
        json_decode($str, JSON_UNESCAPED_UNICODE);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('failed')) {
    /**
     * 判断字符串是否是json
     * @param  string  $message
     * @param  array  $data
     * @return array
     */
    function failed(string $message = 'failed', array $data = [])
    {
        return \App\Traits\ResponseTrait::response(10000, $data, $message);
    }
}

if (!function_exists('success')) {
    /**
     * 判断字符串是否是json
     * @param  string  $message
     * @param  array  $data
     * @return array
     */
    function success(string $message = 'success', array $data = [])
    {
        return \App\Traits\ResponseTrait::response(200, $data, $message);
    }
}