<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/6
 * Time: 15:46
 */

namespace App\Models;


use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;

class Log extends Model
{
    /**
     * @param $action
     * @param null $user
     * @throws \App\Exceptions\TokenException
     */
    public static function write($action, $user = null)
    {
        self::create([
            'user' => $user ?: TokenFactory::getCurrentUser()->nickname,
            'url' => request()->url(),
            'ip' => request()->getClientIp(),
            'action' => $action
        ]);
    }
}