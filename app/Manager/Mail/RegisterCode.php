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
namespace App\Manager\Mail;

use HyperfExt\Contract\ShouldQueue;
use HyperfExt\Mail\Mailable;

class RegisterCode extends Mailable implements ShouldQueue
{
    public int $code;

    /**
     * Create a new message instance.
     * @param mixed $code
     */
    public function __construct(mixed $code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build(): Mailable|RegisterCode
    {
        return $this
            ->subject('账号注册激活码')
            ->htmlView('mails/register_code');
    }
}
