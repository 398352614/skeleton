<?php
/**
 * 客户管理-收货方 服务
 * User: long
 * Date: 2020/1/10
 * Time: 13:41
 */

namespace App\Services\Driver;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Driver\DriverResource;
use App\Models\Device;
use App\Models\Driver;
use App\Models\Fee;
use App\Models\Warehouse;
use App\Services\BaseConstService;
use App\Services\FeeService;
use App\Traits\CompanyTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class AuthService extends BaseService
{
    /**
     * AuthService constructor.
     * @param Driver $model
     */
    public function __construct(Driver $model)
    {
        parent::__construct($model, DriverResource::class, DriverResource::class);
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

        if (empty(Driver::query()->where($this->username(), $params['username'])->first())) {
            throw new BusinessLogicException('邮箱未注册，请先注册');
        }

        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessLogicException('用户名或密码错误！');
        }

        if (auth('driver')->user()->status === 2) {
            auth('driver')->logout();

            throw new BusinessLogicException('暂时无法登录，请联系管理员！');
        }

        auth('driver')->user()->is_api = false;

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
        return Auth::guard('driver');
    }

    /**
     * 登录成功返回
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
            'is_bind' => $this->isBindDevice(auth('driver')->user()->id),
//            'fee_config' => $this->getFeeConfig(auth('driver')->user()->company_id),
            'old_fee' => (auth('driver')->user()->company_id == config('tms.old_company_id')) ? BaseConstService::YES : BaseConstService::NO];
    }

    /**
     * 个人信息
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
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
     * 登出
     * @return string
     */
    public function logout()
    {
        auth('driver')->logout();

        return __('注销成功');
    }

    /**
     * 刷新令牌
     * @return array
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('driver')->refresh());
    }

    /**
     * 更新自己的密码
     * @param $params
     * @return array
     * @throws BusinessLogicException
     */
    public function updatePassword($params)
    {
        $driver = auth('driver')->user();
        if (!password_verify($params['origin_password'], $driver->password)) {
            throw new BusinessLogicException('原密码不正确');
        }
        $res = $driver->update(['password' => bcrypt($params['new_password'])]);
        if ($res) {
            auth('driver')->logout();
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
        $res = driver::query()->where('id', auth()->user()->id)->update(['timezone' => $params['timezone']]);
        if ($res == false) {
            throw new BusinessLogicException('切换时区失败');
        }
        return success();
    }

    /**
     * 获取公司配置信息
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
        $data = [];
        $warehouse = Warehouse::query()->where('id', $warehouseId)->first();
        if (!empty($warehouse)) {
            $data = Arr::only($warehouse->toArray(), ['id', 'name', 'is_center', 'can_select_all']);
        }
        return $data;
    }

    private function getFeeConfig($companyId)
    {
        $fee = Fee::query()->where('company_id', $companyId)->where('level', BaseConstService::FEE_LEVEL_2)->where('status', BaseConstService::BILL_STATUS_1)->get();
        return $fee;
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
}
