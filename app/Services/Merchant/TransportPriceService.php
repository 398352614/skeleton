<?php
/**
 * 运价管理
 * User: long
 * Date: 2019/12/24
 * Time: 20:06
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\TransportPriceResource;
use App\Models\KilometresCharging;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\WeightCharging;
use App\Services\BaseService;


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
        $this->model = $transportPrice;
        $this->query = $this->model::query();
        $this->resource = TransportPriceResource::class;
        $this->request = request();
        $this->formData = $this->request->all();
        //子模型
        $this->kilometresChargingModel = $kilometresCharging;
        $this->weightChargingModel = $weightCharging;
        $this->specialTimeChargingModel = $specialTimeCharging;
    }

    /**
     * 商户组 服务
     * @return MerchantGroupService
     */
    private function getMerchantGroupService()
    {
        return self::getInstance(MerchantGroupService::class);
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
        $info = parent::getInfo(['id' => $merchantGroup['transport_price_id']], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $info['km_list'] = $this->kilometresChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        $info['weight_list'] = $this->weightChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        $info['special_time_list'] = $this->specialTimeChargingModel->newQuery()->where('transport_price_id', '=', $id)->get()->toArray();
        for ($i = 0; $i < count($info['special_time_list']); $i++) {
            $info['special_time_list'][$i]['period'] = [$info['special_time_list'][$i]['start'], $info['special_time_list'][$i]['end']];
        }
        return $info;
    }
}
