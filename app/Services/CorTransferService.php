<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/9/16
 * Time: 16:41
 */

namespace App\Services;


class CorTransferService
{
    //百度转腾讯坐标转换  $a = Latitude , $b = Longitude
    public static function baiDuToTenCent($lat, $lon)
    {
        $x = (double)$lon - 0.0065;
        $y = (double)$lat - 0.006;
        $x_pi = 3.14159265358979324 * 3000 / 180;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);

        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);

        $gb = number_format($z * cos($theta), 6);
        $ga = number_format($z * sin($theta), 6);


        return ['Latitude' => $ga, 'Longitude' => $gb];

    }

    //腾讯转百度坐标转换  $a = Latitude , $b = Longitude
    public static function tenCentToBaiDu($lat, $lon)
    {
        $x = (double)$lon;
        $y = (double)$lat;
        $x_pi = 3.14159265358979324 * 3000 / 180;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);

        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);

        $gb = number_format($z * cos($theta) + 0.0065, 6);
        $ga = number_format($z * sin($theta) + 0.006, 6);


        return ['Latitude' => $ga, 'Longitude' => $gb];

    }
}