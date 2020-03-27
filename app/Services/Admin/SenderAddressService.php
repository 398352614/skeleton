<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Models\SenderAddress;
use App\Services\BaseService;
use App\Http\Resources\SenderAddressResource;

class SenderAddressService extends BaseService
{
    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id']
    ];

    public function __construct(SenderAddress $senderAddress)
    {
        parent::__construct($senderAddress, SenderAddressResource::class, SenderAddressResource::class);
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
     * 商户是否存在验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function checkMerchant($params)
    {
        if (empty(Merchant::query()->where('id', $params['merchant_id'])->first())) {
            throw new BusinessLogicException('商户不存在，请重新选择商户');
        }
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
        $info = parent::getInfo($data, ['*'], true);
        return $info;
    }

    /**
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->checkMerchant($params);
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
        $this->checkMerchant($data);
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
