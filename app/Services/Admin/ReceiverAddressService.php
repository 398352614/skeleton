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
use App\Models\ReceiverAddress;
use App\Services\BaseService;
use Illuminate\Support\Arr;

class ReceiverAddressService extends BaseService
{
    public function __construct(ReceiverAddress $receiverAddress)
    {
        $this->model = $receiverAddress;
        $this->query = $this->model::query();
        $this->resource = ReceiverAddressResource::class;
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
            throw new BusinessLogicException('修改失败,请重新操作');
        }
    }

    /**
     * 验证
     * @param $data
     * @param null $id
     * @throws BusinessLogicException
     */
    public function check($data, $id = null)
    {
        $where = Arr::only($data, ['receiver', 'receiver_phone', 'receiver_country', 'receiver_post_code', 'receiver_house_number', 'receiver_city', 'receiver_street', 'receiver_address']);
        !empty($id) && Arr::add($where, 'id', ['<>', $id]);
        $otherAddress = parent::getInfo($where, ['*'], false);
        if (!empty($otherAddress)) {
            throw new BusinessLogicException('收货方地址已存在,不能重复添加');
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
            throw new BusinessLogicException('删除失败,请重新操作');
        }
    }


}