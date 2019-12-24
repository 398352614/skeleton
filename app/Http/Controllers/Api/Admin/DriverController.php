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
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
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
        return self::$driverStatusList;
    }

    /**
     * @api {POST}  api/admin/driver/lock-driver 管理员端:锁定司机
     * @apiName lock-driver
     * @apiGroup admin-driver
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 锁定司机
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "ret":1,
     *  "msg":"添加线路",
     *  "data":{}
     * }
     */
    public function lockDriver(Request $request)
    {
        $data = [
            'is_locked' => $request->is_locked
        ];

        throw_unless(
            $this->service->count($data),
            new BusinessLogicException('司机不存在或者不属于当前公司'),
        );

        return $this->service->updateById($request->id, $data);
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
        return self::$driverTypeList;
    }
}
