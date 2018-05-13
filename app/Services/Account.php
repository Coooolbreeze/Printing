<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 13:08
 */

namespace App\Services;


use App\Exceptions\AccountErrorException;
use App\Exceptions\PasswordErrorException;
use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class Account
{
    /**
     * 注册
     *
     * @return mixed
     * @throws AccountErrorException
     * @throws RegisterException
     * @throws \App\Exceptions\AccountIsExistException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public static function register()
    {
        $verificationCode = request()->post('verification_code');
        $verificationToken = request()->post('verification_token');
        $password = request()->post('password');

        if (!preg_match('/^\w{6,18}$/', $password))
            throw new RegisterException('密码为6~18位字母、数字或下划线');

        $username = VerificationCode::getContact($verificationCode, $verificationToken);
        $type = self::judgeAccountType($username);

        if ($type == 'phone')
            return TokenFactory::phone($username)->create();
        if ($type == 'email')
            return TokenFactory::email($username)->create();
    }

    /**
     * 登录
     *
     * @return array
     * @throws AccountErrorException
     * @throws PasswordErrorException
     * @throws UserNotFoundException
     * @throws \App\Exceptions\ServerException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public static function login()
    {
        switch (self::judgeRequest()) {
            case 'phonePassword':
                return TokenFactory::phone()->get();
            case 'emailPassword':
                return TokenFactory::email()->get();
            case 'accountPassword':
                return TokenFactory::account()->get();
            case 'verificationCode':
                return TokenFactory::verificationCode()->get();
            case 'weChat':
                return TokenFactory::weChat()->get();
            default:
                throw new UserNotFoundException();
        }
    }

    /**
     * 判断登录方式
     *
     * @return string
     * @throws AccountErrorException
     * @throws PasswordErrorException
     * @throws UserNotFoundException
     */
    public static function judgeRequest()
    {
        $username = request()->post('username');
        $password = request()->post('password');
        $verificationCode = request()->post('verification_code');
        $verificationToken = request()->post('verification_token');
        $code = request()->post('code');

        if ($password && !preg_match('/^\w{6,18}$/', $password))
            throw new PasswordErrorException('密码为6~18位字母、数字或下划线');

        if ($username && $password)
            return self::judgeAccountType($username) . 'Password';

        if ($verificationCode && $verificationToken)
            return 'verificationCode';

        if ($code)
            return 'weChat';

        throw new UserNotFoundException();
    }

    /**
     * 判断账号类型
     *
     * @param $username
     * @return string
     * @throws AccountErrorException
     */
    public static function judgeAccountType($username)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) return 'email';
        if (preg_match('/^1[3-9]\d{9}$/', $username)) return 'phone';
        if (preg_match('/^[a-zA-Z][-_a-zA-Z0-9]{5,19}$/', $username)) return 'account';
        throw new AccountErrorException();
    }
}