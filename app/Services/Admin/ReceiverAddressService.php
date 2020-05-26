<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\ReceiverAddressResource;
use App\Models\Merchant;
use App\Models\ReceiverAddress;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class ReceiverAddressService extends BaseService
{

    public $filterRules = [
        'merchant_id' => ['=', 'merchant_id'],
        'receiver_fullname' => ['like', '_fullname'],
        'receiver_post_code' => ['like', 'receiver_post_code']
    ];

    public function __construct(ReceiverAddress $receiverAddress)
    {
        parent::__construct($receiverAddress, ReceiverAddressResource::class, ReceiverAddressResource::class);
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
     * 商户是否存在验证
     * @param $params
     * @throws BusinessLogicException
     */
    public function checkMerchant(&$params)
    {
        $merchant = $this->getMerchantService()->getInfo(['id' => $params['merchant_id']], ['id', 'country'], false);
        if (empty($merchant)) {
            throw new BusinessLogicException('商户不存在，请重新选择商户');
        }
        $params['receiver_country'] = CompanyTrait::getCountry();
    }

    /**
     * 新增
     * @param $params
     * @throws BusinessLogicException
     */
    public function store($params)
    {
        $this->checkMerchant($params);
        if (!empty($this->check($params))) {
            throw new BusinessLogicException('收货方地址已存在，不能重复添加');
        }
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
        $this->checkMerchant($data);
        if (!empty($this->check($data, $id))) {
            throw new BusinessLogicException('收货方地址已存在，不能重复添加');
        }
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * 验证
     * @param $data
     * @param null $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function check($data, $id = null)
    {
        if (auth()->user()->companyConfig->address_template_id == 1) {
            $fields = ['merchant_id', 'receiver_country', 'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street'];
            $where = Arr::only($data, $fields);
        } else {
            $where = Arr::only($data, ['merchant_id', 'receiver_country', 'receiver_fullname', 'receiver_phone', 'receiver_address']);
        }
        if (!empty($id)) {
            $where = Arr::add($where, 'id', ['<>', $id]);
        }
        return parent::getInfo($where, ['*'], false);
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
