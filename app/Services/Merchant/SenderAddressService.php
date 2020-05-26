<?php

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class SenderAddressService extends BaseService
{
    public function __construct(SenderAddress $senderAddress)
    {
        parent::__construct($senderAddress, SenderAddressResource::class, SenderAddressResource::class);
    }

    public $filterRules = [
        'sender_fullname' => ['like', 'sender_fullname'],
        'sender_post_code' => ['like', 'sender_post_code'],
    ];

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
        $info = parent::getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $info;
    }


    /**
     * 联合唯一检验
     * @param $params
     * @param int $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */

    public function check($params, $id = null)
    {
        if (auth()->user()->companyConfig->address_template_id == 1) {
            $fields = ['merchant_id', 'sender_country', 'sender_fullname', 'sender_phone', 'sender_post_code', 'sender_house_number', 'sender_city', 'sender_street'];
            $where = Arr::only($params, $fields);
        } else {
            $where = Arr::only($params, ['merchant_id', 'sender_country', 'sender_fullname', 'sender_phone', 'sender_address']);
        }
        if (!empty($id)) {
            $where['id'] = ['<>', $id];
        }
        $info = parent::getInfo($where, ['*'], true);
        return $info;
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $params['receiver_country'] = CompanyTrait::getCountry();
        if (!empty($this->check($params))) {
            throw new BusinessLogicException('地址新增失败，已有重复地址');
        }
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
        $params['receiver_country'] = CompanyTrait::getCountry();
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
