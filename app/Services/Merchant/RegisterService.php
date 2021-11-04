<?php

/**
 * @Author: h9471
 */

namespace App\Services\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Resources\Api\Merchant\MerchantResource;
use App\Mail\SendRegisterCode;
use App\Mail\SendResetCode;
use App\Models\Merchant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Vinkla\Hashids\Facades\Hashids;

class RegisterService extends BaseService
{
    /**
     * RegisterService constructor.
     * @param Merchant $merchant
     */
    public function __construct(Merchant $merchant)
    {
        parent::__construct($merchant, MerchantResource::class, MerchantResource::class);
    }

    /**
     * @param $data
     * @return mixed
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function register($data)
    {
        throw_if(
            $this->query->where('email', $data['email'])->count(),
            new BusinessLogicException('邮箱已注册，请直接登录')
        );
        throw_if(
            $this->query->where('name', $data['name'])->count(),
            new BusinessLogicException('名称已注册，请直接登录')
        );
        $companyId = $this->getCompanyCustomizeService()->getList();
        $merchant = parent::create([
            'company_id' => $companyId,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $id = $merchant->id;
        $merchantApi = $this->getMerchantApiService()->create([
            'merchant_id' => $id,
            'key' => Hashids::encode(time() . $id),
            'secret' => Hashids::connection('alternative')->encode(time() . $id)
        ]);
        if ($merchantApi === false) {
            throw new BusinessLogicException('新增失败，请重新操作');
        }
    }

    /**
     * @param $data
     * @return string
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function applyOfRegister($data)
    {
        throw_if(
            $this->query->where('email', $data['email'])->count(),
            new BusinessLogicException('邮箱已注册，请直接登录')
        );
        return self::sendCode($data['email']);
    }

    /**
     * @param $data
     * @return string
     * @throws BusinessLogicException
     */
    public function applyOfReset($data)
    {
        if (empty($this->query->where($this->username($data), $data['email'])->first())) {
            throw new BusinessLogicException('该邮箱未注册，请联系管理员');
        }
        return self::sendCode($data['email'], 'RESET');
    }

    /**
     * @param $data
     * @return string
     */
    public function username($data)
    {
        $username = $data['email'];
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } else {
            return 'phone';
        }
    }

    /**
     * @param $data
     * @return void
     * @throws BusinessLogicException
     */
    public function resetPassword($data)
    {
        self::verifyResetCode($data);
        self::deleteVerifyCode($data['email'], 'RESET');
        $row = $this->update(['email'=>$data['email']],[
            'password' => bcrypt($data['new_password']),
        ]);
        if ($row === false) {
            throw new BusinessLogicException('操作失败');
        }
    }

    /**
     * @param $data
     * @return array
     * @throws BusinessLogicException
     */
    public function verifyResetCode($data)
    {
        if ($data['code'] !== self::getVerifyCode($data['email'], 'RESET')) {
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
    public function getVerifyCode(string $mail, string $use = 'REGISTER'): ?string
    {
        return Cache::get('VERIFY_CODE:' . $use . ':' . $mail);
    }

    /**
     * 删除验证码
     * @param string $mail
     * @param string $use
     * @return bool
     */
    public function deleteVerifyCode(string $mail, string $use = 'REGISTER'): bool
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
    public function sendCode(string $email, string $use = 'REGISTER')
    {
        try {
            if ($use == 'REGISTER') {
                Mail::to($email)->send(new SendRegisterCode(self::makeVerifyCode($email, $use)));
            } elseif ($use == 'RESET') {
                Mail::to($email)->send(new SendResetCode(self::makeVerifyCode($email, $use)));
            }
        } catch (\Exception $exception) {
            info('用户认证邮件发送失败：', ['message' => $exception->getMessage()]);
            throw new BusinessLogicException('验证码发送失败');
        }

        return '验证码发送成功';
    }
}
