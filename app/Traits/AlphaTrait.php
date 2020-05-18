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


    public static function getNextString($string)
    {
        $strList = preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);
        if ($strList[count($strList) - 1] == 'Z') {
            $str = '';
            foreach ($strList as $key => $value) {
                if ($key != count($strList) - 1)
                    $str .= $value;
            }
            if ($str == '') {
                $str = chr(ord('A') - 1);
            }
            $str = self::getNextString($str) . 'A';
        } else {
            $strList[count($strList) - 1] = chr(ord($strList[count($strList) - 1]) + 1);
            $str = implode('', $strList);
        }
        $string = $str;
        return $string;
    }
}