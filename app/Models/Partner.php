<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 10:25
 */

namespace App\Models;


class Partner extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}