<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\AddressInfoResource;
use App\Http\Resources\Api\Merchant\AddressResource;
use App\Models\Address;
use App\Services\CommonService;
use App\Traits\CompanyTrait;
use App\Traits\LocationTrait;
use Illuminate\Support\Arr;

class AddressService extends BaseService
{
    public function __construct(Address $address)
    {
        parent::__construct($address, AddressResource::class, AddressInfoResource::class);
    }

    public $filterRules = [
        'place_fullname' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
        'place_phone' => ['like', 'place_phone'],
        'type' => ['=', 'type'],
    ];

    public $orderBy = [
        'updated_at' => 'desc',
    ];

    /**
     * 获取唯一性条件
     * @param $data
     * @return array
     */
    public function getUniqueWhere($data)
    {
        $fields = ['merchant_id', 'place_country', 'place_fullname', 'place_phone', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_address'];
        $where = Arr::only($data, $fields);
        return $where;
    }

    /**
     * 通过唯一组合字段获取信息
     * @param $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoByUnique($data)
    {
        return parent::getInfo(['unique_code' => $this->getUniqueCode($data)], ['*'], false);
    }

    /**
     * 获取唯一码
     * @param $data
     * @return string
     */
    public function getUniqueCode($data)
    {
        $allAddress = '';
        $fields = [
            'type', 'merchant_id', 'place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'
        ];
        foreach ($fields as $k => $v) {
            $allAddress = $allAddress . ($data[$v] ?? '');
        }
        return md5($allAddress);
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], true);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['merchant_id'] = auth()->user()->id;
        $this->check($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * 通过ID 修改
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $this->check($data, $id);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }

    /**
     * 唯一性判断
     * @param $data
     * @param null $id
     * @throws BusinessLogicException
     */
    public function uniqueCheck(&$data, $id = null)
    {
        //判断是否唯一
        $data['unique_code'] = $this->getUniqueCode($data);
        if (!empty($id)) {
            $info = parent::getInfo(['unique_code' => $data['unique_code'], 'id' => ['<>', $id]], ['*'], false);
        } else {
            $info = parent::getInfo(['unique_code' => $data['unique_code']], ['*'], false);
        }
        if (!empty($info)) {
            throw new BusinessLogicException('地址已存在，不能重复添加');
        }
    }

    /**
     * 验证
     * @param $data
     * @param array $dbInfo
     * @throws BusinessLogicException
     */
    public function check(&$data, $id = null)
    {
        if (auth()->user()->company_id !== 'NL') {
            if (empty($data['place_city'])) {
                throw new BusinessLogicException('城市是必填的');
            }
            if (empty($data['place_street'])) {
                throw new BusinessLogicException('街道是必填的');
            }
        }
        if (empty($data['place_lon']) || empty($data['place_lat'])) {
            throw new BusinessLogicException('地址无法定位，请选择其他地址');
        }
        $fields = ['place_fullname', 'place_phone',
            'place_country', 'place_province', 'place_city', 'place_district',
            'place_post_code', 'place_street', 'place_house_number',
            'place_address'];
        foreach ($fields as $v) {
            array_key_exists($v, $data) && $data[$v] = trim($data[$v]);
        }
        if (!empty($id)) {
            $info = parent::getInfo(['id' => $id], ['*'], false);
            if (empty($info)) {
                throw new BusinessLogicException('数据不存在');
            }
        }

        $data['place_country'] = !empty($dbInfo['place_country']) ? $dbInfo['place_country'] : CompanyTrait::getCountry();
        //验证商家是否存在
        $merchant = $this->getMerchantService()->getInfo(['id' => $data['merchant_id']], ['id', 'country'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在，请重新选择货主');
        }
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($data['place_address'])) {
            $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }

        $this->uniqueCheck($data, $id);

    }

    /**
     * 删除
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('删除失败，请重新操作');
        }
    }

    public $address = [
        "fullname",
        "phone",
        "post_code",
        "house_number",
        "city",
        "street",
        "province",
        "district",
        "lon",
        "lat",
        "address"
    ];

    public $place = [
        "place_fullname",
        "place_phone",
        "place_post_code",
        "place_house_number",
        "place_city",
        "place_street",
        "place_province",
        "place_district",
        "place_lon",
        "place_lat",
        "place_address"
    ];

    public $secondPlace = [
        "second_place_fullname",
        "second_place_phone",
        "second_place_post_code",
        "second_place_house_number",
        "second_place_city",
        "second_place_street",
        "second_place_lon",
        "second_place_lat",
        "second_place_province",
        "second_place_district",
        "second_place_address"
    ];

    public $warehouse = [
        "warehouse_fullname",
        "warehouse_phone",
        "warehouse_post_code",
        "warehouse_house_number",
        "warehouse_city",
        "warehouse_street",
        "warehouse_lon",
        "warehouse_lat",
        "warehouse_province",
        "warehouse_district",
        "warehouse_address",
    ];

    /**
     * 地址转第二地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function placeToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->place as $k => $v) {
                $data['second_' . $v] = $from[$v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->place as $k => $v) {
                $data['second_' . $v] = $from[$v] ?? '';
            }
        }
        return $data;
    }

    /**
     * 第二地址转地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function secondPlaceToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->place as $k => $v) {
                $data[$v] = $from['second_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->place as $k => $v) {
                $data[$v] = $from['second_' . $v] ?? '';
            }
        }
        return $data;
    }

    /**
     * 仓库转地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function warehouseToPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->address as $k => $v) {
                $data['place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->address as $k => $v) {
                $data['place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        }
        return $data;
    }

    /**
     * 仓库转第二地址
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function warehouseToSecondPlace($from, $to = [])
    {
        if ($to == []) {
            $data = $from;
            foreach ($this->address as $k => $v) {
                $data['second_place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        } else {
            $data = $to;
            foreach ($this->address as $k => $v) {
                $data['second_place_' . $v] = $from['warehouse_' . $v] ?? '';
            }
        }
        return $data;
    }

    /**
     * 仓库转仓库
     * @param $from
     * @param array $to
     * @return mixed
     */
    public function warehouseToWarehouse($from, $to)
    {
        $data = $to;
        foreach ($this->warehouse as $k => $v) {
            $data[$v] = $from[$v] ?? '';
        }
        return $data;
    }

    /**
     * 互换地址和第二地址
     * @param $data
     * @return mixed
     */
    public
    function changePlaceAndSecondPlace($data)
    {
        $params = [];
        foreach ($this->place as $k => $v) {
            $params[$v] = $data['second_' . $v] ?? '';
            $data['second_' . $v] = $data[$v] ?? '';
            $data[$v] = $params[$v] ?? '';
        }
        return $data;
    }

    public function unsetPlace($data)
    {
        foreach ($this->place as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }

    public function unsetSecondPlace(array $data)
    {
        foreach ($this->secondPlace as $k => $v) {
            unset($data[$v]);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function showByApi(array $data)
    {
        $where = [];
        $array = ['place_country', 'place_post_code', 'place_house_number', 'place_city', 'place_street', 'place_province', 'place_district'];
        foreach ($array as $k => $v) {
            if (!empty($data[$v])) {
                $where[$v] = $data[$v];
            } else {
                $data[$v] = '';
            }
        }
        $address = parent::getInfo($where, ['*'], false);
        dd($address);
        if (empty($address)) {
            $address = LocationTrait::getLocation($data['place_country'], $data['place_city'], $data['place_street'], $data['place_house_number'], $data['place_post_code']);
            foreach ($array as $k => $v) {
                $address['place_' . $v] = $address[str_replace('place_', '', $v)];
            }
        }
        $address = Arr::only($address, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code', 'place_province', 'place_district', 'place_lon', 'place_lat']);
        return $address;
    }
}
