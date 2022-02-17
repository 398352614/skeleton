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
namespace App\Service;

use App\Constants\BaseConstant;
use App\Exception\BusinessException;
use App\Manager\Mail\RegisterCode;
use App\Model\User;
use App\Request\AuthRequest;
use App\Resource\AuthResource;
use App\Trait\AuthCodeTrait;
use Hyperf\Di\Annotation\Inject;
use HyperfExt\Mail\Mail;
use Psr\SimpleCache\InvalidArgumentException;
use Qbhy\HyperfAuth\AuthManager;

class AuthService extends Service
{
    use AuthCodeTrait;

    /**
     * @Inject
     */
    protected AuthManager $auth;

    public function __construct(User $model, AuthRequest $request, AuthResource $resource)
    {
        parent::__construct(
            $model,
            $resource,
            $request
        );
    }

    public function login(mixed $params)
    {
        /** @var User $user */
        $user = $this->query->where('name', $params['name'])->first();
        if (empty($user)) {
            throw new BusinessException('用户不存在');
        }
        if (! $this->auth->guard('jwt')->getProvider()->retrieveByCredentials($params)) {
            throw new BusinessException('用户名或密码错误！');
        }
        $user['token'] = $this->auth->guard('jwt')->login($user);
        return new $this->resource($user);
    }

    public function logout()
    {
        $this->auth->guard('jwt')->logout();
    }

    /**
     * @param $data
     * @throws InvalidArgumentException
     */
    public function register($data)
    {
        self::verifyCode($data, BaseConstant::REGISTER_CODE);
        if ($this->query->where('name', $data['name'])->count()) {
            throw new BusinessException('邮箱已注册，请直接登录');
        }
        $row = parent::store([
            'name' => $data['name'],
            'password' => hash('sha256', $data['password']),
        ]);
        if ($row == false) {
            throw new BusinessException('注册失败');
        }
    }

    public function registerCode($params): string
    {
        $data = $this->show(['name' => $params['name']]);
        if (! empty($data)) {
            throw new BusinessException('邮箱已注册，请直接登录');
        }
        try {
            $code = self::makeVerifyCode($params['name'], BaseConstant::REGISTER_CODE);
            Mail::to($params['name'])->send(new RegisterCode($code));
        } catch (InvalidArgumentException $e) {
            throw new BusinessException('验证码发送失败');
        }
        return '验证码发送成功';
    }

    public function reset($data)
    {
    }
}
