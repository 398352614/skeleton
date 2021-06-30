<?php

/**
 * @Author: h9471
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Mail\SendRegisterCode;
use App\Mail\SendResetCode;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Merchant;
use App\Services\Merchant\MerchantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegisterController extends BaseController
{
    public function __construct(MerchantService $service)
    {
        $this->service = $service;
    }

    /**
     * @api {POST}  api/merchant/register 商家端:注册
     * @apiName register
     * @apiGroup user-register
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 注册
     * @apiParam {String}   email                    邮箱
     * @apiParam {String}   name                     名字
     * @apiParam {String}   password                 密码
     * @apiParam {String}   confirm_password         确认密码
     * @apiParam {String}   code                     注册验证码 -- 暂时不需
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"注册成功",
     *  "data":{}
     * }
     */
    public function store(Request $request)
    {
        //        $data = $request->validate([
        //                'email' => 'required|email',
        //                'name' => 'required',
        //                'password' => 'required|string|between:8,20',
        //                'confirm_password' =>'required|string|same:password',
        //                //'phone'     => 'required|string|phone:CN',
        //                'code'      => 'required|string|digits_between:6,6'
        //        ]);
        $data = $request->all();

        // if ($data['code'] !== RegisterController::getVerifyCode($data['email'])) {
        //     throw new BusinessLogicException('验证码错误');
        // }

        // RegisterController::deleteVerifyCode($data['email']);

        throw_if(
            Merchant::where('email', $data['email'])->count(),
            new BusinessLogicException('账号已注册，请直接登录')
        );
        throw_if(
            Merchant::where('name', $request->get('name'))->count(),
            new BusinessLogicException('该名称已注册，请直接登录')
        );
        return DB::transaction(function () use ($data) {
            $merchant = Merchant::create([
                'company_id' => Company::query()->where('company_code', $data['company_code'])->value('id'),
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $id = $merchant->id;
            $merchantApi = $this->service->getMerchantApiService()->create([
                'merchant_id' => $id,
                'key' => Hashids::encode(time() . $id),
                'secret' => Hashids::connection('alternative')->encode(time() . $id)
            ]);
            if ($merchantApi === false) {
                throw new BusinessLogicException('新增失败，请重新操作');
            }
        });
    }

    /**
     * @param Request $request
     * @return string
     * @throws BusinessLogicException
     * @throws \Throwable
     * @api {POST}  api/merchant/register/apply 商家端:注册验证码
     * @apiName register-apply
     * @apiGroup user-register
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 注册
     * @apiParam {String}   email                    邮箱
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"注册验证码",
     *  "data":{}
     * }
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
     * @param Request $request
     * @return string
     * @throws BusinessLogicException
     * @api {PUT}  api/merchant/password-reset/apply 商家端:重置密码验证码
     * @apiName password-reset-apply
     * @apiGroup user-register
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 重置密码验证码
     * @apiParam {String}   email                    邮箱
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"注册验证码",
     *  "data":{}
     * }
     */
    public function applyOfReset(Request $request)
    {
        /*$request->validate([
            'email' => 'required|email',
        ]);*/

        if (empty(Merchant::query()->where($this->username(), $request['email'])->first())) {
            throw new BusinessLogicException('该邮箱未注册，请联系管理员');
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
     * @api {PUT}  api/merchant/password-reset 商家端:重置密码
     * @apiName password-reset
     * @apiGroup user-register
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 重置密码
     * @apiParam {String}   email                    邮箱
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"注册验证码",
     *  "data":{}
     * }
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

        // if ($data['code'] !== RegisterController::getVerifyCode($data['email'], 'RESET')) {
        //     throw new BusinessLogicException('验证码错误');
        // }

        // RegisterController::deleteVerifyCode($data['email'], 'RESET');
        $merchant = Merchant::where('email', $data['email'])->firstOrFail();
        $res = $merchant->update([
            'password' => bcrypt($data['new_password']),
        ]);

        if ($res === false) {
            return failed();
        }

        return success();
    }

    /**
     * @api {PUT}  api/merchant/password-reset/verify 商家端:重置密码验证码验证
     * @apiName password-reset-verify
     * @apiGroup user-register
     * @apiPermission api
     * @apiVersion 1.0.0
     * @apiDescription 重置密码验证码验证
     * @apiParam {String}   email                    邮箱
     * @apiParam {String}   code                     验证码
     * @apiSuccessExample {json}  返回示例
     * HTTP/1.1 200 OK
     * {
     *  "code":200,
     *  "msg":"注册验证码",
     *  "data":{}
     * }
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
}
