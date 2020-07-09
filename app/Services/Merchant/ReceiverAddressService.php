<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;


use App\Exceptions\BusinessLogicException;
use App\Http\Resources\ReceiverAddressResource;
use App\Models\Merchant;
use App\Models\ReceiverAddress;
use App\Services\BaseService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;

class ReceiverAddressService extends BaseService
{
    public function __construct(ReceiverAddress $receiverAddress)
    {
        parent::__construct($receiverAddress, ReceiverAddressResource::class, ReceiverAddressResource::class);
    }

    public $filterRules = [
        'receiver_fullname' => ['like', 'receiver_fullname'],
        'receiver_post_code' => ['like', 'receiver_post_code'],
    ];

    /**
     * 获取唯一性条件
     * @param $data
     * @return array
     */
    public function getUniqueWhere($data)
    {
        $fields = ['merchant_id', 'receiver_country', 'receiver_fullname', 'receiver_phone', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address'];
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
        $this->check($data, $id);
        $rowCount = parent::updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('修改失败，请重新操作');
        }
    }


    /**
     * 验证
     * @param $data
     * @param null $id
     * @throws BusinessLogicException
     */
    public function check(&$data, $id = null)
    {
        $data['receiver_country'] = CompanyTrait::getCountry();
        $fields = ['receiver_country', 'receiver_city', 'receiver_street', 'receiver_post_code', 'receiver_house_number'];
        //当修改时，若数据未修改，则不处理
        if (!empty($id)) {
            $dbInfo = parent::getInfo(['id' => $id], ['*'], false);
            if (empty($dbInfo)) {
                throw new BusinessLogicException('数据不存在');
            }
            //若数据未修改,则返回成功
            if (empty(array_diff(Arr::only($dbInfo->toArray(), $fields), Arr::only($data, $fields)))) {
                $data['receiver_address'] = $dbInfo->receiver_address;
            }
        }
        if (empty($data['receiver_address'])) {
            $data['receiver_address'] = implode(' ', array_filter(array_only_fields_sort($data, ['receiver_country', 'receiver_city', 'receiver_street', 'receiver_post_code', 'receiver_house_number'])));
        }
        //判断是否唯一
        $where = $this->getUniqueWhere($data);
        !empty($id) && $where = Arr::add($where, 'id', ['<>', $id]);
        $info = parent::getInfo($where, ['*'], false);
        if (!empty($info)) {
            throw new BusinessLogicException('收货方地址已存在，不能重复添加');
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


}
