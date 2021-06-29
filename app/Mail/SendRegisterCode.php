<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendRegisterCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
        Log::channel('api')->info(__CLASS__ . '.' . __FUNCTION__ . '.' . '注册发送的验证码' . $code);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.register_code');
    }
}
