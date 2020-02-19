<?php

namespace App\Services\Auth;

use App\Exceptions\BusinessLogicException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        if (!$this->validate($credentials)) {
            throw new BusinessLogicException('缺少参数key,sign或timestamp');
        }
        $merchantApi = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($merchantApi, $credentials)) {
            $this->user = $merchantApi;
            return true;
        }
        return false;
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
        if (empty($credentials['key']) || empty($credentials['sign']) || empty($credentials['timestamp'])) {
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
