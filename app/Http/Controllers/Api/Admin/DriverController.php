<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Admin\DriverService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Request;

/**
 * Class DriverController
 * @package App\Http\Controllers\Api\Admin
 * @property DriverService $service
 */
class DriverController extends BaseController
{
    use ConstTranslateTrait;

    /**
     * DriverController constructor.
     * @param DriverService $service
     */
    public function __construct(DriverService $service)
    {
        parent::__construct($service);
    }


    public function index(Request $request)
    {
        return $this->service->getPageList();
    }

    public function store($params)
    {
        return $this->service->store($params);
    }

    /**
     * @api {GET}  api/admin/driver/{driver} 管理员端:查询司机
     * @apiName show
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 查询司机
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function show($id)
    {
        return $this->service->getInfo(['id' => $id], ['*'], true);
    }

    /**
     * @api {PUT}  api/admin/driver/{driver} 管理员端:修改司机
     * @apiName update
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 修改司机
     * @apiSuccessExample {json}  返回示例
     * @throws BusinessLogicException
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"修改司机",
     *  "data":{}
     * }
     */
    public function update($id)
    {
        return $this->service->updateById($id, $this->data);
    }

    /**
     * @param $id
     * @return void
     * @throws BusinessLogicException
     * @api {POST}  api/admin/driver/{driver}/reset-password 管理员端:重置密码
     * @apiName reset-password
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 重置密码
     * @apiParam {String}   password                    密码
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"修改司机",
     *  "data":{}
     * }
     */
    public function resetPassword($id)
    {
        return $this->service->resetPassword($id, $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @throws BusinessLogicException HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     * @api {DELETE}  api/admin/driver/{driver} 管理员端:删除司机
     * @apiName destroy
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 删除司机
     * @apiSuccessExample {json}  返回示例
     */
    public function destroy($id)
    {
        $this->service->destroy($id);
    }

    /**
     * @throws BusinessLogicException
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     * @api {POST}  api/admin/driver/driver-register 管理员端:司机添加
     * @apiName driver-register
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 司机添加
     * @apiParam {String}   email                  有序的批次数组
     * @apiParam {String}   password            司机当前位置
     * @apiParam {String}   last_name            司机当前位置
     * @apiParam {String}   first_name            司机当前位置
     * @apiParam {String}   gender            司机当前位置
     * @apiParam {String}   birthday            司机当前位置
     * @apiParam {String}   phone            司机当前位置
     * @apiParam {String}   duty_paragraph            司机当前位置
     * @apiParam {String}   post_code            司机当前位置
     * @apiParam {String}   door_no            司机当前位置
     * @apiParam {String}   street            司机当前位置
     * @apiParam {String}   city                    在途编号
     * @apiParam {String}   country                    在途编号
     * @apiParam {String}   lisence_number                    在途编号
     * @apiParam {String}   lisence_valid_date                    在途编号
     * @apiParam {String}   lisence_type                    在途编号
     * @apiParam {String}   lisence_material                    在途编号
     * @apiParam {String}   government_material                    在途编号
     * @apiParam {String}   avatar                    在途编号
     * @apiParam {String}   bank_name                    在途编号
     * @apiParam {String}   iban                    在途编号
     * @apiParam {String}   bic                    在途编号
     * @apiParam {String}   crop_type                    合作类型
     */
    public function driverRegister(Request $request)
    {
        return $this->service->driverRegister();
    }

    /**
     * @api {GET}  api/admin/driver/driver-status 管理员端:司机状态
     * @apiName driver-status
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 司机状态
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function driverStatus()
    {
        $data = ConstTranslateTrait::formatList(ConstTranslateTrait::$driverStatusList);
        return $data;
    }

    /**
     * @throws
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     * @api {POST}  api/admin/driver/lock-driver 管理员端:锁定司机
     * @apiName lock-driver
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 锁定司机
     */
    public function lockDriver(Request $request, $id)
    {
        $data = [
            'is_locked' => $request->is_locked
        ];

        throw_unless(
            $this->service->count(['id' => $id]),
            new BusinessLogicException('司机不存在或者不属于当前公司')
        );

        $rowCount = $this->service->updateById($id, $data);
        if ($rowCount === false) {
            throw new BusinessLogicException('操作失败');
        }
        return;
    }
}
