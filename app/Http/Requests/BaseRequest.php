<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/22
 * Time: 10:08
 */

namespace App\Http\Requests;


class BaseRequest extends \Illuminate\Http\Request
{
    public function expectsJson()
    {
        return true;
    }

    public function wantsJson()
    {
        return true;
    }
}