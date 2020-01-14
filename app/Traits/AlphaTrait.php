<?php

/**
 * 字母操作 trait
 * User: long
 * Date: 2020/1/14
 * Time: 13:42
 */

namespace App\Traits;

trait AlphaTrait
{
    /**
     * 获取下一个大写的字母
     * @param $alpha
     * @return string
     * @throws \App\Exceptions\BusinessLogicException
     */
    public static function getNextUpAlpha($alpha)
    {
        $ord = ord($alpha);
        if ($ord < 65 || $ord > 90) {
            throw new \App\Exceptions\BusinessLogicException('字母规则不正确');
        }
        return ($ord === 90) ? 'A' : chr($ord + 1);
    }
}