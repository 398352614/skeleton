<?php


namespace App\Traits;


use App\Exceptions\BusinessLogicException;
use App\Services\BaseConstService;
use DemeterChain\B;
use Illuminate\Support\Facades\DB;

/**
 *
 * Class CountTrait
 * @package App\Traits
 */
Trait OrderStatisticsTrait
{

    /**
     * 自动计算
     * @param $number
     * @param $type
     * @param null $targetNumber
     * @throws BusinessLogicException
     */
    public function orderStatistics($number, $type, $targetNumber = null)
    {
        try {
            if (!empty($targetNumber)) {
                $this->singleOrderStatistics($targetNumber, $type);
            }
            $this->singleOrderStatistics($number, $type);
        } catch (\Exception $e) {
            throw new BusinessLogicException('更新数量失败');
        }
    }

    /**
     * 逐条自动计算
     * @param $number
     * @param $type
     * @throws BusinessLogicException
     */
    public function singleOrderStatistics($number, $type)
    {
        $data = DB::table('order')->where($type . '_no', $number)->first()->toArray();
        if ($type == BaseConstService::ORDER) {
            $this->baseOrderStatistics('batch', $data['batch_no']);
        }
        $this->baseOrderStatistics('tour', $data['tour_no']);
    }

    /**
     * 基础自动计算
     * @param $type
     * @param $params
     * @throws BusinessLogicException
     */
    public function baseOrderStatistics($type, $params)
    {
        $baseData = DB::table('order')->where('company_id', auth()->user()->id)->where($type . '_no', '=', $params);
        $data = [
            'expect_pickup_quantity' => $baseData
                ->where('type', '=', BaseConstService::ORDER_TYPE_1)
                ->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)
                ->count(),
            'actual_pickup_quantity' => $baseData
                ->where('type', '=', BaseConstService::ORDER_TYPE_1)
                ->where('status', '=', BaseConstService::TRACKING_ORDER_STATUS_5)
                ->count(),
            'expect_pie_quantity' => $baseData
                ->where('type', '=', BaseConstService::ORDER_TYPE_2)
                ->where('status', '<>', BaseConstService::TRACKING_ORDER_STATUS_7)
                ->count(),
            'actual_pie_quantity' => $baseData
                ->where('type', '=', BaseConstService::ORDER_TYPE_2)
                ->where('status', '=', BaseConstService::TRACKING_ORDER_STATUS_5)
                ->count(),
        ];
        $row = DB::table($type)->sharedLock()->where($type . '_no', '=', $params)->update($data);
        if ($row == false) {
            throw new BusinessLogicException('更新数量失败');
        }
    }

}
