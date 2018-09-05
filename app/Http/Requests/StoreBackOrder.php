<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/5
 * Time: 22:27
 */

namespace App\Http\Requests;


class StoreBackOrder extends Request
{
    public function rules()
    {
        return [
            'user_id' => 'required'
        ];
    }
}