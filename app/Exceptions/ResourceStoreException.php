<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 16:14
 */

namespace App\Exceptions;


class ResourceStoreException extends BaseException
{
    public $message = '创建资源失败';
    public $error_code = 30001;
    public $code = 400;
}