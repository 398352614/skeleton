<?php
/**
 * @Author: h9471
 * @Created: 2019/9/9 18:19
 */

namespace App\Http\Controllers\Api\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Api\Admin\RegisterController;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Driver;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use App\Services\FeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function login(Request $request)
    {
        $credentials = [
            $this->username() => $request['username'],
            'password' => $request['password'],
        ];

        if (empty(Driver::query()->where($this->username(), $request['username'])->first())) {
            throw new BusinessLogicException('邮箱未注册，请先注册');
        }

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }
        $params['messager_token'] = auth('driver')->user()->messager;

        if (empty($params['messager_token'])) {
            $params['messager_token'] = '';
        }
        $params['token'] = $token;
        return $this->respondWithToken($params);
    }

    /**
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = auth('driver')->user();
        $device = Device::query()->where('driver_id', $user->id)->first();
        $user = $user->getAttributes();
        $user['is_bind'] = !empty($device) ? 1 : 2;
        return response()->json($user);
    }

    /**
     * @return string
     */
    public function logout()
    {
        auth('driver')->logout();
        return 'true';
    }

    /**
     * @return array
     * @throws BusinessLogicException
     */
    public function refresh()
    {
        $token = auth('driver')->refresh();
        $messageToken = auth('driver')->user()->messager;
        if (empty($messageToken)) {
            $messageToken = '';
        }
        return $this->respondWithToken(['token' => $token, 'messager_token' => $messageToken]);
    }

    /**
     * @param $params
     * @return array
     * @throws BusinessLogicException
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
            'company_config' => $this->getCompanyConfig(auth('driver')->user()->company_id),
            'warehouse' => $this->getWarehouse(auth('driver')->user()->warehouse_id),
            'is_bind' => $this->isBindDevice(auth('driver')->user()->id)
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
        $deliveryAmount = FeeService::getFeeAmount(['company_id' => $companyId, 'code' => BaseConstService::DELIVERY]);
        return [
            'sticker_amount' => $stickerAmount,
            'delivery_amount' => $deliveryAmount
        ];
    }

    private function getWarehouse($warehouseId)
    {
        $warehouse = Warehouse::query()->where('id', $warehouseId)->first()->toArray();
        $warehouse = Arr::only($warehouse, ['id', 'name', 'is_center']);
        return $warehouse;
    }

    /**
     * 是否绑定设备
     * @param $driverId
     * @return bool
     */
    private function isBindDevice($driverId)
    {
        $device = Device::query()->where('driver_id', $driverId)->first();
        return !empty($device) ? 1 : 2;
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
        if (empty(Driver::query()->where('email', $request['email'])->first())) {
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

    /**
     * 修改时区
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function updateTimezone(Request $request)
    {
        $data = $request->all();
        if(empty($data['timezone'])){
            throw new BusinessLogicException('时区 必填');
        }
        $res =DB::table('driver')->where('id', auth()->user()->id)->update(['timezone' => $data['timezone']]);
        return success();
    }
}
