<?php

namespace App\Mail;

use App\Services\VerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
            ->subject('易特印邮箱验证码')
            ->html('您的验证码' . $this->code . '，该验证码5分钟内有效，请勿泄漏于他人！');
    }
}
