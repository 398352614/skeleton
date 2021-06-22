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

    public $orderBy=[
        'updated_at'=>'desc',
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
    public function check(&$data,  $id = null)
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


}
