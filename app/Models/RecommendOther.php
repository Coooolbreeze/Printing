<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 14:28
 */

namespace App\Models;


class RecommendOther extends Model
{
    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }
}