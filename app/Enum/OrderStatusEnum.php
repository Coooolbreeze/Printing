<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/31
 * Time: 16:34
 */

namespace App\Enum;


class OrderStatusEnum
{
    // 已过期
    const EXPIRE = 0;

    // 待支付
    const UNPAID = 1;

    // 已支付
    const PAID = 2;

    // 待发货
    const UNDELIVERED = 3;

    // 已发货
    const DELIVERED = 4;

    // 已收货
    const RECEIVED = 5;

    // 已评论
    const COMMENTED = 6;

    // 未通过
    const FAILED = 7;

    // 已退款
    const REFUNDED = 8;
}