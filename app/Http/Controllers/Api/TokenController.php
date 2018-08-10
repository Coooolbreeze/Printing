<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/8
 * Time: 21:26
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BindingLoginModeException;
use App\Exceptions\RegisterException;
use App\Exceptions\UserNotFoundException;
use App\Models\UserAuth;
use App\Services\Account;
use App\Services\VerificationCode;
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
     * 修改密码
     *
     * @param Request $request
     * @return mixed
     * @throws RegisterException
     * @throws \App\Exceptions\AccountErrorException
     * @throws \App\Exceptions\RePasswordException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function rePassword(Request $request)
    {
        $password = $request->password;

        if (!preg_match('/^\w{6,18}$/', $password))
            throw new RegisterException('密码为6~18位字母、数字或下划线');

        $username = VerificationCode::getContact($request->verification_code, $request->verification_token);

        $type = Account::judgeAccountType($username);

        $userAuth = UserAuth::where('platform', 'local')
            ->where('identity_type', $type)
            ->where('identifier', $username)
            ->firstOrFail();

        TokenFactory::rePassword($userAuth->user_id, $password);

        return $this->message('修改密码成功');
    }

    /**
     * 刷新token令牌
     *
     * @return mixed
     * @throws UserNotFoundException
     * @throws \App\Exceptions\ServerException
     */
    public function refresh()
    {
        return $this->success(TokenFactory::refresh()->get());
    }

    /**
     * 绑定登录方式
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\AccountErrorException
     * @throws \App\Exceptions\AccountIsExistException
     * @throws \App\Exceptions\BindingLoginModeException
     * @throws \App\Exceptions\TokenException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public function bindLoginMode(Request $request)
    {
        $contact = VerificationCode::getContact($request->verification_code, $request->verification_token);

        $type = Account::judgeAccountType($contact);

        if ($type == 'phone') {
            TokenFactory::bindPhone($contact, function ($password) {
                // TODO 发送密码

            });
            return $this->message('手机号绑定成功');
        }
        elseif ($type == 'email') {
            TokenFactory::bindEmail($contact, function ($password) {
                // TODO 发送密码

            });
            return $this->message('邮箱绑定成功');
        }
        else {
            throw new BindingLoginModeException();
        }
    }

    /**
     * 解绑登录方式
     *
     * @param Request $request
     * @return mixed
     * @throws BindingLoginModeException
     * @throws \App\Exceptions\TokenException
     */
    public function unbindLoginMode(Request $request)
    {
        TokenFactory::unbind($request->type);

        return $this->message('解绑成功');
    }
}