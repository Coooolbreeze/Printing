<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/18
 * Time: 21:36
 */

namespace App\Http\Resources;


class HotKeywordResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'sort' => $this->sort
        ];
    }
}