<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:04
 */

namespace App\Models;


class Gift extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}