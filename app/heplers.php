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

if (! function_exists('is_json')) {
    /**
     * 判断字符串是否是json.
     * @param $str
     * @return bool
     */
    function is_json($str): bool
    {
        json_decode($str, (bool) JSON_UNESCAPED_UNICODE);
        return json_last_error() == JSON_ERROR_NONE;
    }
}
