<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/31
 * Time: 17:23
 */

namespace App\Enum;


class OrderPayTypeEnum
{
    // 支付宝支付
    const ALI_PAY = 1;

    // 微信支付
    const WX_PAY = 2;

    // 余额支付
    const BALANCE = 3;

    // 后台支付
    const BACK_PAY = 4;
}