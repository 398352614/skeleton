<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\MerchantResource;
use App\Models\Employee;
use App\Models\Merchant;
use App\Traits\CompanyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService extends BaseService
{
    /**
     * AuthService constructor.
     * @param Merchant $model
     */
    public function __construct(Merchant $model)
    {
        parent::__construct($model, MerchantResource::class, MerchantResource::class);
    }

    /**
     * 登录
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function login($params)
    {
        $credentials = [
            $this->username() => $params['username'],
            'password' => $params['password']
        ];

        if (empty(Merchant::query()->where($this->username(), $params['username'])->first())) {
            throw new BusinessLogicException('邮箱未注册，请先注册');
        }

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }

        if (auth('merchant')->user()->status === 2) {
            auth('merchant')->logout();

            throw new BusinessLogicException('暂时无法登录，请联系管理员！');
        }

        auth('merchant')->user()->is_api = false;

        return $this->respondWithToken($token);
    }

    /**
     * 智能判断用户名
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        $username = request()->get('username');

        if (preg_match('/^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7(?:[35678]\d{2}|4(?:0\d|1[0-2]|9\d))|9[189]\d{2}|66\d{2})\d{6}$/', $username)) {
            return 'phone';
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'name';
        }
    }

    /**
     * 看守器
     * Get the guard to be used during authentication.
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('merchant');
    }

    /**
     * 登录成功返回
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'username' => auth('merchant')->user()->name,
            'company_id' => auth('merchant')->user()->company_id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('merchant')->factory()->getTTL() * 60,
            'company_config' => CompanyTrait::getCompany(auth('merchant')->user()->company_id)
        ];
    }

    /**
     * 个人信息
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function me()
    {
        return parent::getInfo(['id' => auth()->user()->id], ['*'], true);
    }

    /**
     * 登出
     * @return string
     */
    public function logout()
    {
        auth('merchant')->logout();

        return '注销成功！';
    }

    /**
     * 刷新令牌
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('merchant')->refresh());
    }

    /**
     * 更新自己的密码
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePassword($params)
    {
        $merchant = \auth('merchant')->user();
        if (!password_verify($params['origin_password'], $merchant->password)) {
            throw new BusinessLogicException('原密码不正确');
        }
        $res = $merchant->update(['password' => bcrypt($params['new_password'])]);
        if ($res) {
            auth('merchant')->logout();
        }
        return success();
    }

    /**
     * 切换时区
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function updateTimezone($params)
    {
        if (empty($params['timezone'])) {
            throw new BusinessLogicException('时区 必填');
        }
        $res = Merchant::query()->where('id', auth()->user()->id)->update(['timezone' => $params['timezone']]);
        if ($res == false) {
            throw new BusinessLogicException('切换时区失败');
        }
        return success();
    }
}
