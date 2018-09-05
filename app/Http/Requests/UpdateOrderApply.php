<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/3
 * Time: 11:09
 */

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class UpdateOrderApply extends Request
{
    public function rules()
    {
        return [
            'status' => [
                Rule::in([1, 2])
            ]
        ];
    }
}