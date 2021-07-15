<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Services\BaseConstService;
use App\Services\TreeService;
use App\Traits\CompanyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
            'password' => $request['password']
        ];

        if (empty(Employee::query()->where($this->username(), $request['username'])->first())) {
            throw new BusinessLogicException('邮箱未注册，请先注册');
        }

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }

        if (auth('admin')->user()->forbid_login == 1) {
            auth('admin')->logout();

            throw new BusinessLogicException('账户已被禁用，请联系管理员！');
        }
        return $this->respondWithToken($token);
    }

    public function validation()
    {
        $validate = [];
        $cnValidate = include base_path('resources/lang/cn/validation.php');
        $cnValidate = array_flip($cnValidate['attributes']);
        $enValidate = include base_path('resources/lang/en/validation.php');
        $enValidate = $enValidate['attributes'];
        foreach ($cnValidate as $k => $v) {
            $validate[$k] = $enValidate[$v] ?? $v;
        }
        $translate = file_get_contents(base_path('resources/lang/en.json'));
        $translate = collect(json_decode($translate))->toArray();
        return array_merge($translate, $validate);
    }

    /**
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = auth('admin')->user();
        //不可删除
        $companyConfig = $user->companyConfig;
        return response()->json($user);
    }

    /**
     * 获取当前用户权限
     * @return array
     * @throws BusinessLogicException
     */
    public function getPermission()
    {
        /**@var Role $role */
        $role = auth('admin')->user()->roles->first();
        if (empty($role)) return [];
        $rolePermissionList = $role->getAllPermissions();
        if ($rolePermissionList->isEmpty()) return [];
        $rolePermissionList = $rolePermissionList->map(function ($permission, $key) {
            return $permission->only(['id', 'parent_id', 'name', 'route_as', 'type']);
        });
        $rolePermissionList = array_create_group_index($rolePermissionList->toArray(), 'type');
        return [
            'permission_list' => $rolePermissionList[BaseConstService::PERMISSION_TYPE_2],
            'menu_list' => TreeService::makeTree($rolePermissionList[BaseConstService::PERMISSION_TYPE_1])
        ];
    }

    /**
     * @return string
     */
    public function logout()
    {
        auth('admin')->logout();

        return __('注销成功');
    }

    /**
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh(true));
    }

    /**
     * @param $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        /**@var Role $role */
        $role = auth('admin')->user()->roles->first();
        if (empty($role)) {
            $permissionAuth = 2;
        } else {
            $rolePermissionList = $role->getAllPermissions();
            $permissionAuth = $rolePermissionList->isEmpty() ? 2 : 1;
            unset($rolePermissionList);
        }
        return [
            'username' => auth('admin')->user()->fullname,
            'company_id' => auth('admin')->user()->company_id,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'is_permission' => $permissionAuth,
            'company_config' => CompanyTrait::getCompany(auth('admin')->user()->company_id)
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
        auth('admin')->refresh(true);
        if ($res) {
            auth('admin')->logout();
        }
        return success();
    }

    /**
     * 修改时区
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function updateTimezone(Request $request)
    {
        $data = $request->all();
        if (empty($data['timezone'])) {
            throw new BusinessLogicException('时区 必填');
        }
        $res = Employee::query()->where('id', auth()->user()->id)->update(['timezone' => $data['timezone']]);
        if ($res == false) {
            throw new BusinessLogicException('切换时区失败');
        }
        return success();
    }
}
