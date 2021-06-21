<?php
/**
 * 运价管理
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\TransportPriceResource;
use App\Models\KilometresCharging;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\WeightCharging;
use App\Services\BaseConstService;
use Illuminate\Support\Facades\Log;

/**
 * Class TransportPriceService
 * @package App\Services\Merchant
 * @property KilometresCharging $kilometresChargingModel
 * @property WeightCharging weightChargingModel
 * @property SpecialTimeCharging $specialTimeChargingModel
 */
class TransportPriceService extends BaseService
{
    public function __construct(
        TransportPrice $transportPrice,
        KilometresCharging $kilometresCharging,
        WeightCharging $weightCharging,
        SpecialTimeCharging $specialTimeCharging)
    {
        parent::__construct($transportPrice, TransportPriceResource::class);
        //子模型
        $this->kilometresChargingModel = $kilometresCharging;
        $this->weightChargingModel = $weightCharging;
        $this->specialTimeChargingModel = $specialTimeCharging;
    }

    /**运价方案详情
     * @return array
     * @throws BusinessLogicException
     */
    public function show()
    {
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => auth()->user()->merchant_group_id], ['id', 'transport_price_id'], false);
        if (empty($merchantGroup)) {
            throw new BusinessLogicException('数据不存在');
        }
        $transportPriceId = $merchantGroup['transport_price_id'];
        $info = parent::getInfo(['id' => $transportPriceId], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $info['km_list'] = $this->kilometresChargingModel->newQuery()->where('transport_price_id', '=', $transportPriceId)->get()->toArray();
        $info['weight_list'] = $this->weightChargingModel->newQuery()->where('transport_price_id', '=', $transportPriceId)->get()->toArray();
//        $info['special_time_list'] = $this->specialTimeChargingModel->newQuery()->where('transport_price_id', '=', $transportPriceId)->get()->toArray();
//        for ($i = 0; $i < count($info['special_time_list']); $i++) {
//            $info['special_time_list'][$i]['period'] = [$info['special_time_list'][$i]['start'], $info['special_time_list'][$i]['end']];
//        }
        return $info;
    }

    /**
     * 运价计算(插入运价字段)
     * step阶梯式multiply乘积式
     * @param $data
     * @param null $transportPriceId
     * @return array|void
     * @throws BusinessLogicException
     */
    public function priceCount($data, $transportPriceId = null)
    {
        Log::info('脚手架2', $data);
        //预设为0
        $data['starting_price'] = $data['settlement_amount'] = $data['package_settlement_amount'] = $data['count_settlement_amount'] = 0.00;
        if (!empty($data['package_list'])) {
            foreach ($data['package_list'] as $k => $v) {
                $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = 0.00;
            }
        }
        if (empty($transportPriceId)) {
            $transportPriceId = $this->getTransportPriceIdByMerchantId(auth()->user()->id);
        }
        //没有运价就返回
        if ($transportPriceId == null) {
            return $data;
        }
        $transportPrice = $this->show();
        Log::info('脚手架1', $transportPrice);
        $data['transport_price_id'] = $transportPriceId;
        $data['transport_price_type'] = $transportPrice['type'];
        if ($transportPrice['status'] == BaseConstService::ON) {
            if (!empty($data['package_list'])) {
                //根据计算方式计算包裹运价
                if ($transportPrice['type'] == BaseConstService::TRANSPORT_PRICE_TYPE_1) {
                    $data = $this->multiplyWeightMultiplyDistance($data, $transportPrice);
                } elseif ($transportPrice['type'] == BaseConstService::TRANSPORT_PRICE_TYPE_2) {
                    $data = $this->stepWeightStepDistance($data, $transportPrice);
                } elseif ($transportPrice['type'] == BaseConstService::ONLY_START_PRICE) {
                    $data['settlement_amount'] = $data['count_settlement_amount'] = 0.00;
                } else {
                    throw new BusinessLogicException('暂无预计运价，运价以实际为准');
                }
            }
            $data['starting_price'] = $transportPrice['starting_price'];
            $data['package_settlement_amount'] = number_format_simple($data['count_settlement_amount'], 2, '.', '');
            $data['settlement_amount'] = $data['count_settlement_amount'] = number_format_simple(round($data['count_settlement_amount'] + $data['starting_price'], 2), 2, '.', '');
        }
        if (!empty($data['package_list'])) {
            foreach ($data['package_list'] as $k => $v) {
                $data['package_list'][$k]['settlement_amount'] = number_format_simple($data['package_list'][$k]['settlement_amount'], 2, '.', '');
                $data['package_list'][$k]['count_settlement_amount'] = number_format_simple($data['package_list'][$k]['count_settlement_amount'], 2, '.', '');
            }
        }
        return $data;
    }

    /**
     * 通过货主ID获得运价方案
     * @param $merchantId
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function getTransportPriceIdByMerchantId($merchantId)
    {
        //获取货主组
        $merchant = $this->getMerchantService()->getInfo(['id' => $merchantId], ['*'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        //获取货主组
        $merchantGroup = $this->getMerchantGroupService()->getInfo(['id' => $merchant['merchant_group_id']], ['*'], false);
        if (empty($merchant)) {
            return null;
        }
        return $merchantGroup['transport_price_id'];
    }

    /**
     * 重量乘积，距离乘积
     * @param $data
     * @param $transportPrice
     * @return array
     */
    public function multiplyWeightMultiplyDistance($data, $transportPrice)
    {
        foreach ($data['package_list'] as $k => $package) {
            !empty($package['weight']) && $weightPrice = $this->getWeightPrice($package['weight'], $transportPrice);
            !empty($package['distance']) && $distancePrice = $this->getDistancePrice($data['distance'], $transportPrice);
            if (empty($weightPrice) && !empty($distancePrice)) {
                $weightPrice = 1;
            } elseif (!empty($weightPrice)&& empty($distancePrice)) {
                $distancePrice = 1;
            } elseif (empty($weightPrice) && empty($distancePrice)) {
                $weightPrice = $distancePrice = 0;
            }
            //公式
            $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = round(
                floatval($package['weight']) *
                floatval($weightPrice) *
                floatval($data['distance']) *
                floatval($distancePrice)
                , 2);
            $data['settlement_amount'] = $data['count_settlement_amount'] = round(
                floatval($data['count_settlement_amount']) +
                floatval($data['package_list'][$k]['count_settlement_amount'])
                , 2);
            $data['package_settlement_amount'] = round($data['package_settlement_amount'] + $data['package_list'][$k]['count_settlement_amount'], 2);
        }
        return $data;
    }

    /**
     * 重量阶梯，距离阶梯
     * @param $data
     * @param $transportPrice
     * @return array
     */
    public function stepWeightStepDistance($data, $transportPrice)
    {
        foreach ($data['package_list'] as $k => $package) {
            $weightPrice = $this->getWeightPrice($package['weight'] ?? 1, $transportPrice);
            $distancePrice = $this->getDistancePrice($data['distance'] ?? 1, $transportPrice);
            if ($weightPrice == null && $distancePrice !== null) {
                $weightPrice = 1;
            } elseif ($weightPrice !== null && $distancePrice == null) {
                $distancePrice = 1;
            } elseif ($weightPrice == null && $distancePrice == null) {
                $weightPrice = $distancePrice = 0;
            }
            //公式
            $data['package_list'][$k]['settlement_amount'] = $data['package_list'][$k]['count_settlement_amount'] = round(
                floatval($weightPrice) *
                floatval($distancePrice)
                , 2);
            $data['settlement_amount'] = $data['count_settlement_amount'] = round(
                floatval($data['count_settlement_amount']) +
                floatval($data['package_list'][$k]['count_settlement_amount'])
                , 2);
            $data['package_settlement_amount'] = round($data['package_settlement_amount'] + $data['package_list'][$k]['count_settlement_amount'], 2);
        }
        return $data;
    }

    /**
     * 按重量价格档，获取单位重量价格
     * @param $weight
     * @param $transportPrice
     * @return int
     */
    public function getWeightPrice($weight, $transportPrice)
    {
        $weightPrice = collect($transportPrice['weight_list'])->where('start', '<=', $weight)->where('end', '>', $weight)->all();
        $weightPrice = array_values($weightPrice);
        return $weightPrice[0]['price'] ?? null;

    }

    /**
     * 按重量价格档，获取单位重量价格
     * @param $distance
     * @param $transportPrice
     * @return int
     */
    public function getDistancePrice($distance, $transportPrice)
    {
        $distancePrice = collect($transportPrice['km_list'])->where('start', '<=', $distance)->where('end', '>', $distance)->all();
        $distancePrice = array_values($distancePrice);
        return $distancePrice[0]['price'] ?? null;
    }
}
