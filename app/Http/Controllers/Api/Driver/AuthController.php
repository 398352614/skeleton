<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
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
        $this->validateLogin($request);

        $credentials = [
            $this->username() => $request['username'],
            'password'        => $request['password'],
        ];

        if (! $token = $this->guard()->attempt($credentials)) {
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
     * @param  \Illuminate\Http\Request  $request
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
}
