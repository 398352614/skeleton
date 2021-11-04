<?php

/**
 * @Author: h9471
 */

namespace App\Http\Controllers\Api\Merchant;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\BaseController;
use App\Services\Merchant\RegisterService;

/**
 * Class Register
 * @package App\Http\Controllers\Api\Merchant
 * @property RegisterService $service
 */
class RegisterController extends BaseController
{
    public function __construct(RegisterService $service)
    {
        parent::__construct($service);
    }

    /**
     * @return array
     * @throws BusinessLogicException
     * @throws \Throwable
     */
    public function register()
    {
        return $this->service->register($this->data);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function applyOfRegister()
    {
        return $this->service->applyOfRegister($this->data);
    }

    /**
     * @return string
     * @throws BusinessLogicException
     */
    public function applyOfReset()
    {
        return $this->service->applyOfReset($this->data);
    }

    /**
     * @return void
     * @throws BusinessLogicException
     */
    public function resetPassword()
    {
        $this->service->resetPassword($this->data);
    }
}
