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
use App\Services\BaseConstService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegisterController extends BaseController
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


            $lastCompany = Company::lockForUpdate()->orderBy('created_at','desc')->first();

            $company = Company::create([
                'company_code' => self::makeNewCompanyCode($lastCompany),
                'email' => $data['email'],
                'name' => $data['name'],
                'contacts' =>$data['email'],
                //'phone' => $data['phone'],
            ]);

            $this->addInstitution($company);//初始化组织结构
            $this->addEmployee($company, $data);//初始化管理员帐户
            $this->addWarehouse($company);//初始化仓库
            $this->initCompanyOrderCodeRules($company);//初始化编号规则
            $this->addTransportPrice($company);//初始化运价方案
            $this->addMerchantGroup($company);//初始化商户组
            $this->addMerchant($company);//初始化商户
            $this->addMerchantApi($company);//初始化商户API
    }

    /**
     * @param $company
     * @return mixed
     */
    protected function addInstitution($company){
        //创建根节点
        $companyRoot = Institution::create([
            'company_id'=>$company->id,
            'name' => $company->name,
            'parent' => 0,
        ]);
        $companyRoot->makeRoot();
        //创建初始组织
        $parentId =Institution::query()->where('company_id','=',$company->id)->first()->toArray()['id'];
        $child = Institution::create([
                'company_id'=>$company->id,
                'name' =>$company->name,
                'phone' => $company->phone ?? '',
                'contacts' => $company->contacts?? '',
                'country' => $company->country?? '',
                'address' => $company->address ?? '',
                'parent'=>$parentId,
            ]);

        return $child->moveTo($parentId);
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
                'length' => ($value == BaseConstService::ORDER_NO_TYPE) ? 8 : 4,
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
    protected function addEmployee(Company $company, array $data)
    {
        return Employee::create([
            'email' => $data['email'],
            //'phone' => $data['phone'],
            'password'   => bcrypt($data['password']),
            'auth_group_id'   => 1,
            'institution_id'   => Institution::query()->where('company_id',$company->id)->where('parent','<>',0)->first()->toArray()['id'],
            'fullname'  => $data['email'],
            'company_id'    => $company->id,
            'username'=>$data['email']
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
            'name'=>$company->name,
            'contacter'=>$company->email,
            'company_id'=> $company->id,
            'country'=>'NL',
            'post_code'=>'2153PJ',
            'house_number'=>'20',
            'city'=>'Nieuw-Vennep',
            'street'=>'Pesetaweg',
            'lon'=>'52.25347699',
            'lat'=>'4.62897256',
        ]);
    }

    /**
     * 添加初始运价方案
     * @param $company
     * @return mixed
     */
    protected function addTransportPrice($company){
        $info= TransportPrice::create([
            'company_id'=> $company->id,
            'name'=>$company->name,
            'remark'=>'',
            'status'=>1,
            ]);
        KilometresCharging::create(['company_id'=> $company->id, 'transport_price_id'=>$info->id, 'start'=>0, 'end'=>2, 'price'=>4]);
        WeightCharging::create(['company_id'=> $company->id, 'transport_price_id'=>$info->id, 'start'=>1, 'end'=>2, 'price'=>2]);
        SpecialTimeCharging::create(['company_id'=> $company->id, 'transport_price_id'=>$info->id,'start'=>'10:00:00', 'end'=>"11:00:00", 'price'=>1.5]);
        return $info;
    }

    protected function addMerchantGroup($company)
    {
        return MerchantGroup::create([
            'company_id'=> $company->id,
            'name'=>$company->name,
            'transport_price_id'=>TransportPrice::query()->where('company_id',$company->id)->first()->toArray()['id'],
            'is_default'=>1,
        ]);
    }

    protected function addMerchant($company){
        return Merchant::create([
            'company_id'=> $company->id,
            'type'=>BaseConstService::MERCHANT_TYPE_2,
            'name'=>$company->name,
            'email'=>$company->email,
            'password'=>Hash::make(BaseConstService::INITIAL_PASSWORD),
            'settlement_type'=>BaseConstService::MERCHANT_SETTLEMENT_TYPE_1,
            'merchant_group_id'=>MerchantGroup::query()->where('company_id',$company->id)->first()->toArray()['id'],
            'contacter'=>$company->contacts,
            'phone'=>$company->phone,
            'address'=>$company->address,
            'avatar'=>'',
            'status'=>1,
        ]);
    }

    protected function addMerchantApi($company){
        $id=Merchant::query()->where('company_id',$company->id)->first()->toArray()['id'];
        return MerchantApi::create([
            'company_id'=> $company->id,
            'merchant_id' => $id,
            'key' => Hashids::encode(time() . $id),
            'secret' => Hashids::connection('alternative')->encode(time() . $id),
            'url'=> '',
            'white_ip_list'=> '',
            'status'=> 1,
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
     * @param  Request  $request
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
     * @param  Request  $request
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
     * @param  Request  $request
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
