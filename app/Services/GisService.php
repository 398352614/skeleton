<?php
/**
 * 坐标处理
 * User: long
 * Date: 2020/9/16
 * Time: 16:41
 */

namespace App\Services;


/**
 * https://github.com/pokeyou/GpsPositionTransform
 * WGS84坐标系(World Geodetic System)：即地球坐标系，国际上通用的坐标系。设备一般包含GPS芯片或者北斗芯片获取的经纬度为WGS84地理坐标系,谷歌地图采用的是WGS84地理坐标系（中国范围除外）
 * GCJ02坐标系：即火星坐标系，是由中国国家测绘局制订的地理信息系统的坐标系统。由WGS84坐标系经加密后的坐标系。谷歌中国地图和搜搜中国地图采用的是GCJ02地理坐标系
 * BD09坐标系：即百度坐标系，GCJ02坐标系经加密后的坐标系;
 * 搜狗坐标系、图吧坐标系等，估计也是在GCJ02基础上加密而成的。
 */
class GisService
{

    private static $pi = 3.1415926535897932384626;

    private static $a = 6378245.0;

    private static $ee = 0.00669342162296594323;

    /**
     * get rectangle longitude and latitude
     *
     * @param float $longitude
     * @param float $latitude
     * @param integer $distance
     *            meter
     * @return array
     */
    static function rectangle($longitude, $latitude, $distance)
    {
        $radius = 6371 * 1000;
        // latitude boundaries
        $maxlat = $latitude + rad2deg($distance / $radius);
        $minlat = $latitude - rad2deg($distance / $radius);
        // longitude boundaries (longitude gets smaller when latitude increases)
        $maxlng = $longitude +
            rad2deg($distance / $radius / cos(deg2rad($latitude)));
        $minlng = $longitude -
            rad2deg($distance / $radius / cos(deg2rad($latitude)));
        return array(
            array(
                'lat' => $minlat,
                'lng' => $minlng
            ),
            array(
                'lat' => $maxlat,
                'lng' => $maxlng
            )
        );
    }

    /**
     * get distance by longitude and latitude
     *
     * @param mixed $lng1
     * @param mixed $lat1
     * @param mixed $lng2
     * @param mixed $lat2
     * @return int meter
     */
    static function distance($lng1, $lat1, $lng2, $lat2)
    {
        // convert latitude/longitude degrees for both coordinates
        // to radians: radian = degree * π / 180
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // calculate great-circle distance
        $distance = acos(
            sin($lat1) * sin($lat2) +
            cos($lat1) * cos($lat2) * cos($lng1 - $lng2));

        // distance in human-readable format:
        // earth's radius in km = ~6371
        return 6371 * $distance * 1000;
    }

    /**
     * wgs84 转 gcj02
     * @param mixed $lng
     * @param mixed $lat
     * @return array
     */
    public static function wgs84ToGcj02($lng, $lat)
    {
        if (static::outOfChina($lat, $lng)) {
            return array(
                'lat' => $lat,
                'lng' => $lng
            );
        }
        $dLat = static::transformLat($lng - 105.0, $lat - 35.0);
        $dLon = static::transformLng($lng - 105.0, $lat - 35.0);
        $radLat = $lat / 180.0 * static::$pi;
        $magic = sin($radLat);
        $magic = 1 - static::$ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLat = ($dLat * 180.0) / ((static::$a * (1 - static::$ee)) /
                ($magic * $sqrtMagic) * static::$pi);
        $dLon = ($dLon * 180.0) /
            (static::$a / $sqrtMagic * cos($radLat) * static::$pi);
        $mgLat = $lat + $dLat;
        $mgLon = $lng + $dLon;
        return array(
            'lat' => $mgLat,
            'lng' => $mgLon
        );
    }

    /**
     * gcj02 转 wgs84
     * @param mixed $lng
     * @param mixed $lat
     * @return array
     */
    public static function gcj02ToWgs84($lng, $lat)
    {
        $gps_arr = static::transform($lng, $lat);
        $lontitude = $lng * 2 - $gps_arr['lon'];
        $latitude = $lat * 2 - $gps_arr['lat'];
        return array(
            'lat' => $latitude,
            'lng' => $lontitude
        );
    }

    /**
     * gcj02 转 bd09
     * @param mixed $lng
     * @param mixed $lat
     * @return array
     */
    public static function gcj02ToBd09($lng, $lat)
    {
        $x = $lng;
        $y = $lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * static::$pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * static::$pi);
        $bd_lon = $z * cos($theta) + 0.0065;
        $bd_lat = $z * sin($theta) + 0.006;
        return array(
            'lat' => $bd_lat,
            'lng' => $bd_lon
        );
    }

    /**
     * bd09 转 Gcj02
     * @param mixed $lng
     * @param mixed $lat
     * @return array
     */
    public static function bd09ToGcj02($lng, $lat)
    {
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * static::$pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * static::$pi);
        $gg_lon = $z * cos($theta);
        $gg_lat = $z * sin($theta);
        return array(
            'lat' => $gg_lat,
            'lng' => $gg_lon
        );
    }

    /**
     * bd09 转 wgs84
     * @param $lng
     * @param $lat
     * @return array
     */
    public static function bd09ToWgs84($lng, $lat)
    {
        $gcj02_arr = static::bd09ToGcj02($lng, $lat);
        $map84_arr = static::gcj02ToWgs84($gcj02_arr['lng'], $gcj02_arr['lat']);
        return $map84_arr;
    }

    /**
     * wgs84 转 bd09
     * @param $lng
     * @param $lat
     * @return array
     */
    public static function wgs84ToBd09($lng, $lat)
    {
        $gcj02_arr = static::wgs84ToGcj02($lng, $lat);
        $bd09_arr = static::gcj02ToBd09($gcj02_arr['lng'], $gcj02_arr['lat']);
        return $bd09_arr;
    }

    /**
     * 判断是否是在中国
     * @param $lng
     * @param $lat
     * @return bool
     */
    private static function outOfChina($lng, $lat)
    {
        if (($lng < 72.004 || $lng > 137.8347) &&
            ($lat < 0.8293 || $lat > 55.8271))
            return true;
        return false;
    }


    /**
     * 坐标转换
     * @param $lng
     * @param $lat
     * @return array
     */
    private static function transform($lng, $lat)
    {
        if (static::outOfChina($lng, $lat)) {
            return array(
                'lat' => $lat,
                'lng' => $lng
            );
        }
        $dLat = static::transformLat($lng - 105.0, $lat - 35.0);
        $dLon = static::transformLng($lng - 105.0, $lat - 35.0);
        $radLat = $lat / 180.0 * static::$pi;
        $magic = sin($radLat);
        $magic = 1 - static::$ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLat = ($dLat * 180.0) / ((static::$a * (1 - static::$ee)) /
                ($magic * $sqrtMagic) * static::$pi);
        $dLon = ($dLon * 180.0) /
            (static::$a / $sqrtMagic * cos($radLat) * static::$pi);
        $mgLat = $lat + $dLat;
        $mgLon = $lng + $dLon;
        return array(
            'lat' => $mgLat,
            'lng' => $mgLon
        );
    }

    /**
     * 经度转换
     * @param $x
     * @param $y
     * @return float|int
     */
    private static function transformLng($x, $y)
    {
        $ret = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y +
            0.1 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * static::$pi) +
                20.0 * sin(2.0 * $x * static::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($x * static::$pi) +
                40.0 * sin($x / 3.0 * static::$pi)) * 2.0 / 3.0;
        $ret += (150.0 * sin($x / 12.0 * static::$pi) +
                300.0 * sin($x / 30.0 * static::$pi)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 纬度转换
     * @param $x
     * @param $y
     * @return float|int
     */
    private static function transformLat($x, $y)
    {
        $ret = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y +
            0.2 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * static::$pi) +
                20.0 * sin(2.0 * $x * static::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($y * static::$pi) +
                40.0 * sin($y / 3.0 * static::$pi)) * 2.0 / 3.0;
        $ret += (160.0 * sin($y / 12.0 * static::$pi) +
                320 * sin($y * static::$pi / 30.0)) * 2.0 / 3.0;
        return $ret;
    }
}
