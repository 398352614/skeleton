<?php

namespace App\Services\Auth;

use App\Exceptions\BusinessLogicException;
use App\Models\Employee;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class MerchantApiGuard
 * @package App\Services\Auth
 * @property EloquentAdminApiProvider $provider
 */
class AdminApiGuard implements Guard
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
        $adminApi = $this->provider->retrieveByCredentials($credentials);
        if ($this->hasValidCredentials($adminApi, $credentials)) {
            return $this->validCredentialSuccess($adminApi);
        }
        return false;
    }

    /**
     * 认证成功后的操作
     *
     * @param $adminApi
     * @return boolean
     * @throws BusinessLogicException
     */
    private function validCredentialSuccess($adminApi)
    {
        $employee = (new Employee())->newQuery()->where('company_id', '=', $adminApi->company_id)->first();
        if (empty($employee)) {
            throw new BusinessLogicException('企业不存在');
        }
        $employee->is_api = true;
        $this->user = $employee;
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
