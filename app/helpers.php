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
     * @param string $message
     * @param array $data
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
     * @param string $message
     * @param array $data
     * @return array
     */
    function success(string $message = 'success', array $data = [])
    {
        return \App\Traits\ResponseTrait::response(200, $data, $message);
    }
}


if (!function_exists('array_create_group_index')) {
    /**
     * 根据$field，数组列表进行分组并创建索引
     * @param $arr
     * @param $field
     * @return array
     */
    function array_create_group_index($arr, $field)
    {
        $newArray = [];
        foreach ($arr as $val) {
            $newArray[$val[$field]][] = $val;
        }
        unset($arr);
        return $newArray;
    }
}
if (!function_exists('array_create_index')) {
    /**
     * 根据$field，数组列表创建索引
     * @param $arr
     * @param string $field 属性名称，必须是唯一键
     * @return array
     */
    function array_create_index($arr, $field)
    {
        $newArr = [];
        foreach ($arr as $key => $val) {
            $newArr[$val[$field]] = $val;
        }
        unset($arr);
        return $newArr;
    }
}


if (!function_exists('create_unique')) {
    /**
     * 创建唯一标识
     * @param $str
     * @return bool
     */
    if (!function_exists('create_unique')) {
        function create_unique(string $pre = '')
        {
            $data = time() . rand();
            if (isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['REMOTE_ADDR'])) {
                $data = $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $data;
            }
            return $pre . sha1($data);
        }
    }
}

if (!function_exists('explode_post_code')) {
    /**
     * 提取邮编数字部分与字母部分
     * @param $postcode
     * @param bool $letter
     * @return mixed
     */
    function explode_post_code($postcode, $letter = false)
    {
        preg_match('/^(\d+)(\D+)?$/', $postcode, $value);
        if ($letter)
            return $value[2] ?? '';
        return (int)$value[1];
    }
}