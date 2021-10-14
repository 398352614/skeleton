<?php
/**
 * 费用 接口
 * User: long
 * Date: 2020/6/22
 * Time: 14:05
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\FeeService;

/**
 * 费用
 * Class FeeController
 * @package App\Http\Controllers\Api\Admin
 * @property FeeService $service
 */
class FeeController extends BaseController
{
    public function __construct(FeeService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
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
        $fee = $this->service->getInfo(['id' => $id], ['*'], false);
        if (empty($fee)) {
            throw new BusinessLogicException('数据不存在');
        }
        return $fee->toArray();
    }

    public function init()
    {
        return $this->service->init();
    }

    /**
     * 新增
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
     * 删除
     * @param $id
     * @return string
     * @throws BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
