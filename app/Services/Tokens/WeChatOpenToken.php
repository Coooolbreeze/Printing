<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/12
 * Time: 19:54
 */

namespace App\Services\Tokens;


use App\Exceptions\AccountIsExistException;
use App\Exceptions\BindingLoginModeException;
use App\Exceptions\RegisterException;
use App\Exceptions\TokenException;
use App\Exceptions\WeChatException;
use App\Models\User;
use Exception;
use DB;

class WeChatOpenToken extends BaseToken
{
    private $code;
    private $appID;
    private $appSecret;
    private $loginUrl;
    private $userInfoUrl;

    public function __construct($code)
    {
        $this->code = $code;

        $this->appID = config('wxOpen.app_id');
        $this->appSecret = config('wxOpen.app_secret');
        $this->userInfoUrl = config('wxOpen.user_info_url');
        $this->loginUrl = sprintf(config('wxOpen.login_url'), $this->appID, $this->appSecret, $this->code);
    }

    /**
     * 获取用户身份
     *
     * @return WeChatMediaToken|\Illuminate\Database\Eloquent\Model|mixed
     * @throws Exception
     * @throws WeChatException
     */
    public function identity()
    {
        // 获取access_token及openid
        $wxBaseInfo = self::getWxResult($this->loginUrl);
        $openid = $wxBaseInfo['openid'];
        $accessToken = $wxBaseInfo['access_token'];

        // 查找用户身份
        $identity = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', 'open')
            ->where('identifier', $openid)
            ->first();

        // 如果身份不存在，则创建用户身份
        if (!$identity) {
            $identity = $this->createUserIdentity($openid, $accessToken);
        }

        return $identity;
    }

    /**
     * 创建用户身份
     *
     * @param $openid
     * @param $accessToken
     * @return mixed
     * @throws Exception
     * @throws RegisterException
     * @throws WeChatException
     */
    public function createUserIdentity($openid, $accessToken)
    {
        // 拼接获取用户详细信息的url
        $userInfoUrl = sprintf($this->userInfoUrl, $accessToken, $openid);
        // 获取用户详细信息
        $wxUserInfo = self::getWxResult($userInfoUrl);

        DB::beginTransaction();
        try {
            // 添加用户信息
            $user = User::create([
                'nickname' => $wxUserInfo['nickname'],
                'avatar' => $wxUserInfo['headimgurl'],
                'sex' => $wxUserInfo['sex'],
                'is_bind_wx' => 1
            ]);
            $uid = $user->id;

            // 添加用户身份信息
            $identity = (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => 'open',
                'identifier' => $openid,
                'credential' => $accessToken,
                'remark' => $wxUserInfo['unionid'],
                'verified' => 1
            ]);

            DB::commit();
            return $identity;
        } catch (Exception $e) {
            DB::rollBack();
            throw new RegisterException();
        }
    }

    /**
     * 绑定微信号
     *
     * @throws AccountIsExistException
     * @throws BindingLoginModeException
     * @throws Exception
     * @throws TokenException
     * @throws WeChatException
     */
    public function bind()
    {
        $uid = TokenFactory::getCurrentUID();

        // 查看用户是否已绑定微信号
        $user = User::find($uid);
        if ($user->is_bind_wx == 1) {
            throw new BindingLoginModeException('该账号已绑定微信号，无法继续绑定', 0, 409);
        }

        // 获取access_token及openid
        $wxBaseInfo = self::getWxResult($this->loginUrl);
        $openid = $wxBaseInfo['openid'];
        $accessToken = $wxBaseInfo['access_token'];

        // 查看该微信号是否已被使用
        $userAuth = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', 'open')
            ->where('identifier', $openid)
            ->first();
        if ($userAuth) {
            throw new AccountIsExistException('该微信号已与其他账号绑定，请先解除绑定');
        }

        // 拼接获取用户详细信息的url
        $userInfoUrl = sprintf($this->userInfoUrl, $accessToken, $openid);
        // 获取用户详细信息
        $wxUserInfo = self::getWxResult($userInfoUrl);

        DB::beginTransaction();
        try {
            // 为用户创建微信登录方式
            (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => 'open',
                'identifier' => $openid,
                'credential' => $accessToken,
                'remark' => $wxUserInfo['unionid'],
                'verified' => 1
            ]);

            // 更新用户微信号为已绑定
            $user->is_bind_wx = 1;
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new BindingLoginModeException('绑定微信号失败');
        }
    }

    /**
     * 调用微信接口
     *
     * @param $url
     * @return mixed
     * @throws Exception
     * @throws WeChatException
     */
    public static function getWxResult($url)
    {
        $result = curl($url);

        $wxResult = json_decode($result, true);

        if (empty($wxResult)) {
            throw new Exception('获取用户信息失败，微信内部错误');
        }

        if (array_key_exists('errcode', $wxResult)) {
            throw new WeChatException($wxResult['errmsg'], $wxResult['errcode']);
        }

        return $wxResult;
    }
}