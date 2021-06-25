<?php

namespace App\Services\Auth;

use App\Exceptions\BusinessLogicException;
use App\Models\Merchant;
use App\Services\BaseConstService;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MerchantApiGuard
 * @package App\Services\Auth
 * @property EloquentMerchantApiProvider $provider
 */
class MerchantApiGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;


    protected $key;

    /**
     * Create a new authentication guard.
     *
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Illuminate\Http\Request $request
     * @param string $inputKey
     * @param string $storageKey
     * @param bool $hash
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * 验证
     * @return bool
     * @throws BusinessLogicException
     */
    public function check()
    {
        $credentials = $this->request->all();
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . 'credentials', $credentials);
        if (!$this->validate($credentials)) {
            throw new BusinessLogicException('缺少参数key,sign,timestamp或data');
        }
        $merchantApi = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($merchantApi, $credentials)) {
            return $this->validCredentialSuccess($merchantApi);
        }
        return false;
    }

    /**
     * 认证成功后的操作
     *
     * @param $merchantApi
     * @return boolean
     * @throws BusinessLogicException
     */
    private function validCredentialSuccess($merchantApi)
    {
        if (intval($merchantApi->status) != BaseConstService::YES) {
            throw new BusinessLogicException('当前货主没有API对接权限');
        }
        $merchant = (new Merchant())->newQuery()->where('id', '=', $merchantApi->merchant_id)->first();
        if (empty($merchant)) {
            throw new BusinessLogicException('货主不存在');
        }
        $merchant->is_api = true;
        $this->user = $merchant;
        $data = request()->input('data');
        request()->offsetUnset('data');
        request()->merge($data);
        return true;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->user;
    }


    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (
            empty($credentials['key'])
            || empty($credentials['sign'])
            || empty($credentials['timestamp'])
            || empty($credentials['data'])
        ) {
            return false;
        }
        return true;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    public function credentials()
    {
        return $this->request->only(['key', 'sign', 'time']);
    }
}
