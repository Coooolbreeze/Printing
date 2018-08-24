<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/18
 * Time: 16:35
 */

namespace App\Exceptions;


class TokenException extends BaseException
{
    public $message = '登录状态已失效，请重新登录';
    public $error_code = 10001;
    public $code = 401;
}