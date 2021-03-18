<?php
/**
 * 地图区域 trait
 * User: long
 * Date: 2020/4/26
 * Time: 10:29
 */

namespace App\Traits;

use Illuminate\Support\Arr;

trait MapAreaTrait
{

    /**
     * 验证坐标点是否在某区域内
     * 如何判断一个点是否在多边形内部？
     *（1）面积和判别法：判断目标点与多边形的每条边组成的三角形面积和是否等于该多边形，相等则在多边形内部。
     *（2）夹角和判别法：判断目标点与所有边的夹角和是否为360度，为360度则在多边形内部。
     *（3）引射线法：从目标点出发引一条射线，看这条射线和多边形所有边的交点数目。如果有奇数个交点，则说明在内部，如果有偶数个交点，则说明在外部。
     * 当前使用方法（3）
     * @param array $coordinateList
     * @param array $coordinate
     * @return bool
     */
    public static function containsPoint(array $coordinateList, array $coordinate)
    {
        //若有相同点
        $samePoint = Arr::where($coordinateList, function ($coordinateV) use ($coordinate) {
            return (($coordinate['lat'] == $coordinateV['lat']) && ($coordinate['lon'] == $coordinateV['lon']));
        });
        if (!empty($samePoint)) return true;
        $latList = array_column($coordinateList, 'lat');
        $lonList = array_column($coordinateList, 'lon');
        $lon = $coordinate['lon'];
        $lat = $coordinate['lat'];
        $bool = false;
        //基本 验证
        if ((bccomp($lon, min($lonList), 10) == -1) || bccomp($lon, max($lonList), 10) == 1 || bccomp($lat, min($latList), 10) == -1 || bccomp($lat, max($latList), 10) == 1) {
            return $bool;
        }
        //引射线法 验证奇偶数
        $coordinateCount = count($coordinateList);
        for ($i = 0, $j = $coordinateCount - 1; $i < $coordinateCount; $j = $i++) {
            if (
                (($latList[$i] > $lat) != ($latList[$j] > $lat)) &&
                ($lon < ($lonList[$j] - $lonList[$i]) * ($lat - $latList[$i]) / ($latList[$j] - $latList[$i]) + $lonList[$i])
            ) {
                $bool = !$bool;
            }
        }
        return $bool;
    }

    /**
     * 验证两个多边形是否重叠
     * 1.验证A多边形存在坐标点在B多边形中
     * 2.验证B多边形存在坐标点在A多边形中
     * 若存在，则重叠；否则，不重叠
     * @param $firstCoordinateList
     * @param $secondCoordinateList
     * @return bool
     */
    public static function TwoAreasOverlap($firstCoordinateList, $secondCoordinateList)
    {
//        foreach ($firstCoordinateList as $firstCoordinate) {
//            if (self::containsPoint($secondCoordinateList, $firstCoordinate)) {
//                return true;
//            }
//        }
        foreach ($secondCoordinateList as $secondCoordinate) {
            if (self::containsPoint($firstCoordinateList, $secondCoordinate)) {
                return true;
            }
        }
        return false;
    }
}
