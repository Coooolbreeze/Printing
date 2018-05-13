<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 1:23
 */

namespace App\Exceptions;


class VerificationCodeException extends BaseException
{
    public $message = '验证码错误';
    public $error_code = 20006;
    public $code = 422;
}