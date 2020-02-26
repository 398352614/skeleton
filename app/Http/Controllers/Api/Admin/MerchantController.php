<?php
/**
 * 商户列表
 * User: long
 * Date: 2020/1/3
 * Time: 16:26
 */

namespace App\Http\Controllers\Api\Admin;


use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Models\MerchantGroup;
use App\Services\Admin\MerchantService;

/**
 * Class BatchExceptionController
 * @package App\Http\Controllers\Api\Admin
 * @property MerchantService $service
 */
class MerchantController extends BaseController
{
    public function __construct(MerchantService $service)
    {
        parent::__construct($service);
    }

    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array
     * @throws BusinessLogicException
     */
    public function show($id)
    {
        $info = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($info)) {
            throw new BusinessLogicException('数据不存在');
        }
        $info = $info->toArray();
        $info['merchant_group_name']=MerchantGroup::query()->where('id',$info['merchant_group_id'])->value('name');
        unset($info['password']);
        return $info;
    }

    /**
     * 新增
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 修改密码
     * @param $id
     * @throws BusinessLogicException
     */
    public function updatePassword($id)
    {
        return $this->service->updatePassword($id, $this->data);
    }

    /**
     * 状态-启用/禁用
     * @param $id
     * @throws BusinessLogicException
     */
    public function status($id)
    {
        return $this->service->status($id, $this->data);
    }
}
