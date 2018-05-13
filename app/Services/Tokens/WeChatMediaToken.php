<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/29
 * Time: 20:38
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

class WeChatMediaToken extends BaseToken
{
    private $code;
    private $appID;
    private $appSecret;
    private $loginUrl;
    private $userInfoUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appID = config('wxMedia.app_id');
        $this->appSecret = config('wxMedia.app_secret');
        $this->userInfoUrl = config('wxMedia.user_info_url');
        $this->loginUrl = sprintf(config('wxMedia.login_url'), $this->appID, $this->appSecret, $this->code);
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
        $wxBaseInfo = WeChatOpenToken::getWxResult($this->loginUrl);
        $openid = $wxBaseInfo['openid'];
        $accessToken = $wxBaseInfo['access_token'];

        // 查找用户身份
        $identity = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', 'media')
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
        $wxUserInfo = WeChatOpenToken::getWxResult($userInfoUrl);

        DB::beginTransaction();
        try {
            // 添加用户信息
            $user = User::create([
                'nickname' => $wxUserInfo['nickname'],
                'avatar' => $wxUserInfo['headimgurl'],
                'sex' => $wxUserInfo['sex']
            ]);
            $uid = $user->id;

            // 添加用户身份信息
            $identity = (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => 'media',
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
}