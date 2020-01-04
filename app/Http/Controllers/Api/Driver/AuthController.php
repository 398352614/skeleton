<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\RegisterController;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function login(Request $request)
    {
        //$this->validateLogin($request);

        $credentials = [
            $this->username() => $request['username'],
            'password' => $request['password'],
        ];

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误');
        }

        /*if (auth('admin')->user()->is_locked == 1) {
            auth('admin')->logout();

            throw new BusinessLogicException('账户已被锁定，暂时无法登陆');
        }*/

        return $this->respondWithToken($token);
    }

    /**
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth('driver')->user());
    }

    /**
     * @return string
     */
    public function logout()
    {
        auth('driver')->logout();

        return '注销成功！';
    }

    /**
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('driver')->refresh());
    }

    /**
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'username' => auth('driver')->user()->fullName,
            'company_id' => auth('driver')->user()->company_id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('driver')->factory()->getTTL() * 60
        ];
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        $username = request()->get('username');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'phone';
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('driver');
    }

    /**
     * 重置密码
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function resetPassword(Request $request)
    {
//        $data = $request->validate([
//            'new_password' => 'required|string|between:8,20',
//            'confirm_new_password' =>'required|string|same:new_password',
//            'email' => 'required|email',
//            'code'  => 'required|string|digits_between:6,6'
//        ]);
        $data = $request->all();

        if ($data['code'] !== RegisterController::getVerifyCode($data['email'], 'RESET')) {
            throw new BusinessLogicException('验证码错误');
        }

        RegisterController::deleteVerifyCode($data['email'], 'RESET');

        $driver = Driver::where('email', $data['email'])->firstOrFail();

        $res = $driver->update([
            'password' => bcrypt($data['new_password']),
        ]);

        if ($res === false) {
            return failed();
        }

        auth('driver')->logout();

        return success();
    }

    /**
     * 重置密码验证码
     *
     * @param Request $request
     * @return string
     * @throws BusinessLogicException
     */
    public function applyOfReset(Request $request)
    {
//        $request->validate([
//            'email' => 'required|email',
//        ]);

        return RegisterController::sendCode($request->input('email'), 'RESET');
    }

    /**
     * 更新自己的密码
     *
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePassword(Request $request)
    {
//        $data = $request->validate([
//            'origin_password' => 'required|string|between:8,20',
//            'new_password' => 'required|string|between:8,20|different:origin_password',
//            'new_confirm_password' => 'required|same:new_password',
//        ]);
        $data = $request->all();

        /** @var Driver $driver */
        $driver = \auth('driver')->user();

        if (!password_verify($data['origin_password'], $driver->password)) {
            throw new BusinessLogicException('原密码不正确');
        }

        $res = $driver->update(
            [
                'password' => bcrypt($data['new_password'])
            ]
        );

        if (!$res) {
            return failed();
        }

        return success();
    }
}
