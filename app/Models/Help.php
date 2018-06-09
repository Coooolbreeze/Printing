<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 16:13
 */

namespace App\Models;


class Help extends Model
{
    public function helpCategory()
    {
        return $this->belongsTo('App\Models\HelpCategory');
    }
}