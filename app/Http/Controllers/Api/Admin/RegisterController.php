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
use App\Models\CompanyConfig;
use App\Models\Employee;
use App\Models\Institution;
use App\Models\KilometresCharging;
use App\Models\Merchant;
use App\Models\MerchantApi;
use App\Models\MerchantGroup;
use App\Models\OrderNoRule;
use App\Models\SpecialTimeCharging;
use App\Models\TransportPrice;
use App\Models\Warehouse;
use App\Models\WeightCharging;
use App\Services\Admin\CompanyService;
use App\Services\BaseConstService;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegisterController extends BaseController
{
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

        if ($data['code'] !== RegisterController::getVerifyCode($data['email'])) {
            throw new BusinessLogicException('验证码错误');
        }

        RegisterController::deleteVerifyCode($data['email']);

        throw_if(
            Employee::where('email', $data['email'])->count(),
            new BusinessLogicException('账号已注册，请直接登录')
        );

        DB::transaction(function () use ($data) {

            $lastCompany = Company::lockForUpdate()->orderBy('created_at', 'desc')->first();
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

            $companyConfig = CompanyConfig::create([
                'company_id' => $company->id,
                'line_rule' => BaseConstService::LINE_RULE_POST_CODE,
                'weight_unit' => 'kg',
                'currency_unit' => '￥',
                'volume_unit' => 'cm³',
                'map' => 'google'
            ]);

            if ($companyConfig === false) {
                throw new BusinessLogicException('初始化企业配置信息失败');
            }

            $institutionId = $this->addInstitution($company);//初始化组织结构
            $this->addEmployee($company, $data, $institutionId);//初始化管理员帐户
            $this->addWarehouse($company);//初始化仓库
            $this->initCompanyOrderCodeRules($company);//初始化编号规则
            $transportPrice = $this->addTransportPrice($company);//初始化运价方案
            $merchantGroup = $this->addMerchantGroup($company, $transportPrice);//初始化商户组
            $merchant = $this->addMerchant($company, $merchantGroup);//初始化商户API
            $this->addMerchantApi($company, $merchant);//初始化商户API
            return 'true';
        });
    }

    /**
     * @param $company
     * @return mixed
     */
    protected function addInstitution($company)
    {
        //创建根节点
        $companyRoot = Institution::create([
            'company_id' => $company->id,
            'name' => $company->name,
            'parent' => 0,
        ]);
        //创建初始组织
        $parentId = $companyRoot->id;
        $companyRoot->makeRoot();
        $child = Institution::create([
            'company_id' => $company->id,
            'name' => $company->name,
            'phone' => $company->phone ?? '',
            'contacts' => $company->contacts ?? '',
            'country' => $company->country ?? '',
            'address' => $company->address ?? '',
            'parent' => $parentId,
        ]);
        $institutionId = $child->id;
        $child->moveTo($parentId);
        return $institutionId;
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
            BaseConstService::ORDER_NO_TYPE => BaseConstService::ORDER,
            BaseConstService::TOUR_NO_TYPE => BaseConstService::TOUR,
            BaseConstService::BATCH_EXCEPTION_NO_TYPE => BaseConstService::BATCH_EXCEPTION,
        ];
        $rules = collect($rules)->map(function ($rule, $type) use ($company) {
            $prefix = $rule . substr('000' . $company->id, -4, 4);
            $length = ($type == BaseConstService::ORDER_NO_TYPE) ? 8 : 4;
            return collect([
                'company_id' => $company->id,
                'type' => $rule,
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

    /**
     * 添加管理员初始用户组
     * @param Company $company
     * @param array $data
     * @param $institutionId
     * @return mixed
     */
    protected function addEmployee(Company $company, array $data, $institutionId)
    {
        return Employee::create([
            'email' => $data['email'],
            //'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'auth_group_id' => 1,
            'institution_id' => $institutionId,
            'fullname' => $data['email'],
            'company_id' => $company->id,
            'username' => $data['email']
        ]);
    }

    /**
     * 添加初始仓库
     * @param Company $company
     * @param array $data
     * @return mixed
     */
    protected function addWarehouse(Company $company)
    {
        return Warehouse::create([
            'name' => $company->name,
            'contacter' => $company->email,
            'company_id' => $company->id,
            'country' => 'NL',
            'post_code' => '2153PJ',
            'house_number' => '20',
            'city' => 'Nieuw-Vennep',
            'street' => 'Pesetaweg',
            'lon' => '4.62897256',
            'lat' => '52.25347699',
        ]);
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
            'remark' => '',
            'status' => 1,
        ]);
        if ($transportPrice === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        $rowCount = KilometresCharging::create(['company_id' => $company->id, 'transport_price_id' => $transportPrice->id, 'start' => 0, 'end' => 2, 'price' => 4]);
        if ($rowCount === false) {
            throw new BusinessLogicException('初始化运价失败');
        }
        $rowCount = WeightCharging::create(['company_id' => $company->id, 'transport_price_id' => $transportPrice->id, 'start' => 1, 'end' => 2, 'price' => 2]);
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
     * 初始化商户租
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
            throw new BusinessLogicException('初始化商户组失败');
        }
        return $merchantGroup;
    }


    /**
     * 初始化商户
     *
     * @param $company
     * @param $merchantGroup
     * @return mixed
     * @throws BusinessLogicException
     */
    protected function addMerchant($company, $merchantGroup)
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
        ]);
        if ($merchant === false) {
            throw new BusinessLogicException('初始化商户失败');
        }
        return $merchant;
    }


    /**
     * 初始化商户API
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
            throw new BusinessLogicException('初始化商户API失败');
        }
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

        return RegisterController::sendCode($request->input('email'), 'RESET');
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
