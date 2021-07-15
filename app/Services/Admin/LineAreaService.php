<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 10:50
 */

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\LineArea;
use App\Traits\ConstTranslateTrait;
use App\Traits\MapAreaTrait;

class LineAreaService extends BaseService
{
    public function __construct(LineArea $lineArea, $resource = null, $infoResource = null)
    {
        parent::__construct($lineArea, $resource, $infoResource);
    }

    /**
     * 区域验证
     * @param $coordinateList
     * @param $country
     * @param $id
     * @throws BusinessLogicException
     */
    public function checkArea($coordinateList, $country, $id = null)
    {
        //判断页面区域是否有重叠
        $areaCount = count($coordinateList);
        if ($areaCount > 1) {
            foreach ($coordinateList as $k => $v) {
                if (count($v) < 3) {
                    throw new BusinessLogicException("一个区域至少应该有三个顶点");
                }
            }
            for ($i = 0; $i < $areaCount - 1; $i++) {
                for ($j = $i + 1; $j < $areaCount; $j++) {
                    $status = MapAreaTrait::TwoAreasOverlap($coordinateList[$i], $coordinateList[$j]);
                    if ($status) {
                        throw new BusinessLogicException('区域[:i]和区域[:j]有重叠', 1000, ['i' => $i + 1, 'j' => $j + 1]);
                    }
                }
            }
        }
        //判断页面区域和已存在区域是否有重叠
        $where = ['country' => $country];
        !empty($id) && data_set($where, 'line_id', ['<>', $id]);
        $dbList = parent::getList($where, ['line_id', 'coordinate_list', 'country'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        foreach ($dbList as $dbLineArea) {
            $dbCoordinateList = json_decode($dbLineArea['coordinate_list'], true);
            foreach ($coordinateList as $key => $coordinateItemList) {
                $status = MapAreaTrait::TwoAreasOverlap($coordinateItemList, $dbCoordinateList);
                if ($status) {
                    throw new BusinessLogicException('区域[:key]部分区域已存在', 1000, ['key' => $key + 1]);
                }
            }
        }
    }


    /**
     * 批量新增
     * @param $lineId
     * @param $coordinateList
     * @param $country
     * @throws BusinessLogicException
     */
    public function storeAll($lineId, $coordinateList, $country)
    {
        $dataList = [];
        $weekdayList = array_keys(ConstTranslateTrait::$weekList);
        foreach ($weekdayList as $weekday) {
            foreach ($coordinateList as $coordinateItemList) {
                $dataList[] = [
                    'line_id' => $lineId,
                    'coordinate_list' => json_encode($coordinateItemList, JSON_UNESCAPED_UNICODE),
                    'schedule' => $weekday,
                    'country' => $country
                ];
            }
        }
        $rowCount = parent::insertAll($dataList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败，请重新操作');
        }
    }
}
