<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/4/1
 * Time: 20:48
 */

namespace App\Services\Tokens;


use App\Exceptions\AccountErrorException;
use App\Exceptions\BindingLoginModeException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\RePasswordException;
use App\Exceptions\TokenException;
use App\Models\Token;
use App\Models\User;
use App\Models\UserAuth;
use App\Services\Account;
use App\Services\VerificationCode;
use Cache;
use Closure;
use DB;
use Exception;
use Hash;

class TokenFactory
{
    /**
     * 微信登录实例
     *
     * @return WeChatMediaToken|WeChatMiniProgramToken|WeChatOpenToken
     */
    public static function weChat()
    {
        $code = request()->post('code');
        $type = request()->post('type');

        if (!$type) {
            return new WeChatMiniProgramToken($code);
        }

        if ($type == 'open') {
            return new WeChatOpenToken($code);
        }

        if ($type == 'media') {
            return new WeChatMediaToken($code);
        }
    }

    /**
     * 账号+密码登录实例
     *
     * @return AccountToken
     */
    public static function account()
    {
        $account = request()->post('username');
        $password = request()->post('password');

        return new AccountToken($account, $password);
    }

    /**
     * 手机号+密码登录实例
     *
     * @param string $phone
     * @return PhoneToken
     */
    public static function phone($phone = '')
    {
        $phone = $phone ?: request()->post('username');
        $password = request()->post('password');

        return new PhoneToken($phone, $password);
    }

    /**
     * 邮箱+密码登录实例
     *
     * @param string $email
     * @return EmailToken
     */
    public static function email($email = '')
    {
        $email = $email ?: request()->post('username');
        $password = request()->post('password');

        return new EmailToken($email, $password);
    }

    /**
     * 验证码登录实例
     *
     * @return VerificationCodeToken
     * @throws AccountErrorException
     * @throws \App\Exceptions\VerificationCodeException
     */
    public static function verificationCode()
    {
        $identifier = VerificationCode::getContact(request()->post('verification_code'), request()->post('verification_token'));
        $type = Account::judgeAccountType($identifier);

        return new VerificationCodeToken($type, $identifier);
    }

    /**
     * 刷新token实例
     *
     * @param null $refreshToken
     * @return RefreshToken
     */
    public static function refresh($refreshToken = null)
    {
        $refreshToken = $refreshToken ?: request()->header('token') ?: request()->input('token');
        return new RefreshToken($refreshToken);
    }

    /**
     * 绑定手机号
     *
     * @param $phone
     * @param Closure $sendPassword
     * @throws \App\Exceptions\AccountIsExistException
     * @throws \App\Exceptions\BindingLoginModeException
     * @throws \App\Exceptions\TokenException
     */
    public static function bindPhone($phone, Closure $sendPassword)
    {
        return PhoneToken::bind($phone, $sendPassword);
    }

    /**
     * 绑定邮箱
     *
     * @param $email
     * @param Closure $sendPassword
     * @throws \App\Exceptions\AccountIsExistException
     * @throws \App\Exceptions\BindingLoginModeException
     * @throws \App\Exceptions\TokenException
     */
    public static function bindEmail($email, Closure $sendPassword)
    {
        return EmailToken::bind($email, $sendPassword);
    }

    /**
     * 解绑登录方式
     *
     * @param $type
     * @throws BindingLoginModeException
     * @throws Exception
     * @throws TokenException
     */
    public static function unbind($type)
    {
        $uid = self::getCurrentUID();

        $identity = UserAuth::where('user_id', $uid)
            ->where('identity_type', $type)
            ->first();

        if (!$identity) {
            throw new BindingLoginModeException('该账号未绑定' . $type);
        }

        $identityCount = UserAuth::where('user_id', $uid)
            ->count();

        DB::beginTransaction();
        try {
            if ($identityCount == 1) {
                User::where('id', $uid)
                    ->delete();
            }
            $identity->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new BindingLoginModeException('解除绑定失败');
        }
    }

    /**
     * 从缓存中移除token
     *
     * @param $uid
     */
    public static function removeToken($uid)
    {
        $userToken = Token::where('user_id', $uid)
            ->first();
        if ($userToken) {
            Cache::forget($userToken->access_token);
            Cache::forget('refresh:' . $userToken->refresh_token);
        }
    }

    /**
     * 修改密码
     *
     * @param $uid
     * @param $password
     * @throws RePasswordException
     */
    public static function rePassword($uid, $password)
    {
        $res = UserAuth::where('user_id', $uid)
            ->where('platform', 'local')
            ->update([
                'credential' => Hash::make($password)
            ]);

        if (!$res) {
            throw new RePasswordException();
        }

        self::removeToken($uid);
    }

    /**
     * 获取token中的指定值
     *
     * @param $key
     * @return mixed
     * @throws TokenException
     * @throws \Exception
     */
    public static function getCurrentTokenVar($key)
    {
        $token = request()->header('token') ?: request()->input('token');

        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        }

        if (!is_array($vars)) {
            $vars = json_decode($vars, true);
        }

        if (!array_key_exists($key, $vars)) {
            throw new Exception('尝试获取的Token值不存在');
        }

        return $vars[$key];
    }

    /**
     * 检测操作的UID是否与当前用户匹配
     *
     * @param $checkedUID
     * @return bool
     * @throws \Exception
     */
    public static function isValidOperate($checkedUID)
    {
        if (!$checkedUID) {
            throw new Exception('检查UID时必须传入被检查的UID');
        }

        $currentOperateUID = self::getCurrentUID();

        if ($checkedUID == $currentOperateUID) {
            return true;
        }

        return false;
    }

    /**
     * 获取token中的uid
     *
     * @return mixed
     * @throws TokenException
     * @throws \Exception
     */
    public static function getCurrentUID()
    {
        return self::getCurrentTokenVar('uid');
    }

    /**
     * 获取当前用户
     *
     * @return mixed
     * @throws TokenException
     */
    public static function getCurrentUser()
    {
        $user = request()->offsetGet('user');

        if (!$user) throw new TokenException();

        return $user;
    }

    /**
     * 获取用户拥有的组列表
     *
     * @return mixed
     * @throws TokenException
     */
    public static function getCurrentRoles()
    {
        return self::getCurrentUser()->getRoleNames()->toArray();
    }

    /**
     * 获取用户拥有的权限列表
     *
     * @return array
     * @throws TokenException
     */
    public static function getCurrentPermissions()
    {
        $permissions = [];
        foreach (self::getCurrentUser()->getAllPermissions() as $value) {
            array_push($permissions, $value['name']);
        }
        return $permissions;
    }

    /**
     * 检测用户组
     *
     * @param $role
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needRole($role)
    {
        $currentRoles = self::getCurrentRoles();

        if (!in_array($role, $currentRoles)) {
            throw new ForbiddenException('无权访问，需要' . $role . '用户组');
        }

        return true;
    }

    /**
     * 检测用户权限
     *
     * @param $permission
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function can($permission)
    {
        $currentPermissions = self::getCurrentPermissions();

        if (!in_array($permission, $currentPermissions)) {
            throw new ForbiddenException('权限不足，需要' . $permission . '权限');
        }

        return true;
    }

    public static function isAdmin($uid = null)
    {
        try {
            if ($uid) return User::find($uid)->is_admin;
            else return TokenFactory::getCurrentUser()->is_admin;
        } catch (Exception $exception) {
            return false;
        }
    }
}