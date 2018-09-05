<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/3
 * Time: 10:08
 */

namespace App\Models;


use App\Services\Tokens\TokenFactory;

class OrderApply extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_no', 'order_no');
    }
}