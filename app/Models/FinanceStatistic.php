<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 11:32
 */

namespace App\Models;


class FinanceStatistic extends Model
{
    public static function write($orderNo, $price, $type = 1)
    {
        return self::create([
            'order_no' => $orderNo,
            'price' => $price,
            'type' => $type
        ]);
    }
}