<?php
/**
 * Created by PhpStorm
 * User: long
 * Date: 2020/5/7
 * Time: 15:58
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Models\Line;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use App\Traits\CompanyTrait;
use App\Traits\ConstTranslateTrait;
use App\Traits\ImportTrait;
use App\Traits\MapAreaTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BaseWarehouseService extends BaseService
{
    use ImportTrait;

    public $filterRules = [
        'name' => ['like', 'name'],
        'country' => ['=', 'country'],
    ];

    public $orderBy = ['id' => 'asc'];

    public function __construct(Warehouse $model)
    {
        parent::__construct($model, null);
    }

    /**
     * 通过订单获取派件网点
     * @param $order
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getPieWarehouseByOrder($order)
    {
        $pieAddress = $this->pieAddress($order);
        if (empty($order['execution_date']) && empty($order['second_execution_date'])) {
            return $this->getWareHouseByAddress($pieAddress, true);
        } else {
            return $this->getWareHouseByAddress($pieAddress);
        }
    }

    /**
     * 派件地址
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function pieAddress($data)
    {
        if ($data['type'] == BaseConstService::ORDER_TYPE_2) {
            if (empty($data['place_country'])) {
                $data['place_country'] = CompanyTrait::getCompany()['country'];
            }
            $fields = ['place_fullname', 'place_phone', 'place_country', 'place_province', 'place_post_code', 'place_house_number', 'place_city', 'place_district',
                'place_street', 'place_address', 'place_lat', 'place_lon', 'execution_date'];
            $data = Arr::only($data, $fields);
        } elseif (in_array($data['type'], [BaseConstService::ORDER_TYPE_1, BaseConstService::ORDER_TYPE_3])) {
            if (empty($data['second_place_country'])) {
                $data['second_place_country'] = CompanyTrait::getCompany()['country'];
            }
            $data = [
                'type' => BaseConstService::TRACKING_ORDER_TYPE_2,
                'place_fullname' => $data['second_place_fullname'],
                'place_phone' => $data['second_place_phone'],
                'place_country' => $data['second_place_country'],
                'place_province' => $data['second_place_province'] ?? '',
                'place_post_code' => $data['second_place_post_code'],
                'place_house_number' => $data['second_place_house_number'],
                'place_city' => $data['second_place_city'],
                'place_district' => $data['second_place_district'] ?? '',
                'place_street' => $data['second_place_street'],
                'place_address' => $data['second_place_address'],
                'place_lat' => $data['second_place_lat'],
                'place_lon' => $data['second_place_lon'],
                'execution_date' => $data['second_execution_date']
            ];
        }else{
            throw new BusinessLogicException('订单状态不对');
        }
        $data['type'] = BaseConstService::TRACKING_ORDER_TYPE_1;
        return $data;
    }


    /**
     * 通过地址获取网点
     * @param $data
     * @param bool $withOutCheck
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getWareHouseByAddress($data, $withOutCheck = false)
    {
        if ($withOutCheck == false) {
            $line = $this->getBaseLineService()->getInfoByRule($data);
        } else {
            $line = $this->getBaseLineService()->getInfoByRuleWithoutCheck($data);
        }
        //获取网点
        $warehouse = $this->getInfo(['id' => $line['warehouse_id']], ['*'], false);
        if (empty($warehouse)) {
            throw new BusinessLogicException('网点不存在');
        }
        $warehouse = collect($warehouse)->toArray();
        return $warehouse;
    }

    /**
     * 通过订单获取取件网点
     * @param $order
     * @return array|Builder|Model|object|null
     * @throws BusinessLogicException
     */
    public function getPickupWarehouseByOrder($order)
    {
        $pickupAddress = $this->pickupAddress($order);
        if (empty($order['execution_date']) && empty($order['second_execution_date'])) {
            return $this->getWareHouseByAddress($pickupAddress, true);
        } else {
            return $this->getWareHouseByAddress($pickupAddress);
        }
    }

    /**
     * 取件地址
     * @param $data
     * @return array
     */
    public function pickupAddress($data)
    {
        if (empty($data['place_country'])) {
            $data['place_country'] = CompanyTrait::getCompany()['country'];
        }
        $fields = ['place_fullname', 'place_phone', 'place_country', 'place_province', 'place_post_code', 'place_house_number', 'place_city', 'place_district',
            'place_street', 'place_address', 'place_lat', 'place_lon', 'execution_date'];
        $data = Arr::only($data, $fields);
        $data['type'] = BaseConstService::TRACKING_ORDER_TYPE_1;
        return $data;
    }

    /**
     * 获取该网点所属的分拨中心
     * @param $warehouse
     * @return
     */
    public function getCenter($warehouse)
    {
        $this->fromWarehouseToCenter($warehouse);
        return $warehouse;
    }

    /**
     * 获取从网点到所属分拨中心的所有节点
     * 把分拨中心存储到$warehouse上
     * @param $warehouse
     * @return array
     */
    public function fromWarehouseToCenter(&$warehouse)
    {
        $data = [$warehouse];
        if ($warehouse['is_center'] == BaseConstService::NO && $warehouse['parent'] !== 0) {
            $warehouse = $this->getInfo(['id' => $warehouse['parent']], ['*'], false);
            $data[] = [$warehouse];
            $this->fromWarehouseToCenter($warehouse);
        } else {
            return $data;
        }
    }
}
