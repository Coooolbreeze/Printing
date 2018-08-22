<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 15:15
 */

namespace App\Models;


class RecommendEntity extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image')->withDefault();
    }
}