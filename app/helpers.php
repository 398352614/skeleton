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
    function success(string $message = 'successful', array $data = [])
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


if (!function_exists('multi_array_unique')) {
    /**
     * 根据多维数组字段去重
     * @param $arrList array 多维数组
     * @param $field string 比较的字段
     * @return array
     */
    function multi_array_unique($arrList, $field)
    {
        $newArrList = [];
        foreach ($arrList as $arr) {
            $fieldArr = array_column($newArrList, $field);
            if (!in_array($arr[$field], $fieldArr)) {
                $newArrList[] = $arr;
            }
        }
        return $newArrList;
    }
}


if (!function_exists('create_unique')) {
    /**
     * 创建唯一标识
     * @param $str
     * @return bool
     */
    function create_unique(string $pre = '')
    {
        $data = time() . rand();
        if (isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['REMOTE_ADDR'])) {
            $data = $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $data;
        }
        return $pre . sha1($data);
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
        $postcode = trim($postcode);
        $final_postcode = preg_replace('/\D/', '', $postcode);
        if ($letter) {
            return trim($postcode, $final_postcode);
        }

        return $final_postcode;
    }
}

if (!function_exists('is_include_chinese')) {

    /**
     * 字符串是否包含中文
     * @param $str
     * @return bool
     */
    function is_include_chinese($str)
    {
        return (preg_match('/[\x{4e00}-\x{9fa5}]/u', $str) === 1) ? true : false;
    }
}

if (!function_exists('explode_id_string')) {

    /**
     * 分隔ID字符串
     * @param $delimiter
     * @param $str
     * @return array
     */
    function explode_id_string($str, $delimiter = ',')
    {
        $list = is_array($str) ? $str : explode($delimiter, $str);
        $list = array_filter($list, function ($value) {
            return is_numeric($value);
        });
        return array_unique($list);
    }
}

if (!function_exists('array_only_fields_sort')) {

    /**
     * 字段排序
     * @param $data
     * @param $fields
     * @return array
     */
    function array_only_fields_sort($data, $fields)
    {
        $newData = [];
        if (collect($data)->has($fields)) {
            foreach ($fields as $v) {
                $newData[$v] = $data[$v];
            }
        } else {
            $newData = \Illuminate\Support\Arr::only($data, $fields);
        }
        return $newData;
    }
}

if (!function_exists('array_key_prefix')) {

    /**
     * 设置数组键的前缀
     * @param $arr
     * @param $prefix
     * @return array
     */
    function array_key_prefix($arr, $prefix = '')
    {
        foreach ($arr as $key => $value) {
            $arr[$prefix . $key] = $value;
            unset($arr[$key]);
        }
        return $arr;
    }
}

if (!function_exists('post_code_be')) {

    /**
     * 比利时邮编判断
     * @param $postCode
     * @return bool
     */
    function post_code_be($postCode)
    {
        $postCode = trim($postCode);
        return (is_numeric($postCode) && (\Illuminate\Support\Str::length($postCode) == 4));
    }
}

if (!function_exists('have_special_char')) {

    /** 判断是否有表情字符
     * @param $str
     * @return bool
     */
    function have_special_char($str)
    {
        $length = mb_strlen($str);
        $array = [];
        for ($i = 0; $i < $length; $i++) {
            $array[] = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($array[$i]) >= 4) {
                return true;

            }
        }
        return false;
    }
}
