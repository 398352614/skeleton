<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Service\AuthService;

class AuthController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    public function login()
    {
        return $this->service->login($this->request->all());
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function logout()
    {
        return $this->service->logout();
    }

    public function test()
    {
        return $this->service->test();
    }

    public function register()
    {
        return $this->service->register($this->request->all());
    }

    public function registerCode()
    {
        return $this->service->registerCode($this->request->all());
    }

    public function reset()
    {
        return $this->service->reset($this->request->all());
    }

    public function resetCode()
    {
        return $this->service->resetCode($this->request->all());
    }
}
