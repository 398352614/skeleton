<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class SenderAddressService extends BaseService
{
    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id'],
        'sender_fullname' => ['like', 'sender_fullname'],
        'sender_post_code' => ['like', 'sender_post_code']
    ];

    public function __construct(SenderAddress $senderAddress)
    {
        parent::__construct($senderAddress, SenderAddressResource::class, SenderAddressResource::class);
    }

    /**
     * 商户 服务
     * @return MerchantService
     */
    private function getMerchantService()
    {
        return self::getInstance(MerchantService::class);
    }

    /**
     * 获取唯一性条件
     * @param $data
     * @return array
     */
    public function getUniqueWhere($data)
    {
        $where = [];
        if (CompanyTrait::getAddressTemplateId() == 1) {
            $fields = ['merchant_id', 'sender_country', 'sender_fullname', 'sender_phone', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street'];
            $where = Arr::only($data, $fields);
        } else {
            $where = Arr::only($data, ['merchant_id', 'sender_country', 'sender_fullname', 'sender_phone', 'sender_address']);
        }
        return $where;
    }

    public function index()
    {
        return parent::getpagelist();
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
     * 通过唯一组合字段获取信息
     * @param $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getInfoByUnique($data)
    {
        return parent::getInfo($this->getUniqueWhere($data), ['*'], false);
    }


    /**
     * 联合唯一检验
     * @param $params
     * @param null $id
     * @throws BusinessLogicException
     */
    private function check(&$data, $id = null)
    {
        $data['sender_country'] = CompanyTrait::getCountry();
        //验证商家是否存在
        $merchant = $this->getMerchantService()->getInfo(['id' => $data['merchant_id']], ['id', 'country'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('商户不存在，请重新选择商户');
        }
        //判断是否唯一
        $where = $this->getUniqueWhere($data);
        !empty($id) && $where = Arr::add($where, 'id', ['<>', $id]);
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('发货方地址已存在，不能重复添加');
        }
        if (CompanyTrait::getAddressTemplateId() == 1) {
            $data['sender_address'] = implode(' ', array_filter(Arr::only($data, ['sender_country', 'sender_city', 'sender_street', 'sender_post_code', 'sender_house_number'])));
        }
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->check($params);
        $rowCount = parent::create($params);
        if ($rowCount === false) {
            throw new BusinessLogicException('地址新增失败');
        }
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function updateById($id, $data)
    {
        $info = $this->check($data, $id);
        if (!empty($info)) {
            throw new BusinessLogicException('发货方地址已存在，不能重复添加');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('地址修改失败');
        }
    }

    /**
     * @param $id
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        $rowCount = parent::delete(['id' => $id]);
        if ($rowCount === false) {
            throw new BusinessLogicException('地址删除失败');
        }
    }
}
