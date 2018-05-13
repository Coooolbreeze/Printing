<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/8
 * Time: 21:26
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use App\Services\Account;
use Illuminate\Http\Request;
use App\Services\Tokens\TokenFactory;

class TokenController extends ApiController
{
    /**
     * 用户登录
     *
     * @return mixed
     * @throws UserNotFoundException
     * @throws \App\Exceptions\AccountErrorException
     * @throws \App\Exceptions\PasswordErrorException
     * @throws \App\Exceptions\ServerException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function login()
    {
        return $this->success(Account::login());
    }

    /**
     * 用户注册
     *
     * @return mixed
     * @throws RegisterException
     * @throws \App\Exceptions\AccountErrorException
     * @throws \App\Exceptions\AccountIsExistException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function register()
    {
        return $this->success(Account::register());
    }

    /**
     * 刷新token令牌
     *
     * @return mixed
     * @throws \App\Exceptions\ServerException
     */
    public function refresh()
    {
        return $this->success(TokenFactory::refresh()->get());
    }
}