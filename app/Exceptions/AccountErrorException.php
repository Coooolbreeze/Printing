<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 1:45
 */

namespace App\Exceptions;


class AccountErrorException extends BaseException
{
    public $message = '账号错误';
    public $error_code = 20007;
    public $code = 403;
}