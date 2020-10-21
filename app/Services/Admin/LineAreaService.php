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
use App\Services\Admin\BaseService;
use App\Traits\ConstTranslateTrait;
use App\Traits\MapAreaTrait;
use Illuminate\Database\Eloquent\Model;

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
        $where = ['country' => $country];
        !empty($id) && data_set($where, 'line_id', ['<>', $id]);

        $dbList = parent::getList($where, ['line_id', 'coordinate_list', 'country'], false, ['line_id', 'coordinate_list', 'country'])->toArray();
        foreach ($dbList as $dbLineArea) {
            $status = MapAreaTrait::TwoAreasOverlap($coordinateList, json_decode($dbLineArea['coordinate_list'], true));
            if ($status) {
                throw new BusinessLogicException('区域有重叠');
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
            $dataList[] = [
                'line_id' => $lineId,
                'coordinate_list' => json_encode($coordinateList, JSON_UNESCAPED_UNICODE),
                'schedule' => $weekday,
                'country' => $country
            ];
        }
        $rowCount = parent::insertAll($dataList);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
    }
}
