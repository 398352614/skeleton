<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
use App\Models\Employee;
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
            'password'        => $request['password']
        ];

        if (! $token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }

        /*if (auth('admin')->user()->forbid_login === true) {
            auth('admin')->logout();

            eRet('暂时无法登录，请联系管理员！', 401);
        }*/

        return $this->respondWithToken($token);
    }

    /**
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth('admin')->user());
    }

    /**
     * @return string
     */
    public function logout()
    {
        auth('admin')->logout();

        return '注销成功！';
    }

    /**
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    /**
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'username' => auth('admin')->user()->fullname,
            'company_id' => auth('admin')->user()->company_id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
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

        if (preg_match('/^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7(?:[35678]\d{2}|4(?:0\d|1[0-2]|9\d))|9[189]\d{2}|66\d{2})\d{6}$/', $username)) {
            return 'phone';
        } elseif (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'fullname';
        }
    }
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * 更新自己的密码
     *
     * @param  Request  $request
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'origin_password' => 'required|string|between:8,20',
            'new_password' => 'required|string|between:8,20|different:origin_password',
            'new_confirm_password' => 'required|same:new_password',
        ]);

        /** @var Employee $employee */
        $employee = \auth('admin')->user();

        if (!password_verify($data['origin_password'], $employee->password)) {
            throw new BusinessLogicException('原密码不正确');
        }

        $res = $employee->update(
            [
                'password' => bcrypt($data['new_password'])
            ]
        );

        if ($res) {
            auth('admin')->logout();
        }

        return success();
    }
}
