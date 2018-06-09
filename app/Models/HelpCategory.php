<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 16:13
 */

namespace App\Models;


class HelpCategory extends Model
{
    public function helps()
    {
        return $this->hasMany('App\Models\Help');
    }
}