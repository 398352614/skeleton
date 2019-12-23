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

if (!function_exists('create_unique')) {
    /**
     * 创建唯一标识
     * @param $str
     * @return bool
     */
    if (!function_exists('create_unique')) {
        function create_unique(string $pre='') {
            $data = time() . rand();
            if (isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['REMOTE_ADDR'])) {
                $data = $_SERVER['HTTP_USER_AGENT']. $_SERVER['REMOTE_ADDR'] . $data;
            }
            return $pre . sha1($data);
        }
    }
}