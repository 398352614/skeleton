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
use App\Models\Merchant;
use App\Models\MerchantGroup;
use App\Models\OrderItem;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\WeightCharging;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;


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

    /**运价方案详情
     * @return array
     * @throws BusinessLogicException
     */
    public function me(){
        $id=DB::table('merchant_group')->where('id',auth()->user()->merchant_group_id)->first()->transport_price_id;
        $info =TransportPrice::query()->where('id',$id)->first()->toArray();
        if(empty($info) ||empty($id)){
            throw new BusinessLogicException('数据不存在');
        }
        $info['km_list'] = DB::table('kilometres_charging')->where('transport_price_id', '=', $id)->get()->toArray();
        $info['weight_list'] = DB::table('weight_charging')->where('transport_price_id', '=', $id)->get()->toArray();
        $info['special_time_list'] = DB::table('special_time_charging')->where('transport_price_id', '=', $id)->get()->toArray();
        return $info;
    }
}
