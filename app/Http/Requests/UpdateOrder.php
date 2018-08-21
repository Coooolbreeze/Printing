<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/31
 * Time: 15:35
 */

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UpdateOrder extends Request
{
    public function rules()
    {
        // TODO 验证
        return [
            'status' => [
                Rule::in([0, 3, 4, 5, 7])
            ]
        ];
    }
}