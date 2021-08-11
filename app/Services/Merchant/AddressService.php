<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
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
        parent::__construct($address, AddressResource::class, AddressResource::class);
    }

    public $filterRules = [
        'place_fullname' => ['like', 'place_fullname'],
        'place_post_code' => ['like', 'place_post_code'],
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
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        return $info;
    }

    /**
     * 通过唯一组合字段获取信息
     * @param $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoByUnique($data)
    {
        return parent::getInfo($this->getUniqueWhere($data), ['*'], false);
    }


    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('新增失败,请重新操作');
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
        $this->check($data, $info->toArray());
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * 验证
     * @param $data
     * @param array $dbInfo
     * @throws BusinessLogicException
     */
    public function check(&$data, $dbInfo = [])
    {
        $data['place_country'] = !empty($dbInfo['place_country']) ? $dbInfo['place_country'] : CompanyTrait::getCountry();
        if ((CompanyTrait::getAddressTemplateId() == 1) || empty($data['place_address'])) {
            $data['place_address'] = CommonService::addressFieldsSortCombine($data, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code']);
        }
        //判断是否唯一
        $where = $this->getUniqueWhere($data);
        !empty($dbInfo['id']) && $where = Arr::add($where, 'id', ['<>', $dbInfo['id']]);
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('地址已存在，不能重复添加');
        }

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

    /**
     * @param array $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws BusinessLogicException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function showByApi(array $data)
    {
        $where = [];
        $array = ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code', 'place_province', 'place_district', 'place_lon', 'place_lat'];
        foreach ($array as $k => $v) {
            if (!empty($data[$v])) {
                $where[$v] = $data[$v];
            } else {
                $where[$v] = '';
            }
        }
        $address = parent::getInfo($where, ['*'], false);
        if (empty($address)) {
            $info = LocationTrait::getLocation($where['place_country'], $where['place_city'], $where['place_street'], $where['place_house_number'], $where['place_post_code']);
            foreach ($array as $k => $v) {
                $address[$v] = $info[str_replace('place_', '', $v)];
            }
        } else {
            $address = collect($address)->toArray();
        }
        $address = Arr::only($address, ['place_country', 'place_city', 'place_street', 'place_house_number', 'place_post_code', 'place_province', 'place_district', 'place_lon', 'place_lat']);
        return $address;
    }

}
