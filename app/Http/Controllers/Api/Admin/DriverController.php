<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Services\Admin\DriverService;
use App\Traits\ConstTranslateTrait;
use Illuminate\Http\Request;

class DriverController extends BaseController
{
    use ConstTranslateTrait;

    public $service;

    public function __construct(DriverService $service)
    {
        $this->service = $service;
    }

    /**
     * @api {GET}  api/admin/driver 管理员端:司机列表
     * @apiName index
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
    public function index(Request $request)
    {
        return $this->service->getPageList();
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
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"修改司机",
     *  "data":{}
     * }
     */
    public function update(Request $request, $id)
    {
        return $this->service->update(['id' => $id], $request->validated);
    }

    /**
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
    public function resetPassword(Request $request, $id)
    {
        return $this->service->resetPassword($id, $request->validated['password']);
    }

    /**
     * @api {DELETE}  api/admin/driver/{driver} 管理员端:删除司机
     * @apiName destroy
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 删除司机
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"查询司机",
     *  "data":{}
     * }
     */
    public function destroy(Request $request, $id)
    {
        throw new BusinessLogicException('司机暂不提供删除');
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
        $data = array_values(collect(self::$driverStatusList)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
    }

    /**
     * @api {POST}  api/admin/driver/lock-driver 管理员端:锁定司机
     * @apiName lock-driver
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 锁定司机
     * @throws
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
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

    /**
     * @api {GET}  api/admin/driver/crop-type 管理员端:合作类型
     * @apiName crop-type
     * @apiGroup admin-driver-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 合作类型
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function cropType()
    {
        $data = array_values(collect(self::$driverTypeList)->map(function ($value, $key) {
            return collect(['id' => $key, 'name' => $value]);
        })->toArray());
        return $data;
    }
}
