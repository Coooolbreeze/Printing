<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 11:40
 */

namespace App\Http\Requests;


class StoreAdminAccount extends Request
{
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'roles' => 'required'
        ];
    }
}