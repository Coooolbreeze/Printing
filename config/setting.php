<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/22
 * Time: 0:35
 */

return [
    /**
     * 访问令牌过期时间
     */
    'access_token_expire_in' => env('ACCESS_TOKEN_EXPIRE_IN'),

    /**
     * 刷新令牌过期时间
     */
    'refresh_token_expire_in' => env('REFRESH_TOKEN_EXPIRE_IN'),

    /**
     * sku组合分隔符
     */
    'sku_separator' => '|',

    /**
     * 每积分需消费金额
     */
    'accumulate_points_money' => env('ACCUMULATE_POINTS_MONEY'),

    /**
     * 包邮起步价
     */
    'free_express' => env('FREE_EXPRESS'),

    /**
     * 首重重量
     */
    'first_weight' => env('FIRST_WEIGHT'),

    /**
     * 续重重量
     */
    'additional_weight' => env('ADDITIONAL_WEIGHT'),
];