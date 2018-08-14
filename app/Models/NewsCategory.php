<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/14
 * Time: 15:48
 */

namespace App\Models;


class NewsCategory extends Model
{
    public function news()
    {
        return $this->hasMany('App\Models\News');
    }
}