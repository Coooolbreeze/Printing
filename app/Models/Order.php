<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 15:58
 */

namespace App\Models;


class Order extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\OrderLog');
    }

    public function expresses()
    {
        return $this->hasMany('App\Models\OrderExpress');
    }
}