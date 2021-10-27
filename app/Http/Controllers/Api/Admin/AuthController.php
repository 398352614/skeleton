<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\AuthService;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api\Merchant
 * @property AuthService $service
 */
class AuthController extends BaseController
{
    /**
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    /**
     * 登录
     * @return mixed
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function login()
    {
        return $this->service->login($this->data);
    }

    /**
     * 个人信息
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function me()
    {
        return $this->service->me();
    }

    /**
     * 登出
     * @return string
     */
    public function logout()
    {
        return $this->service->logout();
    }

    /**
     * 刷新令牌
     * @return array
     */
    public function refresh()
    {
        return $this->service->refresh();
    }

    /**
     * 更新密码
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function updatePassword()
    {
        return $this->service->updatePassword($this->data);
    }

    /**
     * 切换时区
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function updateTimezone()
    {
        return $this->service->updateTimezone($this->data);
    }

    /**
     * 获取权限
     * @return array
     * @throws \App\Exceptions\BusinessLogicException
     */
    public function getPermission()
    {
        return $this->service->getPermission();
    }
}
