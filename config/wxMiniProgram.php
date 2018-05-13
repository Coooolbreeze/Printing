<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/12
 * Time: 20:15
 */

return [
    /**
     * 微信小程序的app_id
     */
    'app_id' => env('WX_MINI_PROGRAM_APP_ID'),

    /**
     * 微信小程序的app_secret
     */
    'app_secret' => env('WX_MINI_PROGRAM_APP_SECRET'),

    /**
     * 微信小程序登录的url
     */
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
];