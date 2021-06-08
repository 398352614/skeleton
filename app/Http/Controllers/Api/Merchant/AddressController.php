<?php
/**
 * 联系人管理-收货方 接口
 * User: long
 * Date: 2020/3/16
 * Time: 13:38
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Controllers\BaseController;
use App\Services\Merchant\AddressService;

/**
 * Class AddressController
 * @package App\Http\Controllers\Api\Merchant
 * @property AddressService $service
 */
class AddressController extends BaseController
{
    public function __construct(AddressService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @api {post | get} /admin/api/action 名称
     * @apiGroup 分组名称
     * @apiName 名称
     * @apiPermission admin
     * @apiVersion 1.0.0
     * @apiDescription 接口描述
     * @apiHeader {String} Authorization 请求token
     * @apiParam {String} $param1  参数说明
     * @apiSuccess {Number} ret    状态码，1：请求成功
     * @apiSuccess {String} msg   提示信息
     * @apiSuccess {Object} data    返回数据
     * @apiSampleRequest off
     * @apiSuccessExample {json} Success-Response:
     * {"ret":1,"msg":"","data":[]}
     *
     * @apiErrorExample {json} Error-Response:
     * {"ret":0,"msg":"提错提示"}
     */
    public function index()
    {
        return $this->service->getPageList();
    }

    /**
     * 获取详情
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function show($id)
    {
        return $this->service->show($id);
    }

    /**
     * 新增
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function store()
    {
        return $this->service->store($this->data);
    }

    /**
     * 修改
     * @param $id
     * @return bool|int|void
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * 删除
     * @param $id
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
