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
use App\Models\Employee;
use App\Services\BaseConstService;
use App\Services\FeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RongCloud\RongCloud;

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


        if (empty(Employee::query()->where('username',$request['username'])->first())){
            throw new BusinessLogicException('邮箱未注册，请先注册');
        }

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }

        /*if (auth('admin')->user()->is_locked == 1) {
            auth('admin')->logout();

            throw new BusinessLogicException('账户已被锁定，暂时无法登陆');
        }*/
        $params['messager_token'] = auth('driver')->user()->messager;
        if (empty($params['messager_token'])) {
            $params['messager_token'] = $this->getMessagerToken();
        }
        $params['token'] = $token;
        return $this->respondWithToken($params);
    }

    /**
     * 获取messager_token并保存
     * @return mixed
     * @throws BusinessLogicException
     */
    public function getMessagerToken()
    {
        $rcloudApi = new RongCloud(config('tms.messager_key'), config('tms.messager_secret'));
        $user_id = auth('driver')->user()->id;;//用户的id
        $name = auth('driver')->user()->fullname;//用户名称
        $portrait_uri = auth('driver')->user()->avatar;     //用户的头像
        $messagerToken = $rcloudApi->getUser()->register($User = ['id' => $user_id, 'name' => $name, 'portrait' => $portrait_uri])['token'];
        if ($messagerToken === false) {
            throw new BusinessLogicException('无法获取通讯ID，请稍候再试');
        }
        //messager_token写入数据库
        $rowCount = Driver::where('email', auth('driver')->user()->email)->update(['messager' => $messagerToken]);
        if ($rowCount === false) {
            throw new BusinessLogicException('通讯ID存储失败，请重新操作');
        }
        return $messagerToken;
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
        DB::table('driver')->where('email', '=', auth('driver')->user()->email)->update(['messager' => '']);
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
    protected function respondWithToken($params)
    {
        return [
            'username' => auth('driver')->user()->fullname,
            'company_id' => auth('driver')->user()->company_id,
            'access_token' => $params['token'],
            'messager_token' => $params['messager_token'],
            'token_type' => 'bearer',
            'expires_in' => auth('driver')->factory()->getTTL() * 60,
            'company_config' => $this->getCompanyConfig(auth('driver')->user()->company_id)
        ];
    }

    /**
     * 获取企业配置信息
     * @param $companyId
     * @return array
     * @throws BusinessLogicException
     */
    private function getCompanyConfig($companyId)
    {
        //获取贴单费用
        $stickerAmount = FeeService::getFeeAmount(['company_id' => $companyId, 'code' => BaseConstService::STICKER]);
        return ['sticker_amount' => $stickerAmount];
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
        if (empty(Employee::query()->where('username',$request['username'])->first())){
            throw new BusinessLogicException('用户不存在，请检查用户名');
        }

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
