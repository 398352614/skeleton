<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;

class SenderAddressService extends BaseService
{
    public function __construct(SenderAddress $senderaddress)
    {
        $this->request = request();
        $this->model = $senderaddress;
        $this->query = $this->model::query();
        $this->resource = SenderAddressResource::class;
        $this->infoResource = SenderAddressResource::class;
        $this->setFilterRules();
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
     * 联合唯一检验
     * @param $params
     * @param int $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */

    public function check($params, $id = null)
    {
        $data = [
            'merchant_id' => $params['merchant_id'],
            'sender' => $params['sender'],
            'sender_phone' => $params['sender_phone'],
            'sender_country' => $params['sender_country'],
            'sender_post_code' => $params['sender_post_code'],
            'sender_house_number' => $params['sender_house_number'],
            'sender_city' => $params['sender_city'],
            'sender_street' => $params['sender_street'],
        ];
        if (!empty($id)) {
            $data['id'] = ['<>', $id];
        }
        $info = parent::getInfo([$data], ['*'], true);

        return $info;
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        if (!empty($this->Check($params))) {
            throw new BusinessLogicException('地址新增失败，已有重复地址');
        }
        $rowCount = parent::create($params);
        if (empty($rowCount)) {
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
        $this->Check($data, $id);
        if (!empty($info)) {
            throw new BusinessLogicException('发货方地址已存在,不能重复添加');
        }
        $rowCount = parent::updateById($id, $data);
        if (empty($rowCount)) {
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
        if (empty($rowCount)) {
            throw new BusinessLogicException('地址删除失败');
        }
    }
}
