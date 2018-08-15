<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 17:49
 */

namespace App\Http\Requests;


class StoreNews extends Request
{
    public function rules()
    {
        return [
            'news_category_id' => 'required',
            'image_id' => 'required',
            'title' => 'required',
            'from' => 'required',
            'body' => 'required'
        ];
    }
}