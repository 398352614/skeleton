<?php
/**
 * @Author: h9471
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Mail\SendRegisterCode;
use App\Mail\SendResetCode;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Institution;
use App\Models\KilometresCharging;
use App\Models\MapConfig;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Models\MerchantGroup;
use App\Models\OrderDefaultConfig;
use App\Models\OrderNoRule;
use App\Models\OrderTemplate;
use App\Models\Role;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\Warehouse;
use App\Models\WeightCharging;
use App\Services\Admin\CompanyService;
use App\Services\Admin\WareHouseService;
use App\Services\BaseConstService;
use App\Traits\PermissionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegisterController extends BaseController
{
    use PermissionTrait;

    public function __construct(CompanyService $service, $exceptMethods = [])
    {
        parent::__construct($service, $exceptMethods);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->all();

//        if ($data['code'] !== RegisterController::getVerifyCode($data['email'])) {
//            throw new BusinessLogicException('验证码错误');
//        }

        RegisterController::deleteVerifyCode($data['email']);

        throw_if(
            Employee::where('email', $data['email'])->count(),
            new BusinessLogicException('账号已注册，请直接登录')
        );

        throw_if(
            Company::where('email', $data['email'])->count(),
            new BusinessLogicException('账号已注册，请直接登录')
        );

        DB::transaction(function () use ($data) {

            $lastCompany = Company::lockForUpdate()->orderBy('id', 'desc')->first();
            $company = Company::create([
                'company_code' => self::makeNewCompanyCode($lastCompany),
                'email' => $data['email'],
                'name' => $data['name'],
                'contacts' => $data['email'],
                //'phone' => $data['phone'],
            ]);
            if ($company === false) {
                throw new BusinessLogicException('企业注册失败');
            }
            $warehouse = $this->addWarehouse($company, $data);
            $employee = $this->addEmployee($company, $data, $warehouse);//初始化管理员帐户
            $role = $this->addRole($company);//初始化权限组
            $this->addPermission($employee, $role);//初始化员工权限组
            $this->initCompanyOrderCodeRules($company);//初始化编号规则
            $transportPrice = $this->addTransportPrice($company);//初始化运价方案
            $merchantGroup = $this->addMerchantGroup($company, $transportPrice);//初始化货主组
            $merchant = $this->addMerchant($company, $merchantGroup,$warehouse);//初始化货主API
            $this->addMerchantApi($company, $merchant);//初始化货主API
            $this->addFee($company);//添加费用
            $this->addOrderTemplate($company);//添加打印模板
            $this->addOrderDefaultConfig($company); //添加订单默认配置
            $this->addMapConfig($company); //添加订单默认配置

            return 'true';
        });
    }

    public function addMapConfig($company)
    {
        MapConfig::create([
            'company_id' => $company['id'],
            'front_type' => BaseConstService::MAP_CONFIG_FRONT_TYPE_1,
            'back_type' => BaseConstService::MAP_CONFIG_BACK_TYPE_1,
            'mobile_type' => BaseConstService::MAP_CONFIG_MOBILE_TYPE_1,
            'google_key' => '',
            'google_secret' => '',
            'baidu_key' => '',
            'baidu_secret' => '',
            'tencent_key' => '',
            'tencent_secret' => '',
        ]);
    }

    public function addOrderTemplate($company)
    {
        OrderTemplate::create([
            'company_id' => $company['id'],
            'type' => BaseConstService::ORDER_TEMPLATE_TYPE_1,
            'is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_1,
            'logo' => '',
            'destination_mode' => BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1,
            'sender' => '发件人',
            'receiver' => '收件人',
            'destination' => '目的地',
            'carrier' => '承运人',
            'carrier_address' => '承运人地址',
            'contents' => '物品信息',
            'package' => '包裹',
            'material' => '材料',
            'count' => '数量',
            'replace_amount' => '代收货款',
            'settlement_amount' => '运费金额'
        ]);
        OrderTemplate::create([
            'company_id' => $company['id'],
            'type' => BaseConstService::ORDER_TEMPLATE_TYPE_2,
            'is_default' => BaseConstService::ORDER_TEMPLATE_IS_DEFAULT_2,
            'logo' => '',
            'destination_mode' => BaseConstService::ORDER_TEMPLATE_DESTINATION_MODE_1,
            'sender' => '发件人',
            'receiver' => '收件人',
            'destination' => '目的地',
            'carrier' => '承运人',
            'carrier_address' => '承运人地址',
            'contents' => '物品信息',
            'package' => '包裹',
            'material' => '材料',
            'count' => '数量',
            'replace_amount' => '代收货款',
            'settlement_amount' => '运费金额'
        ]);
    }


    /**
     * 初始化订单号规则
     * @param $company
     * @return bool
     */
    protected function initCompanyOrderCodeRules($company)
    {
        $rules = [
            BaseConstService::BATCH_NO_TYPE => BaseConstService::BATCH,
            BaseConstService::ORDER_NO_TYPE => BaseConstService::TMS,
            BaseConstService::TOUR_NO_TYPE => BaseConstService::TOUR,
            BaseConstService::BATCH_EXCEPTION_NO_TYPE => BaseConstService::BATCH_EXCEPTION,
            BaseConstService::RECHARGE_NO_TYPE => BaseConstService::RECHARGE,
            BaseConstService::STOCK_EXCEPTION_NO_TYPE => BaseConstService::STOCK_EXCEPTION,
            BaseConstService::TRACKING_ORDER_NO_TYPE => BaseConstService::TRACKING_ORDER,
            BaseConstService::CAR_ACCIDENT_NO_TYPE => BaseConstService::CAR_ACCIDENT,
            BaseConstService::CAR_MAINTAIN_NO_TYPE => BaseConstService::CAR_MAINTAIN,
        ];
        $rules = collect($rules)->map(function ($rule, $type) use ($company) {
            $prefix = $rule . substr('000' . $company->id, -4, 4);
            if ($type == BaseConstService::ORDER_NO_TYPE || $type == BaseConstService::TRACKING_ORDER_NO_TYPE) {
                $length = 6;
            } elseif ($type == BaseConstService::RECHARGE_NO_TYPE) {
                $length = 7;
            } else {
                $length = 4;
            }
            return collect([
                'company_id' => $company->id,
                'type' => $type,
                'prefix' => $prefix,
                'start_index' => 1,
                'int_length' => $length,
                'max_no' => $prefix . str_repeat('9', $length)
            ]);
        })->toArray();
        foreach ($rules as $rule) {
            $data[] = OrderNoRule::create($rule);
        }

        return count($data) === count($rules);
    }

    public function addWarehouse($company, $data)
    {
        $warehouse = Warehouse::create([
            'name' => $data['email'],
            'company_id' => $company->id,
            'type' => BaseConstService::WAREHOUSE_TYPE_2,
            'is_center' => BaseConstService::NO,
            'acceptance_type' => BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_1 . ',' . BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_2 . ',' . BaseConstService::WAREHOUSE_ACCEPTANCE_TYPE_3,
            'line_ids' => '',
            'parent' => 0
        ]);

        (new WareHouseService(new Warehouse))->createRoot($warehouse);

        return $warehouse;
    }

    /**
     * 添加管理员初始用户组
     * @param Company $company
     * @param array $data
     * @param $warehouse
     * @return mixed
     */
    protected function addEmployee(Company $company, array $data, $warehouse)
    {
        return Employee::create([
            'email' => $data['email'],
            //'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'fullname' => $data['email'],
            'company_id' => $company->id,
            'username' => $data['email'],
            'is_admin' => 1,
            'warehouse_id' => $warehouse->id
        ]);
    }

    /**
     * 添加权限组
     * @param $company
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws BusinessLogicException
     */
    protected function addRole($company)
    {
        /**@var Role $role * */
        $role = Role::create([
            'company_id' => $company->id,
            'name' => '管理员组',
            'is_admin' => 1,
        ]);
        if ($role === false) {
            throw new BusinessLogicException('初始化权限组失败');
        }
        $basePermissionList = self::getPermissionList();
        $role->syncPermissions(array_column($basePermissionList, 'id'));
        return $role;
    }

    /**
     * 员工初始化权限组
     * @param $employee
     * @param $role
     */
    protected function addPermission($employee, $role)
    {
        $employee->syncRoles($role);
    }


    /**
     * 添加初始运价方案
     *
     * @param $company
     * @return mixed
     * @throws BusinessLogicException
     */
    protected function addTransportPrice($company)
    {
        $transportPrice = TransportPrice::create([
            'company_id' => $company->id,
            'name' => $company->name,
            'remark' => 'remark',
            'status' => 1,
            'starting_price' => 0,
        ]);
        if ($transportPrice === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        $rowCount = KilometresCharging::create(['company_id' => $company->id, 'transport_price_id' => $transportPrice->id, 'start' => 0, 'end' => 999999999, 'price' => 4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        $rowCount = WeightCharging::create(['company_id' => $company->id, 'transport_price_id' => $transportPrice->id, 'start' => 0, 'end' => 999999999, 'price' => 2]);
        if ($rowCount === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        $rowCount = SpecialTimeCharging::create(['company_id' => $company->id, 'transport_price_id' => $transportPrice->id, 'start' => '10:00:00', 'end' => "11:00:00", 'price' => 1.5]);
        if ($rowCount === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        return $transportPrice;
    }

    /**
     * 初始化货主租
     *
     * @param $company
     * @param $transportPrice
     * @return mixed
     * @throws BusinessLogicException
     */
    protected function addMerchantGroup($company, $transportPrice)
    {
        $merchantGroup = MerchantGroup::create([
            'company_id' => $company->id,
            'name' => $company->name,
            'transport_price_id' => $transportPrice->id,
            'count' => 1,
            'is_default' => 1,
        ]);
        if ($merchantGroup === false) {
            throw new BusinessLogicException('初始化货主组失败');
        }
        return $merchantGroup;
    }


    /**
     * 初始化货主
     *
     * @param $company
     * @param $merchantGroup
     * @param $warehouse
     * @return mixed
     * @throws BusinessLogicException
     */
    protected function addMerchant($company, $merchantGroup,$warehouse)
    {
        $merchant = Merchant::create([
            'company_id' => $company->id,
            'type' => BaseConstService::MERCHANT_TYPE_2,
            'name' => $company->name,
            'email' => $company->email,
            'password' => Hash::make(BaseConstService::INITIAL_PASSWORD),
            'settlement_type' => BaseConstService::MERCHANT_SETTLEMENT_TYPE_1,
            'merchant_group_id' => $merchantGroup->id,
            'contacter' => $company->contacts,
            'phone' => $company->phone,
            'address' => $company->address,
            'avatar' => '',
            'status' => 1,
            'warehouse_id'=>$warehouse->id
        ]);
        if ($merchant === false) {
            throw new BusinessLogicException('初始化货主失败');
        }
        return $merchant;
    }


    /**
     * 初始化货主API
     *
     * @param $company
     * @param $merchant
     * @throws BusinessLogicException
     */
    protected function addMerchantApi($company, $merchant)
    {
        $merchant = MerchantApi::create([
            'company_id' => $company->id,
            'merchant_id' => $merchant->id,
            'key' => Hashids::encode(time() . $merchant->id),
            'secret' => Hashids::connection('alternative')->encode(time() . $merchant->id),
            'url' => '',
            'white_ip_list' => '',
            'status' => 1,
        ]);
        if ($merchant === false) {
            throw new BusinessLogicException('初始化货主API失败');
        }
    }

    /**
     * 添加费用
     * @param $company
     * @throws BusinessLogicException
     */
    private function addFee($company)
    {
        $fee = Fee::create([
            'company_id' => $company->id,
            'name' => '贴单费用',
            'code' => BaseConstService::STICKER,
            'amount' => 7.00
        ]);
        if ($fee === false) {
            throw new BusinessLogicException('费用初始化失败');
        }
        $fee = Fee::create([
            'company_id' => $company->id,
            'name' => '提货费用',
            'code' => BaseConstService::DELIVERY,
            'amount' => 10.00
        ]);
        if ($fee === false) {
            throw new BusinessLogicException('费用初始化失败');
        }
    }

    /**
     * 添加订单默认配置
     * @param $company
     */
    private function addOrderDefaultConfig($company)
    {
        OrderDefaultConfig::create([
            'company_id' => $company->id
        ]);
    }

    /**
     * 注册验证码
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function applyOfRegister(Request $request)
    {
        /*$request->validate([
            'email' => 'required|email',
        ]);*/

        throw_if(
            Employee::where('email', $request->get('email'))->count(),
            new BusinessLogicException('该邮箱已注册，请直接登录')
        );

        return RegisterController::sendCode($request->get('email'));
    }

    /**
     * 重置密码验证码
     * @param Request $request
     * @return string
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function applyOfReset(Request $request)
    {
        /*$request->validate([
            'email' => 'required|email',
        ]);*/
        if (empty(Employee::query()->where($this->username(), $request['email'])->first())) {
            throw new BusinessLogicException('用户不存在，请检查用户名');
        }

        return RegisterController::sendCode($request->input('email'), 'RESET');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        $username = request()->get('email');
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'phone';
        }
    }

    /**
     * 重置密码
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function resetPassword(Request $request)
    {
        /*$data = $request->validate([
            'new_password' => 'required|string|between:8,20',
            'confirm_new_password' =>'required|string|same:new_password',
            'email' => 'required|email',
            'code'  => 'required|string|digits_between:6,6'
        ]);*/
        $data = $this->data;

        if ($data['code'] !== RegisterController::getVerifyCode($data['email'], 'RESET')) {
            throw new BusinessLogicException('验证码错误');
        }

        RegisterController::deleteVerifyCode($data['email'], 'RESET');

        $admin = Employee::where('email', $data['email'])->firstOrFail();

        $res = $admin->update([
            'password' => bcrypt($data['new_password']),
        ]);

        if ($res === false) {
            return failed();
        }

        return success();
    }

    /**
     * 重置密码验证码验证
     * @param Request $request
     * @return array
     * @throws BusinessLogicException
     */
    public function verifyResetCode(Request $request)
    {
        $data = $this->data; /*= $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|digits_between:6,6'
        ]);*/

        if ($data['code'] !== RegisterController::getVerifyCode($data['email'], 'RESET')) {
            throw new BusinessLogicException('验证码错误');
        }

        return success();
    }

    /**
     * 获取验证码
     * @param string $mail
     * @param string $use
     * @return string
     */
    protected static function makeVerifyCode(string $mail, string $use = 'REGISTER'): string
    {
        $verifyCode = mt_rand(100000, 999999);

        Cache::put('VERIFY_CODE:' . $use . ':' . $mail, $verifyCode, 300);

        return $verifyCode;
    }

    /**
     * 获取验证码
     * @param string $mail
     * @param string $use
     * @return string
     * @package string $use
     */
    public static function getVerifyCode(string $mail, string $use = 'REGISTER'): ?string
    {
        return Cache::get('VERIFY_CODE:' . $use . ':' . $mail);
    }

    /**
     * 删除验证码
     * @param string $mail
     * @param string $use
     * @return bool
     */
    public static function deleteVerifyCode(string $mail, string $use = 'REGISTER'): bool
    {
        return Cache::forget('VERIFY_CODE:' . $use . ':' . $mail);
    }

    /**
     * 请求验证码发送
     * @param string $email
     * @param string $use
     * @return string
     * @throws BusinessLogicException
     */
    public static function sendCode(string $email, string $use = 'REGISTER')
    {
        try {
            if ($use == 'REGISTER') {
                Mail::to($email)->send(new SendRegisterCode(RegisterController::makeVerifyCode($email, $use)));
            } elseif ($use == 'RESET') {
                Mail::to($email)->send(new SendResetCode(RegisterController::makeVerifyCode($email, $use)));
            }
        } catch (\Exception $exception) {
            info('用户认证邮件发送失败：', ['message' => $exception->getMessage()]);
            throw new BusinessLogicException('验证码发送失败');
        }

        return '验证码发送成功';
    }

    /**
     * 生成新的公司代码
     * @param $company
     * @return false|string
     */
    public static function makeNewCompanyCode($company)
    {
        if ($company) {
            return substr('000' . ($company->company_code + 1), -4, 4);
        }

        return '0001';
    }

}
