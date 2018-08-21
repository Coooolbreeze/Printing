<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 15:14
 */

namespace App\Models;


class Banner extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}