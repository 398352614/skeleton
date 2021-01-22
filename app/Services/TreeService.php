<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2021/1/13
 * Time: 13:37
 */

namespace App\Services;

/**
 * @name TreeService
 * @author crazymus < QQ:291445576 >
 * @des PHP生成树形结构,无限多级分类
 * @version 1.2.0
 * @Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * @updated 2015-08-26
 */
class TreeService
{
    /**
     * 生成树形结构
     * @param array 二维数组
     * @return mixed 多维数组
     */
    public static function makeTree($data)
    {
        $dataset = self::buildData($data);
        if (empty($dataset[0])) return [];
        $r = self::makeTreeCore(0, $dataset);
        return $r;
    }


    /* 格式化数据, 私有方法 */
    private static function buildData($data)
    {
        $r = [];
        foreach ($data as $item) {
            $id = $item['id'];
            $parent_id = $item['parent_id'];
            $r[$parent_id][$id] = $item;
        }
        return $r;
    }

    /* 生成树核心, 私有方法  */
    private static function makeTreeCore($index, $data)
    {
        foreach ($data[$index] as $id => $item) {
            if (isset($data[$id])) {
                $item['children'] = self::makeTreeCore($id, $data);
            }
            $r[] = $item;
        }
        return $r;
    }
}
