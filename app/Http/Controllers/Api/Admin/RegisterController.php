<?php
/**
 * @Author: h9471
 */

namespace App\Http\Controllers\Api\Admin;

use App\Exceptions\BusinessLogicException;
use App\Mail\SendRegisterCode;
use App\Mail\SendResetCode;
use App\Models\Company;
use App\Models\Employee;
use App\Models\OrderNoRule;
use App\Services\BaseConstService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * @param  Request  $request
     * @return JsonResponse|void
     * @throws \Throwable
     */
    public function store(Request $request)
    {
//        $data = $request->validate([
//                'email' => 'required|email',
//                'password' => 'required|string|between:8,20',
//                'confirm_password' =>'required|string|same:password',
//                //'phone'     => 'required|string|phone:CN',
//                'code'      => 'required|string|digits_between:6,6'
//        ]);
        $data = $request->all();

        if ($data['code'] !== RegisterController::getVerifyCode($data['email'])) {
            throw new BusinessLogicException('验证码错误');
        }

        RegisterController::deleteVerifyCode($data['email']);

        throw_if(
            Employee::where('email', $data['email'])->count(),
            new BusinessLogicException('账号已注册，请直接登录')
        );

        return DB::transaction(function () use ($data) {
            $lastCompany = Company::lockForUpdate()->orderBy('created_at','desc')->first();

            $company = Company::create([
                'company_code' => self::makeNewCompanyCode($lastCompany),
                'email' => $data['email'],
                'name' => $data['email'],
                //'phone' => $data['phone'],
            ]);

            $this->addEmployeeForCompany($company, $data);

            $this->initCompanyOrderCodeRules($company);

           return 'true';
        });
    }

    /**
     * 初始化订单号规则
     * @param $company
     * @return bool
     */
    protected function initCompanyOrderCodeRules($company)
    {
        $rules = [
            BaseConstService::BATCH_NO_TYPE,
            BaseConstService::ORDER_NO_TYPE,
            BaseConstService::TOUR_NO_TYPE,
            BaseConstService::BATCH_EXCEPTION_NO_TYPE,
        ];

        $rules = array_map(function ($value) use ($company) {
            return [
                'company_id' => $company->id,
                'type' => $value,
                'prefix' => substr('000'.$company->id, -4, 4),
                'start_index' => 1,
                'length' => 13,
            ];
        }, $rules);

        foreach ($rules as $rule) {
            $data[] = OrderNoRule::create($rule);
        }

        return count($data) === count($rules);
    }

    /**
     * 添加管理员初始用户组
     * @param  Company  $company
     * @param  array  $data
     * @return mixed
     */
    protected function addEmployeeForCompany(Company $company, array $data)
    {
        return Employee::create([
            'email' => $data['email'],
            //'phone' => $data['phone'],
            'password'   => bcrypt($data['password']),
            'auth_group_id'   => 1,
            'institution_id'   => 1,
            'fullname'  => $data['email'],
            'company_id'    => $company->id,
        ]);
    }

    /**
     * 注册验证码
     * @param  Request  $request
     * @return string
     * @throws \Throwable
     */
    public function applyOfRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        throw_if(
            Employee::where('email', $request->get('email'))->count(),
            new BusinessLogicException('该邮箱已注册，请直接登录')
        );

        return RegisterController::sendCode($request->get('email'));
    }

    /**
     * 重置密码验证码
     * @param  Request  $request
     * @return string
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function applyOfReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        return RegisterController::sendCode($request->input('email'), 'RESET');
    }

    /**
     * 重置密码
     * @param  Request  $request
     * @return array
     * @throws BusinessLogicException
     */
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'new_password' => 'required|string|between:8,20',
            'confirm_new_password' =>'required|string|same:new_password',
            'email' => 'required|email',
            'code'  => 'required|string|digits_between:6,6'
        ]);

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
     * 获取验证码
     * @param  string  $mail
     * @param  string  $use
     * @return string
     */
    protected static function makeVerifyCode(string $mail, string $use = 'REGISTER'): string
    {
        $verifyCode = mt_rand(100000, 999999);

        Cache::put('VERIFY_CODE:'.$use.':'.$mail, $verifyCode, 300);

        return $verifyCode;
    }

    /**
     * 获取验证码
     * @param  string  $mail
     * @param  string  $use
     * @return string
     * @package string $use
     */
    public static function getVerifyCode(string $mail, string $use = 'REGISTER'): ?string
    {
        return Cache::get('VERIFY_CODE:'.$use.':'.$mail);
    }

    /**
     * 删除验证码
     * @param  string  $mail
     * @param  string  $use
     * @return bool
     */
    public static function deleteVerifyCode(string $mail, string $use = 'REGISTER'): bool
    {
        return Cache::forget('VERIFY_CODE:'.$use.':'.$mail);
    }

    /**
     * 请求验证码发送
     * @param  string  $email
     * @param  string  $use
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
            return substr('000'.($company->company_code + 1), -4, 4);
        }

        return '0001';
    }
}
