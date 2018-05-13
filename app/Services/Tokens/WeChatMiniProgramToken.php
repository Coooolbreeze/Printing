<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/12
 * Time: 20:12
 */

namespace App\Services\Tokens;


use App\Exceptions\RegisterException;
use App\Models\User;
use Exception;
use DB;

class WeChatMiniProgramToken extends BaseToken
{
    private $code;
    private $appId;
    private $appSecret;
    private $loginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->appId = config('wxMiniProgram.app_id');
        $this->appSecret = config('wxMiniProgram.app_secret');
        $this->loginUrl = sprintf(config('wxMiniProgram.login_url'), $this->appId, $this->appSecret, $this->code);
    }

    /**
     * 实现父类方法，获取用户身份实例
     *
     * @return mixed
     * @throws RegisterException
     * @throws \App\Exceptions\WeChatException
     */
    public function identity()
    {
        // 获取openid
        $baseInfo = WeChatOpenToken::getWxResult($this->loginUrl);
        $openid = $baseInfo['openid'];

        // 查找用户身份
        $identity = (new $this->model)->where('platform', 'wx')
            ->where('identity_type', 'mp')
            ->where('identifier', $openid)
            ->first();

        // 如果身份不存在，则创建用户身份
        if (!$identity) {
            $identity = $this->createUserIdentity($openid);
        }

        return $identity;
    }

    /**
     * 创建用户
     *
     * @param $openid
     * @return mixed
     * @throws Exception
     * @throws RegisterException
     */
    private function createUserIdentity($openid)
    {
        DB::beginTransaction();
        try {
            // 添加用户信息
            $user = User::create([
                'nickname' => '小萌新',
                'avatar' => 'https://lwx-images.oss-cn-beijing.aliyuncs.com/avatar.jpg',
                'sex' => 0
            ]);
            $uid = $user->id;

            // 添加用户身份信息
            $identity = (new $this->model)->create([
                'user_id' => $uid,
                'platform' => 'wx',
                'identity_type' => 'mp',
                'identifier' => $openid,
                'credential' => '',
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