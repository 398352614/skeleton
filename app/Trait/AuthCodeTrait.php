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
namespace App\Trait;

use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

trait AuthCodeTrait
{
    /**
     * @Inject
     */
    protected CacheInterface $cache;

    /**
     * @throws InvalidArgumentException
     */
    public function getVerifyCode(string $mail, string $use)
    {
        return $this->cache->get('EMAIL_' . $use . ':' . $mail);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function makeVerifyCode(string $mail, string $use): int
    {
        $verifyCode = mt_rand(100000, 999999);
        $this->cache->set('EMAIL_' . $use . ':' . $mail, $verifyCode, 300);
        return $verifyCode;
    }

    /**
     * @param $data
     * @param $use
     * @throws InvalidArgumentException
     */
    protected function verifyCode($data, $use)
    {
        if ($data['code'] != self::getVerifyCode($data['name'], $use)) {
            throw new BusinessException('验证码错误');
        }
    }
}
