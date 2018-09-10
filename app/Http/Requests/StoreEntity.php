<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/9
 * Time: 0:43
 */

namespace App\Http\Requests;


class StoreEntity extends Request
{
    public function rules()
    {
        return [
            'name' => 'unique:entities'
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '商品名重复'
        ];
    }
}