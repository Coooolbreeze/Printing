<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 15:59
 */

namespace App\Models;


class OrderLog extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}